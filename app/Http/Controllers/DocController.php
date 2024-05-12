<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doc;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Models\Category;

class DocController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $docs = Doc::all();
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
        //
        $doc = new Doc();
        $doc->name = $request->name;
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
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
    // read file
       

}
