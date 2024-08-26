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
        $personals = Personal::with('positions')
        ->orderBy('id', 'desc')
        ->limit(1000)
        ->get();
        //$personals = Personal::orderBy('id', 'desc')->get();
        return view('personals.index', compact('personals'));
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
        // division
        if($request->division_id){
            $personal->divisions()->sync($request->division_id);
        }
        // find user by tn
        $user = User::where('tn', $request->tn)->first();
        if(!$user){
            $user = new User([
                'tn' => $request->tn,
                'email' => $request->email,
                'password' => bcrypt($request->tn)
            ]);
            $user->save();            
        }
        if($request->position){
            // delete old positions
            $personal->positions()->detach();
            $personal->positions()->attach($request->position);
        }
        // comment add
        if($request->comment){
            $comment = new Comment;
            $comment->comment = $request->comment;
            $comment->save();
            $personal->comments()->attach($comment->id);
        }
        // delete old roles
        $user->roles()->detach();
        // add user roles
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
        return view('personals.import');
    }
    // import personal data from csv file to database
    public function importData(Request $request)
    {
  // 0	1TAB_NO	2PIB	3DEPT	4UCHASTOK	5POSADA	ROOM	6KORPUS	7NOMER_ROOM	8NAME_ROOM	9TEL_NUMBER
     
       // Maximum execution time of 60 seconds exceeded
        set_time_limit(0);
        if($request->type_of_file)
        $type_of_file =$request->type_of_file;
        else
        $type_of_file = 0;
        $csvData = FileHelpers::csvToArray($request->file('file'),$type_of_file);
        //return $csvData;
        foreach ($csvData as $line) {
            $data = str_getcsv($line, ";"); 
            if($data[1]=='TAB_NO') {continue;}
            $personal = Personal::where('tn', $data[1])->first();
            if(!$personal){
                $personal = new Personal();
                $personal->tn = $data[1];
                $personal->nickname = $this->nickname($data[2]);
                $personal->fio = $data[2];
                $fi = explode(' ', $data[2]);
                if(count($fi)>1){
                $email = StringHelpers::generateSlug($fi[0].'.'. $fi[1]). '@khnpp.atom.gov.ua';            
                $email=  strtolower($email);
                $personal->email = $email;
                // phone
                $personal->phone = $data[10];
                $personal->date_start = CommonHelper::formattedDate(now());
                $personal->status = 'На роботі';
                $personal->save();
             }
                else
             {
                $personal->tn = $data[1];
                $personal->nickname = $this->nickname($data[2]);
                $personal->fio = $data[2];
                $fi = explode(' ', $data[2]);
                $email = StringHelpers::generateSlug($fi[0].'.'.$fi[1]).'@khnpp.atom.gov.ua';
                $personal->phone = $data[10];               
                $email=  strtolower($email);
                $personal->email = $email;
                $personal->date_start = CommonHelper::formattedDate(now());
                $personal->status = 'На роботі';
           }
            $personal->save();
            $phone = Phone::firstOrCreate(
                ['phone' => $data[10]],
                ['phone' => $data[10]]
            );
            $phone->save();
            // personal_phone
         // Обновление телефонов
            $personal->phones()->detach();
            $personal->phones()->attach($phone->id);
            $divisionName = $this->insert_text($data[4], 1, 0, ' ');
            $division = Division::where('name', '%LIKE%', $divisionName)->first();
            if($division){
                $personal->divisions()->detach();
                $personal->divisions()->attach($division->id);
            }
           // Обновление здания (Building)
            $buildingName = $data[6];
            $building = Building::where(                    
                'name', $buildingName
            )->first();
            if($building){
                $personal->buildings()->detach();
                $personal->buildings()->attach($building->id);
            }
            // Обновление комнаты (Room)
            $roomName = $data[8];
            $room = Room::where('name',  $roomName)->first();
            if($room){
                $personal->rooms()->detach();
                $personal->rooms()->attach($room->id);
            }

            }
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
}
