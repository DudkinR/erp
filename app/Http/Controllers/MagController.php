<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
// helpers
use App\Helpers\Helpers as Helpers;
use App\Models\Magtable;
use App\Models\Division;
use App\Models\Magcolumn;
use App\Models\Magmem;
use App\Models\Maglimit;
use App\Models\Magdatatext;
use App\Models\Magdatastr;
use App\Models\Magdataint;
use App\Models\Magdatafloat;
use App\Models\Magdatatime;
use App\Models\Magdatabool;
use Illuminate\Support\Facades\Auth;


class MagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $magtables = Magtable::all();
        return view('mag.index', compact('magtables'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $dimensions = Helpers::dimensions();
        $divisions = Division::all();
        $mems = Magmem::all();
        return view('mag.create', compact('dimensions', 'divisions' , 'mems'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
           $request->validate([
                'name' => 'required',
                'description' => 'required',
                'division_writer' => 'required',
                'division_reader' => 'required',
                'column_name' => 'required'
                       
            ]);
            //return request()->all();
            $magtable = new Magtable();
            $magtable->name = $request->name;
            $magtable->description = $request->description;
            // save
            $magtable->save();
            // division_writer !== all add param type = 0
            $all_divisions = Division::all();
            if (is_array($request->division_writer)) {
                if (!in_array('all', $request->division_writer)) {
                    $magtable->divisions()->attach($request->division_writer, ['type' => 0]); }
                else{
                $magtable->divisions()->attach( $all_divisions, ['type' => 0]);
            }
            }
            else{
                $magtable->divisions()->attach($request->division_writer, ['type' => 0]);
            }
            // division_reader !== all add param type = 1
            if (is_array($request->division_reader)) {
                if (!in_array('all', $request->division_reader)) {
                    $magtable->divisions()->attach($request->division_reader, ['type' => 1]); }
                else{
                $magtable->divisions()->attach( $all_divisions, ['type' => 1]);
            }
            }
            else{
                $magtable->divisions()->attach( $all_divisions, ['type' => 1]);
            }
            // columns create new magcolumns [name	description	type]
            $types= ['text'=>0, 'string'=>1, 'number'=>2, 'float'=>3, 'time'=>4, 'boolean'=>5];
           // $type_of_table= ['0'=>'Magdatatext', '1'=>'Magdatastr', '2'=>'Magdataint', '3'=>'Magdatafloat', '4'=>'Magdatatime', '5'=>'Magdatabool'];
            $column_names = $request->column_name;
            foreach($column_names as $key => $column_name){
                $magcolumn = new Magcolumn();
                $magcolumn->name = $column_name;
                $magcolumn->description = $request->{"description_".$key};
                $magcolumn->type = $types[$request->{"column_type_".$key}]; 
                //dimension_
                $magcolumn->dimensions = $request->{"dimension_".$key};
                $magcolumn->save();
                $magtable->magcolumns()->attach($magcolumn, ['number' => $key]);

                if($types[$request->{"column_type_".$key}]== '2' || $types[$request->{"column_type_".$key}]== '3'){
                // add maglimits to magcolumn
                $maglimit = new Maglimit();
                $maglimit->hfb = $request->{"high_fix_limit_".$key};
                $maglimit->heb = $request->{"high_emergency_limit_".$key};
                $maglimit->hrb = $request->{"high_reglement_limit_".$key};
                $maglimit->hwb = $request->{"high_working_limit_".$key};
                $maglimit->lwb = $request->{"low_working_limit_".$key};
                $maglimit->lrb = $request->{"low_reglement_limit_".$key};                
                $maglimit->leb = $request->{"low_emergency_limit_".$key};
                $maglimit->lfb = $request->{"low_fix_limit_".$key}; 
                $maglimit->save();
                $magcolumn->maglimits()->attach($maglimit);
                }
            }
            // Check if 'memory' exists and is an array, then attach if it has any elements
            if (isset($request->memory) && is_array($request->memory) && count($request->memory) > 0) {
                $magtable->magmems()->attach($request->memory);
            }

            // Предполагается, что $request->memory_name и $request->memory_description - это массивы
            $memoryNames = $request->memory_name; // Получаем массив имен из запроса
            $memoryDescriptions = $request->memory_description; // Получаем массив описаний из запроса

            // Проверяем, что количество имен и описаний совпадает
            for ($key = 0; $key < count($memoryNames); $key++) {
                // Проверяем, что значения имени и описания не пусты
                if (!empty($memoryNames[$key]) && !empty($memoryDescriptions[$key])) {
                    // Создаем новый объект модели Magmem
                    $newMem = new Magmem();

                    // Присваиваем значения свойствам объекта
                    $newMem->name = $memoryNames[$key];
                    $newMem->description = $memoryDescriptions[$key];

                    // Сохраняем объект в базу данных
                    $newMem->save();

                    // Привязываем новый объект к магической таблице
                    $magtable->magmems()->attach($newMem->id , ['number' => $key]);
                }
            }
            


            
            // redirect to mag.index
            return redirect('/mag')->with('success', 'Magazine store!');

    }

    public  $types_models = [
        0 => 'Magdatatext',
        1 => 'Magdatastr',
        2 => 'Magdataint',
        3 => 'Magdatafloat',
        4 => 'Magdatatime',
        5 => 'Magdatabool'
    ];

    public  $types_fun_column = [
        0 => 'magdatatexts',
        1 => 'magdatastrs',
        2 => 'magdataints',
        3 => 'magdatafloats',
        4 => 'magdatatimes',
        5 => 'magdatabools'
    ];
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $magtable = Magtable::find($id);
        $cols = $magtable->magcolumns;
        $rows = [];
    
        foreach ($cols as $col) {
            $type = $col->type;
            $modelClass = "App\\Models\\" . $this->types_models[$type];
            $fun = $this->types_fun_column[$type];
            $datas = $col->$fun;
            
            foreach ($datas as $data) {
                $createdAt = $data->created_at->format('Y-m-d H:i:s'); // Format date for key
                // Initialize array if not set
                if (!isset($rows[$createdAt])) {
                    $rows[$createdAt] = [];
                }
                // Add data to row
                $rows[$createdAt][] = [
                    'col' => $col->id,
                    'data' => $data->data, // Assume you need this value
                    'worker_tn' => $data->worker_tn,
                ];
            }
        }
    
        // Sort rows by date (keys)
        ksort($rows); // Sort keys (dates) in ascending order
        $rows = array_reverse($rows, true);
        return view('mag.show', compact('magtable', 'rows'));
    }
    // chart
    public function chart(string $id)
    {
        $magtable = Magtable::find($id);
        $cols = $magtable->magcolumns;
        $firstrows = [];
        $rows = [];
        foreach ($cols as $col) {
            $type = $col->type;
            if(!in_array($col->name,$firstrows) &&  ($type == 2 || $type == 3) ){
                $firstrows[] = $col->name;
            }
            $modelClass = "App\\Models\\" . $this->types_models[$type];
            $fun = $this->types_fun_column[$type];
            $datas = $col->$fun;
            
            foreach ($datas as $data) {
                $createdAt = $data->created_at->format('Y-m-d H:i:s'); // Format date for key
                // Initialize array if not set
                if (!isset($rows[$createdAt])) {
                    $rows[$createdAt] = [];
                }
                // Add data to row
                if($type == 2 || $type == 3){
                    $rows[$createdAt] = [                        
                        $data->data // Assume you need this value                        
                    ];
                }
            }
        }
        ksort($rows); // Sort keys (dates) in ascending order
        $rows = array_reverse($rows, true);
    $final=[];
        
        foreach ($rows as $key => $value) {
            // Преобразуем массив значений $value в один плоский массив
            $flattenedRow = array_merge([strtotime($key)], $value); // Добавляем ключ (дата) первым элементом
            $final[] = $flattenedRow; // Добавляем строку в $firstrows
        }
        $final =array_values($final);
        return view('mag.chart', compact('magtable', 'final', 'firstrows'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $magtable = Magtable::find($id);
        $dimensions = Helpers::dimensions();
        $divisions = Division::all();
        $mems = Magmem::all();
        return view('mag.edit', compact('magtable', 'dimensions', 'divisions', 'mems'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }
// storeRow
public function storeRow(Request $request)
{
    // проверить что все поля заполнены
    foreach ($request->column as $key => $value) {
        if (empty($value)) {
            return redirect('/mag/' . $request->magtable_id)->with('error', 'Fill all fields!');
        }
    }

    $mt = Magtable::find($request->magtable_id);

    $columns = $request->column;
    foreach ($columns as $key => $value) {
        $column = Magcolumn::find($key);
        $type = $column->type;
        $modelClass = "App\\Models\\" . $this->types_models[$type];
        $fun = $this->types_fun_column[$type];

        // Создание модели через полное имя класса
        $model = new $modelClass();
        $model->data = $value;
        $model->worker_tn = Auth::user()->tn;
        $model->save();

        // Присоединение данных к колонке
        $column->$fun()->attach($model);
    }

    return redirect('/mag/' . $request->magtable_id)->with('success', 'Row added!');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $table = Magtable::find($id);
        $table->divisions()->detach();
        $table->magcolumns()->detach();
        $table->magmems()->detach();
        // delete all related columns
        $columns = $table->magcolumns;
        foreach($columns as $column){
            $column->maglimits()->detach();
            $column->magdatabool()->detach();
            $column->magdatafloat()->detach();
            $column->magdataint()->detach();
            $column->magdatatext()->detach();
            $column->magdatastr()->detach();
            $column->magdatatime()->detach();

            $column->delete();
        }
        
        $table->delete();
        return redirect('/mag')->with('success', 'Magazine deleted!');
    }
}
