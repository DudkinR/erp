<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocNpp extends Model
{
    protected $table = 'docnpp'; 
     protected $fillable = [
        'doc',
        'document_type',
        'code',
        'organization',
        'inventory_number',
        'summary',
        'approval_date',
        'effective_date',
        'expiration_date',
        'distribution',
        'replaces',
        'replaced_by',
        'change_number',
        'page_count',
        'note',
        'registration_place',
        'registration_date',
        'is_canceled',
        'cancellation_date',
        'implemented',
        'author',
        'approved_by',
        'project',
    ];

    protected $casts = [
        'approval_date' => 'date',
        'effective_date' => 'date',
        'expiration_date' => 'date',
        'registration_date' => 'date',
        'cancellation_date' => 'date',
        'is_canceled' => 'boolean',
        'implemented' => 'boolean',
    ];
}

