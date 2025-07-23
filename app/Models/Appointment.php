<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
class Appointment extends Model
{
    protected $fillable = ['start_at', 'end_at', 'email'];

    protected $casts = [
        'start_at' => 'immutable_datetime',
        'end_at' => 'immutable_datetime',
    ];

    public static function availableSlotsForDate(Carbon $date, int $slotLengthMinutes = 60): array
    {
        $dayOfWeek = $date->dayOfWeek; // 0 (Sun) - 6 (Sat)

        $workingHour = WorkingHour::where('day_of_week', $dayOfWeek)->first();
        if (!$workingHour) {
            return [];
        }

        $workStart = Carbon::parse($date->format('Y-m-d') . ' ' . $workingHour->start_time);
        $workEnd = Carbon::parse($date->format('Y-m-d') . ' ' . $workingHour->end_time);

        $appointments = self::whereDate('start_at', $date)->get();
        $slots = [];

        for ($slot = $workStart->copy(); $slot->lt($workEnd); $slot->addMinutes($slotLengthMinutes)) {
            $slotEnd = $slot->copy()->addMinutes($slotLengthMinutes);

            if ($slot->lte(now())) {
                continue;
            }

            $overlap = $appointments->first(function ($appt) use ($slot, $slotEnd) {
                return $appt->start_at < $slotEnd && $appt->end_at > $slot;
            });

            if (!$overlap) {
                $slots[] = [
                    'start_at' => $slot->toIso8601String(),
                    'end_at' => $slotEnd->toIso8601String(),
                ];
            }
        }

        return $slots;
    }
}
