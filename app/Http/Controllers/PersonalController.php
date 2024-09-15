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
        $personals = Personal::with('positions')
        ->orderBy('id', 'desc')
        ->limit(0)
        ->with('positions', 'divisions','rooms','phones')
        ->get();
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
        $personal->tn = $request->tn;
        $personal->nickname = $request->nickname;
        $personal->fio = $request->fio;
        $personal->email = $request->email;
        $personal->phone = $request->phone;
        $personal->date_start = $request->date_start;
        $personal->status = $request->status;
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
        return redirect('/profile')->with('success', 'Personal updated!');
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
        return view('personals.import');
    }
    // import personal data from csv file to database
    public function importData(Request $request)
{
    set_time_limit(0);

    $type_of_file = $request->type_of_file ?? 0;
    $csvData = FileHelpers::csvToArray($request->file('file'), $type_of_file);

    foreach ($csvData as $line) {
        $data = str_getcsv($line, ";"); 

        if ($data[1] === 'TAB_NO') {
            continue;
        }

        $personal = Personal::where('tn', $data[1])->first();
        if (!$personal) {
            $personal = new Personal();
            $personal->tn = $data[1];
            $personal->nickname = $this->nickname($data[2]);
            $personal->fio = $data[2];
            $fi = explode(' ', $data[2]);
            if (count($fi) > 1) {
                $email = strtolower(StringHelpers::generateSlug($fi[0] . '.' . $fi[1])) . '@khnpp.atom.gov.ua';
                $personal->email = $email;
            }
            $personal->phone = $data[11];
            $personal->date_start = CommonHelper::formattedDate(now());
            $personal->status = 'На роботі';
            $personal->save();
        }

        $phone = Phone::firstOrCreate(['phone' => $data[11]]);

        // Обновление телефонов
        $personal->phones()->syncWithoutDetaching($phone->id);


        // Обновление комнаты (Room)
        $IDroom = $data[10];
        $room = Room::where('IDname', $IDroom)->first();
        if ($room) {
            $personal->rooms()->syncWithoutDetaching([$room->id]);
        }

        // Обновление позиции (Position)
        $position = Position::where('name', $data[5])->first();
        if (!$position) {
            $position = new Position([
                'name' => $data[5],
                'description' => $data[5],
                'start' => 'active'
            ]);
            $position->save();
        }
        $personal->positions()->sync($position->id);
        $this->cleaning_double($personal->id);
    }
    
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
