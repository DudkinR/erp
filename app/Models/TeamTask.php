<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamTask extends Model
{
    //
    protected $table = 'team_task';

    protected $fillable = [
        'team_id', 'title', 'description', 'creator_id', 'assignee_id',
        'type', 'recurrence', 'start_at', 'due_at', 'next_run_at', 'status',
         'parent_task_id'

    ];  
    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }
}
