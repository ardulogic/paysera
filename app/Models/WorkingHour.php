<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkingHour extends Model
{
    public $timestamps = false;
    protected $fillable = ['day_of_week', 'start_time', 'end_time'];

    protected $primaryKey = 'day_of_week';
    protected $keyType = 'int';
    public $incrementing = false;

    /**
     * Gets working hours for a weekday
     * 0 - Sunday
     * 6 - Saturday
     *
     * @param int $weekday
     * @return WorkingHour|null
     */
    public static function getWorkingHoursForWeekday(int $weekday): ?WorkingHour
    {
        return WorkingHour::where('day_of_week', $weekday)->first();
    }

}
