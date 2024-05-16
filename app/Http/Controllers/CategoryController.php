<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Image;
use App\Models\Doc;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;


class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $categories = Category::all();
        return view('cats.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('cats.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $category = new Category();
        //['name', 'slug', 'description', 'image', 'parent_id'];
        $category->name = $request->name;
        if($request->slug == null)
            $category->slug = $this->unicslug( $request->name);
        else
            $category->slug = $this->unicslug($request->slug);
        $category->description = $request->description;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $file->move(public_path() . '/imagesCat/', $file->getClientOriginalName());
            $category->image = $file->getClientOriginalName();
            $img = new Image();
            $img->name = $file->getClientOriginalName();
            $img->path = public_path() . '/imagesCat/'. $file->getClientOriginalName();
            $img->extension = $file->getClientOriginalExtension();
         //   $img->size = $file->getSize();
        //    $img->mime_type = $file->getMimeType();
            $img->url = public_path() . '/imagesCat/' . $file->getClientOriginalName();
            $img->alt = $file->getClientOriginalName();
            $img->title = $file->getClientOriginalName();
            $img->description = $file->getClientOriginalName();
            $img->save();

        }
        if($request->parent_id == null)
            $category->parent_id = 0;
        else
            $category->parent_id = $request->parent_id;
        $category->save();
        $category->images()->attach($img->id);
        return redirect()->route('cats.index');
    }
    public function unicslug($slug)
    {
        $category = Category::where('slug', $slug)->first();
        if($category == null)
            return $slug;
        else
        $slug = $slug . '-' . rand(1, 1000);
        return $this->unicslug($slug);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $category = Category::find($id);
        return view('cats.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $category = Category::find($id);
        return view('cats.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $category = Category::find($id);
        $category->name = $request->name;
        $category->slug = $request->slug;
        $category->description = $request->description;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $file->move(public_path() . '/imagesCat/', $file->getClientOriginalName());
            $category->image = $file->getClientOriginalName();
            $img = new Image();
            $img->name = $file->getClientOriginalName();
            $img->path = public_path() . '/imagesCat/'. $file->getClientOriginalName();
            $img->extension = $file->getClientOriginalExtension();
          //  $img->size = $file->getSize();
          //  $img->mime_type = $file->getMimeType();
            $img->url = public_path() . '/imagesCat/' . $file->getClientOriginalName();
            $img->alt = $file->getClientOriginalName();
            $img->title = $file->getClientOriginalName();
            $img->description = $file->getClientOriginalName();
            $img->save();
        }
        $category->parent_id = $request->parent_id;
        $category->save(); 
        $category->images()->attach($img->id);
        return redirect()->route('cats.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $category = Category::find($id);
        $category->delete();
        return redirect()->route('cats.index');
    }
}
