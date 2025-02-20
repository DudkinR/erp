<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Personal;
use App\Models\Struct;
use App\Models\Position;
use App\Models\Status;
use App\Models\Comment;
use App\Models\Role;
use App\Models\Room;

use App\Models\User;
use App\Models\Division;
use App\Models\Phone;
use App\Models\Building;
use App\Helpers\FileHelpers as FileHelpers;
use App\Helpers\StringHelpers as StringHelpers;
use App\Helpers\CommonHelper as CommonHelper;
use Str;

class PersonalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      // return  Personal::find(1)->positions()->get();
      $divisions = Division::where('name', 'ЗП КЕР-ВО')->get();
        $personals = Personal::with('positions')
        -> whereHas('divisions', function($query) use ($divisions){
            $query->whereIn('division_id', $divisions->pluck('id'));
        })
        ->orderBy('id', 'desc')
        ->limit(10)
        ->with('positions', 'divisions','rooms','phones')
        ->get();
        //return $personals;
        //$personals = Personal::orderBy('id', 'desc')->get();
        return view('personals.index', compact('personals'));
    }
    public function search(Request $request)
    {
        $search = $request->search;
        $personals = Personal::where('fio', 'like', '%' . $search . '%')
            ->orWhere('tn', 'like', '%' . $search . '%')
            ->orWhere('fio', 'like', '%' . $search . '%')
            ->orWhere('email', 'like', '%' . $search . '%')
            // position->name search
            ->orWhereHas('positions', function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })
            // division->name search
            ->orWhereHas('divisions', function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })
            // room->name search
            ->orWhereHas('rooms', function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            })
            // phone->phone search
            ->orWhereHas('phones', function ($query) use ($search) {
                $query->where('phone', 'like', '%' . $search . '%');
            })
            ->orderBy('id', 'desc')
            ->limit(100)
            ->with('positions', 'divisions','rooms','phones')
            ->get();
            foreach($personals as $personal){
             $this->cleaning_double($personal->id);
            }
        return $personals;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $divisions = Division::orderBy('name')->get();
        return view('personals.create', compact('divisions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    // Проверка наличия персонала с таким же tn или fio
    $personal = Personal::where('tn', $request->tn)
        ->orWhere('fio', $request->fio)
        ->first();
        
    if ($personal) {
        return redirect('/personal')->with('error', 'Personal already exists!');
    }

    // Проверка наличия пользователя с таким же email
    $user = User::where('email', $request->email)->first();

    if ($user) {
        // Проверка наличия персонала с таким же email
        $personalWithEmail = Personal::where('email', $request->email)->first();
        if ($personalWithEmail) {
            return redirect('/personal')->with('error', 'A personal with this email already exists!');
        } else {
            // Создание нового персонала и привязка к существующему пользователю
            $personal = new Personal([
                'tn' => $request->tn,
                'nickname' => $request->nickname,
                'fio' => $request->fio,
                'email' => $request->email,
                'phone' => $request->phone,
                'date_start' => $request->date_start,
                'status' => $request->status
            ]);
            $personal->save();

            // Обновление информации о пользователе
            $user->tn = $request->tn;
            $user->name = $request->fio;
            $user->save();
        }
    } else {
        // Создание нового пользователя и персонала
        $personal = new Personal([
            'tn' => $request->tn,
            'nickname' => $request->nickname,
            'fio' => $request->fio,
            'email' => $request->email,
            'phone' => $request->phone,
            'date_start' => $request->date_start,
            'status' => $request->status
        ]);
        $personal->save();

        $user = new User([
            'tn' => $request->tn,
            'name' => $request->fio,
            'email' => $request->email,
            'password' => bcrypt($request->tn)
        ]);
        $user->save();
    }

    // Обновление позиций персонала
    if ($request->position) {
        $personal->positions()->detach();
        $personal->positions()->attach($request->position);
    }
    // division
    if ($request->division_id) {
        $personal->divisions()->attach($request->division_id);
    }

    // Добавление комментария
    if ($request->comment) {
        $comment = new Comment;
        $comment->comment = $request->comment;
        $comment->save();
        $personal->comments()->attach($comment->id);
    }

    // Обновление ролей пользователя
    $user->roles()->detach();
    $user->roles()->attach($request->roles);


    return redirect('/personal')->with('success', 'Personal saved!');
}


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $personal = Personal::find($id);
        return view('personals.show', compact('personal'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $personal = Personal::find($id);
        // find user by tn
        $user = User::where('tn', $personal->tn)->first();
        if(!$user){
            $user = new User([
                'tn' => $personal->tn,
                'name' =>$personal->fio,
                'email' => $personal->email,
                'password' => bcrypt($personal->tn)
            ]);
            $user->save();            
            // add user roles
            $user->roles()->attach(4);
        }
        $divisions = Division::orderBy('name')->get();
        return view('personals.edit', compact('personal', 'divisions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $personal = Personal::find($id);
        if(!$request->tn){
            $personal->tn=$request->tn;
        }
        if(!$request->email){
         $personal->email = $request->email;
        }
        if(!$request->phone){
            $personal->phone = $request->phone;
        }
        if(!$request->date_start){
            $personal->date_start = $request->date_start;
        }
        if(!$request->status){
            $personal->status = $request->status;
        }
        $personal->save();
        $user = User::where('tn', $request->tn)->first();
        if(!$user){
            $user = new User([
                'tn' => $request->tn,
                'email' => $request->email,
                'password' => bcrypt($request->tn)
            ]);
            $user->save();            
        }
        // email 
        if($request->email){
            $user->email = $request->email;
            $user->save();
        }
        if($request->position){
                $personal->positions()->detach();
                $personal->positions() ->attach($request->position);
        }
        //division
        if($request->division_id){
            $personal->divisions()->detach();
            $personal->divisions()->attach($request->division_id);
        }
        // if not link position_division add new
        $position = Position::find($request->position);
        $division = Division::find($request->division_id);
        if($position->divisions()->where('division_id', $division->id)->count() == 0){
            $position->divisions()->attach($division->id);
        }
        // comment add
        if($request->comment){
            $comment = new Comment;
            $comment->comment = $request->comment;
            $comment->save();
            $personal->comments()->attach($comment->id);
        }
        $user->roles()->detach();
        $user->roles()->attach($request->roles);
        return redirect('/personal')->with('success', 'Personal updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $personal = Personal::find($id);
        $personal->delete();
        return redirect('/personal')->with('success', 'Personal deleted!');
    }
    //import personal data from csv file
    public function import()
    {
        set_time_limit(0);
        $personals= Personal::whereNotIn('tn', User::pluck('tn'))
        ->with('user')
        ->get();
        foreach ($personals as $personal) {
            if(!$user =User::where('email', $personal->email)->first()){
            // Створюємо нового користувача
            $user = new User([
                'tn' => $personal->tn,
                'name' => $personal->fio,
                'email' => $personal->email,
                'password' => bcrypt($personal->tn)
            ]);}
            else {
                $user->tn=$personal->tn;
                $user->name=$personal->fio;
                 $user->password= bcrypt($personal->tn);
            }
            $user->save();

            // Додаємо роль для користувача
            $user->roles()->attach(4);
        }
         Personal::whereNotIn('tn', User::pluck('tn'))
        ->with('user')
        ->get()->count();
       return  view('personals.import');
    }
    // import personal data from csv file to database
    public function importData(Request $request)
    {
        set_time_limit(0);

        $type_of_file = $request->type_of_file ?? 0;
        $csvData = FileHelpers::csvToArray($request->file('file'), $type_of_file);
        //dd(count($csvData)); 
        $pers=[]; $nonDiv=[];
        foreach ($csvData as $line) {
            $data = str_getcsv($line, ";"); 

            if ($data[1] === 'TAB_NO') {
                continue;
            }
            $exname=explode(" ",$data[3]);
            $name=""; $i=0;
            foreach($exname as $w){
                if($i==0){
                    $i++;
                    $num_div=intval($w); // Використовуємо intval() для перетворення в ціле число
        
                }
                elseif($i==1){
                    $name.=$w;
                    $i++;
                }
                else{
                    $name.=" ".$w;
                }
            }
            $name_u=""; $i=0;
            $exname=explode(" ",$data[4]);
            foreach($exname as $w){
                if($i==0){
                    $i++;                      
                }
                elseif($i==1){
                    $name_u.=$w;
                    $i++;
                }
                else{
                    $name_u.=" ".$w;
                }
            }
            if($name_u=="") $name_u=$data[4];
        $division = Division::where('name', $name)->first();
        if (!$division) { 

                $division = new Division([
                    'in_id' =>$num_div,
                    'name' => $name,
                    'description' => $data[3],
                    'abv' => $name,
                    'slug' => StringHelpers::generateSlug($name),
                    'parent_id' => 0
                ]);
                $division->save();
            }
            else{
                $division->in_id=$num_div;
                $division->save();

            }
            $underdivision = Division::where('name', $name_u)->first();
            if (!$underdivision) {
                $underdivision = new Division([
                    'in_id' =>$num_div,
                    'name' => $name_u,
                    'description' => $data[4],
                    'abv' => $name_u,
                    'slug' => StringHelpers::generateSlug($name_u),
                    'parent_id' => $division->id
                ]);
                $underdivision->save();
                $nonDiv[]=$name." ".$name_u." ". $data[1] ;
            } 
            else{
                $underdivision->in_id=$num_div;
                $underdivision->save();  
            }
            $personal = Personal::where('tn', $data[1])->first();
            if ($personal) {
                if($underdivision){
                $personal->divisions()->detach();
                $personal->divisions()->attach($underdivision->id); 
                $personal->save(); 
                $pers[]=$personal->divisions;
                }
            }
            else{
            $nonDiv[]=$name." ".$name_u." ". $data[1] ;
            }
        }
    return [$nonDiv,$pers];
    return redirect('/personal')->with('success', 'Personal data imported!');
    }
    public function insert_text($text, $start, $end, $separator = ' ')
    {
     $words = explode($separator, $text);
        $result = '';
        // сколько слов нужно пропустить в начале
        // сколько слов нужно упустить сзади
        $count = count($words);
        for ($i = $start; $i < $count - $end; $i++) {
            $result .= $words[$i] . ' ';
        }
        return $result;
    }

    // nickname function
    public function nickname($fullname)
    {
        $nickname = '';
        $words = explode(' ', $fullname);
        // first word
        $nickname .= $words[0];
        return $nickname;

    }
    // посада/структура
    public function pos_struct($DEPT,$UCHASTOK,	$POSADA)
    {
        $owner_division = null;
        $owner_subdivision = null;

        if (!empty($DEPT)) {
            $dt=explode(' ', $DEPT);
            $kod=$dt[0];
            $division = Division::firstOrCreate(
                ['name' => $dt[1]],
                [
                    'description' =>$dt[1],
                    'abv' => $dt[1],
                    'slug' => StringHelpers::generateSlug($dt[1]),
                    'parent_id' => 0
                ]
            );
            $owner_division = $division->id;
           // return $division;
            if (!empty($UCHASTOK)) {
                $subdivision = Division::firstOrCreate(
                    ['name' => $UCHASTOK],
                    [
                        'description' => $UCHASTOK,
                        'abv' => $UCHASTOK,
                        'slug' => StringHelpers::generateSlug($UCHASTOK),
                        'parent_id' => $owner_division
                    ]
                );
                $owner_subdivision = $subdivision->id;
            }
        }
        $struct = Struct::where('name', $division->name)->first();
        if(!$struct){
            $struct = new Struct([
                'name' => $division->name,
                'description' => $division->name,
                'status' => 'active',
                'abv' =>  StringHelpers::abv($division->name), 
                'kod' => $kod
            ]);
            $struct->save();
        }
            $pos = Position::where('name', trim($POSADA))->first();
            if(!$pos){
                $pos = new Position([
                    //['name', 'description', 'start', 'data_start', 'closed', 'data_closed'];
                    'name' => $POSADA,
                    'description' => $POSADA,
                    'start' => 'active'
                ]);
                $pos->save();
        }
        
        //записать один раз другие убрать
        //  delete old positions
        $pos->structuries()->detach();
        $pos->structuries()->attach($struct->id);
        
        return $pos->id;
    }
    // position status
    public function pos_status($text)
    {
        $status = Status::where('name', $text)->first();
        if(!$status){
            $status = new Status([
                'name' => $text,
                'description' => $text
            ]);
            $status->save();
        }
        return $status->id;
    }
    public function cleaning_double($id){
        $personal = Personal::find($id);
        $phones = $personal->phones;
        $phones = $phones->unique('id');
        $personal->phones()->sync($phones);
        // positions
        $positions = $personal->positions;
        $positions = $positions->unique('id');
        $personal->positions()->sync($positions);
        // divisions
        $divisions = $personal->divisions;
        $divisions = $divisions->unique('id');
        $personal->divisions()->sync($divisions);
        // rooms
        $rooms = $personal->rooms;
        $rooms = $rooms->unique('id');
        $personal->rooms()->sync($rooms);
      return $personal;

    }

}
