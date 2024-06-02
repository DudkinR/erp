<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Fact;
use App\Models\Image;

class FactsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $facts = Fact::all();
        return view('facts.index', compact('facts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('facts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            Log::info('Store method called');
            Log::info('Request data:', $request->all());

            $fact = new Fact();
            $fact->name = $request->name;
            $fact->description = $request->description;
            $fact->status = $request->status;
            $fact->save();
            
            Log::info('Fact saved successfully, ID: ' . $fact->id);

            if ($request->hasFile('image')) {
                Log::info('Image file found');
                $file = $request->file('image');
                $fileName = time() . '_' . $file->getClientOriginalName(); // Make filename unique
                $file->move(public_path('imagesFact'), $fileName);

                $img = new Image();
                $img->name = $fileName;
                $img->path = 'imagesFact/' . $fileName;
                $img->extension = $file->getClientOriginalExtension();
                $img->url = 'imagesFact/' . $fileName;
                $img->alt = $fileName;
                $img->title = $fileName;
                $img->description = $fileName;
                $img->save();

                $fact->images()->attach($img->id);
                Log::info('Image saved and attached to fact');
            } else {
                Log::info('No image file found');
            }

            return redirect()->route('facts.index')->with('success', 'Fact created successfully');
        } catch (\Exception $e) {
            Log::error('Error creating fact: ' . $e->getMessage());
            return redirect()->route('facts.index')->withErrors('Failed to create fact');
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $fact = Fact::find($id);
        return view('facts.show', compact('fact'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $fact = Fact::find($id);
        return view('facts.edit', compact('fact'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $fact = Fact::find($id);
    
            if (!$fact) {
                return redirect()->route('facts.index')->withErrors('Fact not found');
            }
    
            $fact->name = $request->name;
            $fact->description = $request->description;
            $fact->status = $request->status;
            $fact->save();
    
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $fileName = time() . '_' . $file->getClientOriginalName(); // Make filename unique
                $file->move(public_path('imagesFact'), $fileName);
                $img = new Image();
                $img->name = $fileName;
                $img->path = 'imagesFact/' . $fileName;
                $img->extension = $file->getClientOriginalExtension();
                $img->url = 'imagesFact/' . $fileName;
                $img->alt = $fileName;
                $img->title = $fileName;
                $img->description = $fileName;
                $img->save();    
                $fact->images()->attach($img->id);
            }    
            return redirect()->route('facts.index')->with('success', 'Fact updated successfully');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->route('facts.index')->withErrors('Failed to update fact');
        }
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $fact = Fact::find($id);
        $fact->delete();
        return redirect()->route('facts.index');
    }
}
