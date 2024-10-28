<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calling extends Model
{
    use HasFactory;

    // Table name
    protected $table = 'calling';

    // Fillable columns
    protected $fillable = [
        'description',
        'type_id',
        'start_time',
        'personal_start_id',
        'arrival_time',
        'personal_arrival_id',
        'end_time',
        'personal_end_id',
    ];

    // Relationship to workers via a pivot table 'callings_workers'
    public function workers()
    {
        return $this->belongsToMany(Personal::class, 'callings_workers')
                    ->withPivot('worker_type_id', 'payment_type_id', 'comment', 'start_time', 'end_time');
    }

    // Relationship to checkins
    public function checkins()
    {
        return $this->belongsToMany(Personal::class, 'callings_checkins', 'calling_id', 'personal_id')
                    ->withPivot('checkin_type_id','type','comment')
                    ->withTimestamps(); // Если хотите учитывать поля created_at и updated_at
    }

     public function checkBoss($name='Nachal`nik'){
        $checkin_type_id = Type::where('slug', $name)->first()->id;
        return $this->checkins()
        ->wherePivot('checkin_type_id', $checkin_type_id);
     }

    
    //  Сформированные карточки с одним рабочим, но без заполненных полей прибытия, старта, работы, окончания
        public function scopeFormedWithWorkerNoTimes($query)
    {
        return $query->whereNull('arrival_time')
                    ->whereNull('start_time')
                    ->whereNull('work_time')
                    ->whereNull('end_time')
                    ->whereHas('workers');
    }
    // no any callings_checkins
    public function scopeNoCheckins($query)
    {
        return $query->doesntHave('checkins');
    }

    // Карточки с рабочими, но с заполненным временем прибытия:
    public function scopeWithArrivalTime($query)
    {
        return $query->whereNotNull('arrival_time')
                    ->whereHas('workers');
    }
    //Карточки с рабочими, но с заполненным временем начала работы:
    public function scopeWithStartTime($query)
    {
        return $query->whereNotNull('start_time')
                    ->whereHas('workers');
    }
    //Карточки с временем начала и окончания работы
    public function scopeWithStartAndEndTime($query)
    {
        return $query->whereNotNull('start_time')
                    ->whereNotNull('end_time');
    }
    // Карточки полностью заполненные, но без чек-инов
    public function scopeFullyFilledWithoutCheckins($query)
    {
        return $query->whereNotNull('description')
                    ->whereNotNull('arrival_time')
                    ->whereNotNull('start_time')
                    ->whereNotNull('work_time')
                    ->whereNotNull('end_time')
                    ->doesntHave('checkins');
    }
    //Карточки полностью заполненные, но чек-ины не связаны с текущей записью
    public function scopeFullyFilledWithCheckinsNotLinked($query)
    {
        return $query->whereNotNull('description')
                    ->whereNotNull('arrival_time')
                    ->whereNotNull('start_time')
                    ->whereNotNull('work_time')
                    ->whereNotNull('end_time')
                    ->whereDoesntHave('checkins', function ($q) {
                        $q->whereColumn('callings.id', '=', 'callings_checkins.calling_id');
                    });
    }
    //Карточки полностью заполненные и чек-ины связаны с записью
    public function scopeFullyFilledWithLinkedCheckins($query)
    {
        return $query->whereNotNull('description')
                    ->whereNotNull('arrival_time')
                    ->whereNotNull('start_time')
                    ->whereNotNull('work_time')
                    ->whereNotNull('end_time')
                    ->whereHas('checkins', function ($q) {
                        $q->whereColumn('callings.id', '=', 'callings_checkins.calling_id');
                    });
    }
    //Фильтрация по времени (например, за последние сутки или неделю)  '7 days' - неделя, '24 hours' - сутки и т.д.
    public function scopeRecent($query, $timePeriod = '24 hours')
    {
        return $query->where('created_at', '>=', now()->sub($timePeriod));
    }

}
