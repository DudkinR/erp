<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Personal;
use App\Models\Struct;
use App\Models\Position;
use App\Models\Status;
use App\Models\User;
use App\Models\Comment;
use App\Helpers\FileHelpers as FileHelpers;
use App\Helpers\StringHelpers as StringHelpers;
use App\Helpers\CommonHelper as CommonHelper;

class PersonalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $personals = Personal::with('positions')
        ->get();
        //$personals = Personal::all();
        return view('personals.index', compact('personals'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('personals.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {        
        $personal = Personal::where('tn', $request->tn)
        ->orWhere('fio', $request->fio)
        ->first();
        if($personal){
            return redirect('/personal')->with('error', 'Personal already exists!');
        }
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
        // find user by tn
        $user = User::where('tn', $request->tn)->first();
        if(!$user){
            $user = new User([
                'tn' => $request->tn,
                'name' => $request->fio, // 'name' => 'fio
                'email' => $request->email,
                'password' => bcrypt($request->tn)
            ]);
            $user->save();            
        }
        else {
            $user->name = $request->fio;
            $user->email = $request->email;
            $user->save();
        }
        if($request->position){
            // delete old positions
            $personal->positions()->detach();
            $personal->positions()->attach($request->position);
        }
        //create comment
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
        return view('personals.edit', compact('personal'));
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
        if($request->type_of_file)
        $type_of_file =$request->type_of_file;
        else
        $type_of_file = 0;
        $csvData = FileHelpers::csvToArray($request->file('file'),$type_of_file);
        //return $csvData;
        foreach ($csvData as $line) {
        $data = str_getcsv($line, ";"); 
        $personal = Personal::where('tn', $data[1])->first();
        if(!$personal){
            $personal = new Personal();
            $personal->tn = $data[1];
            $personal->nickname = $this->nickname($data[0]);
            $personal->fio = $data[0];
            $personal->date_start = CommonHelper::formattedDate($data[3]);
            $personal->status = $data[4];
            $personal->save();
        }
            else{
                $personal->tn = $data[1];
                $personal->nickname = $this->nickname($data[0]);
                $personal->fio = $data[0];
                $personal->date_start = CommonHelper::formattedDate($data[3]);
                $personal->status = $data[4];
            }
            $personal->save();
            $position =$this->pos_struct($data[2]);
            $personal->positions()->attach($position);
            $status = $this->pos_status($data[4]);
            $data_status = CommonHelper::formattedDate($data[5]);
            $personal->status()->updatedAt($status, [
                'date_start' =>NULL,  // Змініть це значення на потрібне вам
                'date_end' => $data_status  // Змініть це значення на потрібне вам
            ]);
         
       
    }
    return redirect('/personal')->with('success', 'Personal data imported!');
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
    public function pos_struct($text)
    {
         $data= explode('/', $text);   
         $struct = Struct::where('name', $data[1])->first();
         if(!$struct){
             $struct = new Struct([
                 'name' => $data[1],
                 'abv' => StringHelpers::abv($data[1]),
                 'description' => $data[1],
                 'status' => 'active',
                 'parent_id' => 30
             ]);
             $struct->save();
            }
            $pos = Position::where('name', trim($data[0]))->first();
            if(!$pos){
                $pos = new Position([
                    //['name', 'description', 'start', 'data_start', 'closed', 'data_closed'];
                    'name' => $data[0],
                    'description' => '',
                    'start' => 'active'
                ]);
                $pos->save();
        }
        //записать один раз другие убрать
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
