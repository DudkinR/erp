<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrganomicController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
  
        $personals = [
            [
                'id' => 1,
                'room' => 'D15',
                'group' => 'A',
                'department' => 'IT',
                'phone' => '62611',                
            ],
            [
                'id' => 2,
                'room' => 'A15',
                'group' => 'A',
                'department' => 'IT',
                'phone' => '62611',
            ],
            [
                'id' => 3,
                'room' => 'A15',
                'group' => 'A',
                'department' => 'IT',
                'phone' => '62611',
            ],
            [
                'id' => 4,
                'room' => 'B15',
                'group' => 'B',
                'department' => 'IT',
                'phone' => '12345',
            ],
            [
                'id' => 5,
                'room' => 'A15',
                'group' => 'A',
                'department' => 'IT',
                'phone' => '62611',
            ],
            [
                'id' => 6,
                'room' => 'B15',
                'group' => 'B',
                'department' => 'IT',
                'phone' => '12345',
            ],
            [
                'id' => 7,
                'room' => 'C15',
                'group' => 'C',
                'department' => 'IT',
                'phone' => '12345',
            ],
            [
                'id' => 8,
                'room' => 'D15',
                'group' => 'D',
                'department' => 'IT',
                'phone' => '12345',
            ]
        ];
        
        $rooms = [
            [
                'id' => 1,
                'name' => 'A15',
                'area' => 9,
                'building' => 'A',
                'floor' => 1,
                'air_conditioned' => 1,
            ],
            [
                'id' => 2,
                'name' => 'B15',
                'area' => 10.3,
                'building' => 'B',
                'floor' => 2,
                'air_conditioned' => 0,
            ],
            [
                'id' => 3,
                'name' => 'C15',
                'area' => 8.5,
                'building' => 'C',
                'floor' => 3,
                'air_conditioned' => 1,
            ],
            [
                'id' => 4,
                'name' => 'D15',
                'area' => 9.5,
                'building' => 'D',
                'floor' => 4,
                'air_conditioned' => 0,
    ],
        ];

        $equipments = [
            [
                'id' => 1,
                'name' => 'SA54D01',
                'type' => 'pump',
                'building' => 'A',
                'floor' => 1,
                'air_conditioned' => 1,
            ],
            [
                'id' => 2,
                'name' => 'B15',
                'area' => 10.3,
                'building' => 'B',
                'floor' => 2,
                'air_conditioned' => 0,
            ],
            [
                'id' => 3,
                'name' => 'C15',
                'area' => 8.5,
                'building' => 'C',
                'floor' => 3,
                'air_conditioned' => 1,
            ],
            [
                'id' => 4,
                'name' => 'D15',
                'area' => 9.5,
                'building' => 'D',
                'floor' => 4,
                'air_conditioned' => 0,
            ]

    ];
  
      
        // organomics
        return view('organomics.index', compact('personals', 'rooms', 'equipments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('organomics.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        
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
}
