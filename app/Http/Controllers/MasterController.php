<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// Master
use App\Models\Master;
use Illuminate\Support\Facades\Auth;
use App\Models\Doc;
use App\Models\Personal;
use App\Models\Resource;
use App\Models\Briefing;
use GuzzleHttp\Psr7\Response;
use Whoops\Exception\Formatter;
// Mistake
use App\Models\Mistake;
use App\Models\Goodpractice;


class MasterController extends Controller
{
    // index
    public function index()
    {
        $I_M=  Auth::user()->profile()->pluck('id')->first();
        $masters = Master::where('author_id', $I_M)
        ->where('done','<', 2)
        ->with('docs', 'personals', 'resources')
        ->get();
        return view('master.index', compact('masters'));
    }
    // create
    public function create()
    {
        $docs = Doc::all()->keyBy('id')->values();
        $personals = Personal::all()->keyBy('id')->values();
        $resources = Resource::all()->keyBy('id')->values();
        return view('master.create', compact('docs', 'personals', 'resources'));
    }
    //search_text_task
    public function search_text_task(Request $request)
    {
        // Проверяем, передан ли параметр task в запросе
        if (!$request->has('task') || empty($request->task)) {
            return response()->json(['error' => 'Параметр task не указан'], 400);
        }
    
        // Выполняем поиск по тексту с учетом указанного параметра task
        $tasks = Master::where('text', 'like', '%' . $request->task . '%')->pluck('text');
    
    
        // Возвращаем найденные результаты
        return response()->json($tasks);
    }
    
    // store
    public function store(Request $request)
    {
        $author_id =  Auth::user()->profile()->pluck('id')->first();
        $master = new Master();
        $master->author_id = $author_id;
        $master->text = $request->task;
        $master->urgency = $request->urgency;
        $master->deadline = $request->deadline;
        $master->basis = $request->basis;
        // comment
        $master->comment = $request->comment;
        $master->who = $request->who;
        $master->save();
        return redirect()->route('master.index');
    }
    // show
    public function show($id)
    {
        $master = Master::find($id);
        return view('master.show', compact('master'));
    }
    // edit
    public function edit($id)
    {
        $master = Master::find($id);
        $docs = Doc::all()->keyBy('id')->values();
        $personals = Personal::all()->keyBy('id')->values();
        $resources = Resource::all()->keyBy('id')->values();
        return view('master.edit', compact('master', 'docs', 'personals', 'resources'));
    }
    // step 1
    public function step1($id)
    {
       
        $master = Master::with('docs', 'personals', 'resources')->find($id);
        $docs = Doc::whereNotIn('id', $master->docs->pluck('id'))->get();        
        $personals = Personal::whereNotIn('id', $master->personals->pluck('id'))
            ->get();
        $resources = Resource::whereNotIn('id', $master->resources->pluck('id'))
            ->get();
        return view('master.step1', compact('master', 'docs', 'personals', 'resources'));
    }
    // step 2 Request $request, $id
    public function step2(Request $request, $id)
    {
        $master = Master::find($id);
        $master->estimate = $request->estimate;
        $resources=$request->resource;
        $rsrs=[];
        if(!$resources){
            $resources=[];
        }
        foreach($resources as $resource){
            if($resource==''){
                continue;
            }
            $rsr=Resource::find($resource);
            if(!$rsr){
                $rsr = new Resource();
                $rsr->name= $resource;
                $rsr->save();
                $rsrs[]=$rsr->id;
            }
            else {
                $rsrs[]=$resource;
            }

        }
        $master->save();
        $master->docs()->sync($request->docs);
        $master->personals()->sync($request->workers);
        $master->resources()->sync($rsrs);
        return redirect()->route('master.index');
    }
    public function step3($id){
        $master = Master::find($id);
        return view('master.step3', compact('master'));
    }
    public function step4($id){
        $master = Master::find($id);
        return view('master.step4', compact('master'));
    }
    public function step5(Request $request, $id)
    {
        $master = Master::find($id);
        $master->done = 2;
        $master->save();
        $user_id = Auth::user()->profile()->pluck('id')->first();
        $mistakes = $request->input('mistakes', []);
        $good_practices = $request->input('good_practices', []);
    
        foreach ($mistakes as $mistakeText) {
            $mistake = new Mistake();
            $mistake->user_id = $user_id;
            $mistake->text = $mistakeText;
            $mistake->save();
            $master->mistakes()->attach($mistake->id);
        }
    
        foreach ($good_practices as $practiceText) {
            $good_practice = new Goodpractice();
            $good_practice->user_id = $user_id;
            $good_practice->text = $practiceText;
            $good_practice->save();
            $master->goodpractices()->attach($good_practice->id);
        }
    
        return redirect()->route('master.index');
    }
    
    public function ending(Request $request, $id){
         $master = Master::find($id);
         if($request->done==0){
            // Создать новое задание
            $newMaster = $master->replicate(); // Создать копию текущего задания
            $newMaster->start = null; // Установить время старта в null
            $newMaster->end = null; // Установить время окончания в null
            $newMaster->done = 0; // Новое задание не завершено
            $newMaster->save(); // Сохранить новое задание

            // Очистить назначенных персоналов для нового задания
            $newMaster->personals()->detach(); // Удалить все назначенные персонал
            // Закрыть текущее задание
            $master->end = now();
            $master->done = 1;
            $master->save();
         }
         else{
            $master->done=1;
            $master ->end=now();
            $master ->save();
         }
         return redirect()->route('master.index');
    }
    // инструктаж
    public function briefing(Request $request)
    {
        // Найти Master по ID
         $master = Master::find($request->master_id);
        $personal = Personal::find($request->personal_id);

        // Найти инструктаж для Master
        $briefing = $master->briefing;

        // Если инструктажа нет, создать его
        if (!$briefing) {
            $briefing = new Briefing();
            $briefing->master_id = $master->id;  // Установить связь с мастером
            $briefing->save();
        }

        // Проверка табельного номера и добавление связи между персоналом и инструктажем
        if ($personal && $personal->tn == $request->tn) {
            $briefing->personals()->attach($personal->id);
            return response()->json(['status' => 'success', 'worker_id'=>$personal->id, 'briefing' => $briefing], 200);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Personal not found or TN does not match'], 400);
        }
    }
     // running
     public function running(Request $request){
         $master= Master::find($request->mi);
          $master->start = now();
          $master->save();
         return redirect()->route('master.index');
     }
    
    // update
    public function update(Request $request, $id)
    {
        $master = Master::find($id);
        $master->author_id = Auth::user()->personal_id;
        $master->text = $request->text;
        $master->basis = $request->basis;
        $master->who = $request->who;
        $master->urgency = $request->urgency;
        $master->deadline = $request->deadline;
        $master->estimate = $request->estimate;
        $master->start = $request->start;
        $master->end = $request->end;
        $master->done = $request->done;
        $master->comment = $request->comment;
        $master->save();
        $master->docs()->sync($request->doc_id);
        $master->personals()->sync($request->personal_id);
        $master->resources()->sync($request->resource_id);
        return redirect()->route('master.index');
    }
    // destroy
    public function destroy($id)
    {
        $master = Master::find($id);
        // delete all related records
        $master->docs()->detach();
        $master->personals()->detach();
        $master->resources()->detach();        
        $master->delete();
        return redirect()->route('master.index');
    }
    

}
