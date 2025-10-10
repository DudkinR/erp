<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    
    protected $table = 'contracts';        
    protected $fillable = [
        'contract_number',
        'contract_date',
        'provider_id',
        'subject',
    ];

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }

}
