<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamTaskReport extends Model
{
    
    /*
    `team_task_reports`
      `id`, `task_id`, `user_id`, `report`, `attachment`, `created_at`, `updated_at`*/
    protected $table = 'team_task_reports';
    protected $fillable = [
        'task_id', 'user_id', 'report', 'attachment'
    ];
    public function task()
    {
        return $this->belongsTo(TeamTask::class, 'task_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
