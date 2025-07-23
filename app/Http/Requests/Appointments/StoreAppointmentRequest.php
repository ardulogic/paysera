<?php

namespace App\Http\Requests\Appointments;

use App\Models\Appointment;
use App\Models\WorkingHour;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class StoreAppointmentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'start_at' => [
                'required',
                'date',
                'after_or_equal:now',
            ],
            'end_at' => [
                'required',
                'date',
                'after:start_at',
            ],
            'email' => 'required|email',
        ];
    }

    /**
     * This method is called automatically by Laravel
     * Since we're validating both start and end dates at once
     * this is a better approach
     *
     * @param Validator $validator
     * @return void
     */
    public function withValidator(Validator $validator): void
    {
        // This method is called no matter the validation result
        $validator->after(function (Validator $validator) {
            try {
                // Make sure we validate previous rules
                $this->validated();
            } catch (ValidationException) {
                // Don't proceed if basic rules didn't pass
                return;
            }

            // These rules combine few fields, so we dont have a single-field validator
            $startDate = $this->input('start_at');
            $endDate = $this->input('end_at');

            if (!$this->isWithinWorkingHours($startDate)) {
                $validator->errors()->add('start_at', 'We are closed at that time!');
                return;
            }

            if (!$this->isWithinWorkingHours($endDate)) {
                $validator->errors()->add('end_at', 'We are closing before your appointment ends.');
                return;
            }

            if (!$this->isWindowAvailable($startDate, $endDate)) {
                $validator->errors()->add('start_at', 'This appointment overlaps with an existing booking.');
            }
        });
    }

    private function isWithinWorkingHours(string $date): bool
    {
        try {
            $datetime = Carbon::parse($date);
        } catch (\Exception $e) {
            return false;
        }

        $dayOfWeek = $datetime->dayOfWeek;
        $workingHour = WorkingHour::getWorkingHoursForWeekday($dayOfWeek);

        if (!$workingHour) {
            return false;
        }

        $workStart = Carbon::createFromFormat('H:i', $workingHour->start_time)->setDateFrom($datetime);
        $workEnd   = Carbon::createFromFormat('H:i', $workingHour->end_time)->setDateFrom($datetime);

        return $datetime->between($workStart, $workEnd);
    }

    private function isWindowAvailable(string $start, string $end): bool
    {
        try {
            $start = Carbon::parse($start);
            $end = Carbon::parse($end);
        } catch (\Exception $e) {
            return false;
        }

        return !Appointment::where(function ($query) use ($start, $end) {
            $query->where('start_at', '<', $end)
                ->where('end_at', '>', $start);
        })->exists();
    }

}
