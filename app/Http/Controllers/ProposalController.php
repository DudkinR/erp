<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proposal;

class ProposalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $proposals = Proposal::all();
        return view('proposals.index', compact('proposals'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('proposals.create');    
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([

            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
  
        ]);

        $proposal = new Proposal();
        // $personal->divisions->pluck('id')->contains($division->id)
        $proposal->division_id = auth()->user()->personal->divisions->first()->id ?? null   ;
        
        $proposal->title = $request->input('title');
        $proposal->description = $request->input('description');
        $proposal->proposal = null;
        $proposal->status = 'на розгляді';
        $proposal->save();
        return redirect()->route('proposals.index')->with('success', 'Proposal created successfully.');
    }

    /**
     * Display the specified resource.
     *  
        | Поле                    | Тип                                                             | Опис                   |
        | ----------------------- | --------------------------------------------------------------- | ---------------------- |
        | id                      | bigint                                                          | ID пропозиції          |
        | division_id           | bigint                                                          | Хто подав              |
        | title                   | string                                                          | Назва пропозиції       |
        | description             | text                                                            | Суть пропозиції        |
        | proposal                | text                                                            | Пропозиція             |
        | status                  | enum(‘на розгляді’, ‘схвалено’, ‘відхилено’, ‘у доопрацюванні’) | Статус                 |
        | decision                | text                                                            | Рішення групи          |
        | created_at / updated_at | timestamps                                                      | Дата створення / зміни |
        
     */
    public function show(string $id)
    {
        //
        $proposal = Proposal::findOrFail($id);
        return view('proposals.show', compact('proposal'));
    }
    // consideration
    public function consider(string $id)
    {
        //

        $proposal = Proposal::findOrFail($id);
        $actions = $proposal->actions()->get();
        return view('proposals.consideration', compact('proposal', 'actions'));
    }
    //considerations.store
    public function storeConsideration(Request $request)
    {
        $request->validate([
            'proposal_id' => 'required|exists:proposals,id',
            'content' => 'required|string',
            'responsible' => 'required|string',
            'deadline' => 'required|date',
        ]);

        $proposal = Proposal::findOrFail($request->input('proposal_id'));
        // Create new action
        $action = new \App\Models\Action();
        $action->proposal_id = $proposal->id;
        $action->title = $request->input('content');
        $action->responsible = $request->input('responsible');
        $action->deadline = $request->input('deadline');
        $action->status = 'в процесі';
        $action->save();

        return redirect()->route('proposals.consider', $proposal->id)->with('success', 'Consideration added successfully.');
    }
    // editConsideration
    public function editConsideration(string $id)
    {
        //
        $consideration = \App\Models\Action::findOrFail($id);
        $efficiency_criteria = $consideration->efficiency_criteria()->get();
        return view('proposals.edit_consideration', compact('consideration', 'efficiency_criteria'));
    }
    // updateConsideration
    public function updateConsideration(Request $request, string $id)
    {
        $action = \App\Models\Action::findOrFail($id);
        $action->title = $request->input('content');
        $action->responsible = $request->input('responsible');
        $action->deadline = $request->input('deadline');
        $action->status = $request->input('status');
        $action->result_description = $request->input('result_description');
        $action->save();
        return redirect()->route('proposals.consider', $action->proposal_id)->with('success', 'Consideration updated successfully.');
    }
    // destroyConsideration
    public function destroyConsideration(string $id)
    {
        //
        $action = \App\Models\Action::findOrFail($id);
        $proposal_id = $action->proposal_id;
        $action->delete();
        return redirect()->route('proposals.consider', $proposal_id)->with('success', 'Consideration deleted successfully.');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $proposal = Proposal::findOrFail($id);
        return view('proposals.edit', compact('proposal'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $proposal = Proposal::findOrFail($id);
      //       $table->enum('status', ['на розгляді', 'схвалено', 'відхилено', 'у доопрацюванні'])->default('на розгляді');
        
        $proposal->title = $request->input('title');
        $proposal->description = $request->input('description');
        $proposal->proposal = $request->input('proposal');
        $proposal->status = $request->input('status') ? : 'на розгляді';
        $proposal->decision = $request->input('decision')? : null; 
        $proposal->save();
        return redirect()->route('proposals.index')->with('success', 'Proposal updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $proposal = Proposal::findOrFail($id);
        $proposal->delete();
        return redirect()->route('proposals.index')->with('success', 'Proposal deleted successfully.');
    }

    //storeEfficiencyCriterion
    public function storeEfficiencyCriterion(Request $request)
    {
        $request->validate([
            'action_id' => 'required|exists:actions,id',
            'proposal_id' => 'required|exists:proposals,id',
            'name' => 'required|string|max:255',
            'weight' => 'required|numeric',
            'unit' => 'required|string|max:50',
        ]);

        $efficiencyCriterion = new \App\Models\EfficiencyCriterion();
        $efficiencyCriterion->action_id = $request->input('action_id');
        $efficiencyCriterion->proposal_id = $request->input('proposal_id');
        $efficiencyCriterion->name = $request->input('name');
        $efficiencyCriterion->weight = $request->input('weight');
        $efficiencyCriterion->unit = $request->input('unit');
        $efficiencyCriterion->save();

        return redirect()->route('considerations.edit', $request->input('action_id'))->with('success', 'Efficiency Criterion added successfully.');
    }
    //editEfficiencyCriterion
    public function editEfficiencyCriterion(string $id)
    {
        //
        
        $EfficiencyCriterion= \App\Models\EfficiencyCriterion::findOrFail($id);
        $consideration = $EfficiencyCriterion->action;
        $efficiency_criteria = $consideration->efficiency_criteria()->get();
        return view('proposals.edit_consideration', compact('consideration', 'efficiency_criteria'));
    }
}

