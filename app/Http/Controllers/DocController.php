<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doc;
use App\Models\Project;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use App\Models\Category;
use App\Helpers\StringHelpers as StringHelpers;

class DocController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $docs = Doc::orderBy('id', 'desc')->get();

        return view('docs.index', compact('docs'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('docs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Логирование запроса для отладки
            Log::info('Store method called');
            Log::info('Request data:', $request->all());
    
            // Поиск существующего документа
            $doc = Doc::where('name', $request->name)
                ->where('slug', $request->slug)
                ->where('category_id', $request->category_id)
                ->where('status', $request->status)
                ->first();
    
            if (!$doc) {
                $doc = new Doc();
            }
    
            $request->slug = StringHelpers::slugifyNoSpace($request->slug);
            $doc->name = $request->name;
    
            if ($request->hasFile('file')) {
                $path = $this->category_path($request->category_id, '');
                $file = $request->file('file');
                $filename = $request->slug . '.' . $file->getClientOriginalExtension();
    
                // Сохранение файла
                $file->storeAs($path, $filename);
    
                // Проверка и удаление старого файла, если путь изменился
                if ($doc->path !== $path . '/' . $filename) {
                    $old_path = $doc->path;
                    $doc->path = $path . '/' . $filename;
                    if ($old_path) {
                        Storage::delete($old_path);
                    }
                }
                $doc->path = $path . '/' . $filename;
            }
    
            $doc->category_id = $request->category_id;
            $doc->slug = $request->slug;
            $doc->lng = $request->lng ?? 'uk';
            $doc->link = $request->link;
            $doc->description = $request->description;
            $doc->revision_date = $request->revision_date;
            $doc->publication_date = $request->publication_date;
            $doc->creation_date = $request->creation_date;
            $doc->deletion_date = $request->deletion_date;
            $doc->last_change_date = $request->last_change_date;
            $doc->last_view_date = $request->last_view_date;
            $doc->status = $request->status;
            $doc->save();
    
            // Привязка категорий
            $doc->categories()->detach();
            $doc->categories()->attach($request->category_id);
    
            // Привязка связанных документов
            if ($request->document_releted) {
                $doc->relatedDocs()->detach();
                $doc->relatedDocs()->attach($request->document_releted);
            }
    
            return redirect()->route('docs.index')->with('success', 'Document saved successfully');
        } catch (\Exception $e) {
            // Логирование ошибки
            Log::error('Error storing document: ' . $e->getMessage());
            return redirect()->route('docs.index')->withErrors('Failed to store document');
        }
    }
    
    // apidocs 
    public function apistoredocs(Request $request)
    {
       // return 55555;
        $doc = new Doc();
        $doc->name = $request->name;
        $doc->status= $request->status;
        // save
        $doc->save();
        return response()->json($doc);

    }
    public function category_path($category_id, $path = '')
    {
        $category = Category::find($category_id);
        if (!$category) {
            return rtrim($path, '/'); // Обрезаем лишние слеши справа
        } else {
            if (empty($path)) {
                $path = $category->slug; // Если path пуст, просто используем slug
            } else {
                $path = $category->slug . '/' . $path; // Иначе добавляем slug и path через слеш
            }
            return $this->category_path($category->parent_id, $path);
        }
    }
    public function test(Request $request)
    { 
        $category_id = $request->id;
        $cat = Category::find($category_id);
        return $cat->docs;

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $doc = Doc::find($id);
        return view('docs.show', compact('doc'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $doc = Doc::find($id);
      //  return $doc;
        return view('docs.edit', compact('doc'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $doc = Doc::find($id);
        $doc->name = $request->name;
        $request->slug = StringHelpers::slugifyNoSpace($request->slug);
        if($request->hasFile('file')){
            $path = $this->category_path($request->category_id, '');
            $file = $request->file('file');
            $filename = $request->slug.'.'.$file->getClientOriginalExtension();
            // delete old file
            $old_path = $doc->path;
            if($old_path){
                Storage::delete($old_path);
            }
            // download file
            $file->storeAs($path, $filename);
            $doc->path = $path.'/'.$filename;
        }else{
            $path = $this->category_path($request->category_id, '');
            // проверяем на null значение переменной $path
            if($path !== null){
                // имя файла с расширением берем из старого пути
                $filename =  pathinfo($doc->path, PATHINFO_BASENAME);
                if($doc->path !== $path.'/'.$filename)
                {
                    // копируем старый файл в новый путь и удаляем старый файл
                    //  $old_path  string
                    $old_path = $doc->path;
                    // проверяем существование файла
                    if($old_path !== NULL && Storage::exists($old_path) ){               
                        Storage::copy($old_path, $path.'/'.$filename);
                        if($old_path){
                            Storage::delete($old_path);
                        }
                    }
                    $doc->path = $path.'/'.$filename;
                }
            }
        }
        

        $doc->slug = $request->slug;
        $doc->category_id = $request->category_id;
        if($request->link){
            $doc->link = $request->link;
        }
        $doc->description = $request->description;
        if($request->revision_date){
            $doc->revision_date = $request->revision_date;
        }
        if($request->publication_date){
            $doc->publication_date = $request->publication_date;
        }
        if($request->creation_date){
            $doc->creation_date = $request->creation_date;
        }
        if($request->deletion_date){
            $doc->deletion_date = $request->deletion_date;
        }
        if($request->last_change_date){
            $doc->last_change_date = $request->last_change_date;
        }
        if($request->last_view_date){
            $doc->last_view_date = $request->last_view_date;
        }
        $doc->category_id = $request ->category_id;
        $doc->status = $request->status;
        $doc->save();
        //category belongto docs
        // delete old category
        $doc->categories()->detach();
        $doc->categories()->attach($request->category_id);
        // document_releted[] attach to  relatedDocs
        if($request->document_releted){
            $doc->relatedDocs()->detach();
            $doc->relatedDocs()->attach($request->document_releted);
        }
        return redirect()->route('docs.show', $id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $doc = Doc::find($id);
        $doc->delete();
        return redirect()->route('docs.index');
    }
    //import
    public function import()
    {
      return view('docs.import');
    }
    // importData
    public function importData(Request $request)
    {
        $folder = public_path('documents');
        $files = $this->getAllFiles($folder);
        $rootCategoryId = 1; // Убедитесь, что здесь правильный ID для корневой категории
        return $this->readDoc($files, $rootCategoryId);
    }
    
    public function getAllFiles($folder)
    {
        $files = [];
        $items = scandir($folder);
        foreach ($items as $item) {
            if ($item != '.' && $item != '..') {
                $path = $folder . DIRECTORY_SEPARATOR . $item;
                if (is_dir($path)) {
                    $files = array_merge($files, $this->getAllFiles($path));
                } elseif (is_file($path)) {
                    $files[] = $path;
                }
            }
        }
        return $files;
    }
    
    public function readDoc($files, $parent_category_id)
    {
        $docs = [];
        foreach ($files as $file) {
            $relativePath = str_replace(public_path() . DIRECTORY_SEPARATOR, '', $file);  // Получаем относительный путь
            $parts = explode(DIRECTORY_SEPARATOR, $relativePath);  // Разделяем путь на части
            $fileName = array_pop($parts); // Удаляем имя файла, получаем только путь каталогов
    
            $currentParentId = $parent_category_id;
            foreach ($parts as $part) {
                $currentParentId = $this->newCategory($part, $currentParentId);  // Пошагово создаем или получаем категории
            }
    
            $docs[] = $this->newDoc($fileName, $relativePath, $currentParentId);
        }
        return $docs;
    }
    
    public function newCategory($name, $parent_id)
    {
        $category = Category::firstOrCreate(
            ['name' => $name, 'parent_id' => $parent_id],
            ['slug' => $name, 'description' => $name]
        );
        return $category->id;
    }
    
     // new doc
    public function newDoc($name, $path, $category_id)
    {
        $doc = Doc::where('name', $name)->first();
        if(!$doc){
            $doc = new Doc();
        }          
        $doc->name = $name;
        $doc->path = $path;
        $doc->slug = $name;
        $doc->description = $name;
        $doc->revision_date = NULL;
        $doc->publication_date = NULL;
        $doc->creation_date = NULL;
        $doc->deletion_date = NULL;
        $doc->last_change_date = NULL;
        $doc->last_view_date = NULL;
        $doc->category_id = $category_id;
        $doc->save();
        return $doc;
    }
    // addDocs
    public function addDocs(Request $request)
    {     
        $project = Project::find($request->project_id);
        $docs = Doc::orderBy('id', 'desc')->get();

        return view('docs.addDocs', compact('project', 'docs'));
    }
    // store_to_project
    public function store_to_project(Request $request)
    {
        if(!$request->doc_id){
            return response()->json('Error: docs', 400);
        }
        if(!$request->project_id){
            return response()->json('Error:project', 400);
        }
        $project = Project::find($request->project_id);
        //$project->docs()->detach();
        $project->docs()->attach($request->doc_id);
        return  response()->json($request->doc_id);
    }
       

}
