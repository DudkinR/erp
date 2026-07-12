<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConstructionJob extends Model
{
    protected $table = 'construction_jobs';

    protected $fillable = [
        'basis',
        'build',
        'room',
        'location_axes',
        'element',
        'work_type',
        'unit',
        'q',
        'whh',
        'real_whh',
        'type',
        'year',
        // Планові місяці
        'jan','feb','mar','apr','may','jun',
        'jul','aug','sep','oct','nov','dec',
        // Фактичні місяці
        'real_jan','real_feb','real_mar','real_apr','real_may','real_jun',
        'real_jul','real_aug','real_sep','real_oct','real_nov','real_dec',
        'tmc',
        'inv_no',
        'own_division',
        'note_locale',
        'note',
        'grant'
    ];

    protected $casts = [
        'q'        => 'float',
        'whh'      => 'float',
        'real_whh' => 'float',
        'year'     => 'integer',
        // автоматичне приведення до float для всіх місяців
        'jan' => 'float','feb' => 'float','mar' => 'float','apr' => 'float','may' => 'float','jun' => 'float',
        'jul' => 'float','aug' => 'float','sep' => 'float','oct' => 'float','nov' => 'float','dec' => 'float',
        'real_jan' => 'float','real_feb' => 'float','real_mar' => 'float','real_apr' => 'float','real_may' => 'float','real_jun' => 'float',
        'real_jul' => 'float','real_aug' => 'float','real_sep' => 'float','real_oct' => 'float','real_nov' => 'float','real_dec' => 'float',
    ];

    /** Планові місяці */
    public function plannedMonths(): array
    {
        return [
            'jan' => $this->jan ?? 0,
            'feb' => $this->feb ?? 0,
            'mar' => $this->mar ?? 0,
            'apr' => $this->apr ?? 0,
            'may' => $this->may ?? 0,
            'jun' => $this->jun ?? 0,
            'jul' => $this->jul ?? 0,
            'aug' => $this->aug ?? 0,
            'sep' => $this->sep ?? 0,
            'oct' => $this->oct ?? 0,
            'nov' => $this->nov ?? 0,
            'dec' => $this->dec ?? 0,
        ];
    }

    /** Фактичні місяці */
    public function realMonths(): array
    {
        return [
            'jan' => $this->real_jan ?? 0,
            'feb' => $this->real_feb ?? 0,
            'mar' => $this->real_mar ?? 0,
            'apr' => $this->real_apr ?? 0,
            'may' => $this->real_may ?? 0,
            'jun' => $this->real_jun ?? 0,
            'jul' => $this->real_jul ?? 0,
            'aug' => $this->real_aug ?? 0,
            'sep' => $this->real_sep ?? 0,
            'oct' => $this->real_oct ?? 0,
            'nov' => $this->real_nov ?? 0,
            'dec' => $this->real_dec ?? 0,
        ];
    }

    /** Сума планових місяців */
    public function plannedSum(): float
    {
        return array_sum($this->plannedMonths());
    }

    /** Сума фактичних місяців */
    public function realSum(): float
    {
        return array_sum($this->realMonths());
    }

    /** Відхилення план/факт */
    public function deviation(): float
    {
        return $this->realSum() - $this->plannedSum();
    }

    /** Чи співпадає плановий WHH із сумою місяців */
    public function isWhhValid(): bool
    {
        return abs($this->whh - $this->plannedSum()) < 0.0001;
    }

    /** Чи співпадає фактичний WHH із сумою фактичних місяців */
    public function isRealWhhValid(): bool
    {
        return abs($this->real_whh - $this->realSum()) < 0.0001;
    }
}
