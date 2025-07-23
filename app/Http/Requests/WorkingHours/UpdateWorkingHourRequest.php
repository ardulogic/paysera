<?php

namespace App\Http\Requests\WorkingHours;


use Illuminate\Foundation\Http\FormRequest;

class UpdateWorkingHourRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'day_of_week' => 'required|integer|between:0,6',

            // Only include the flag when you want to toggle it
            'closed'      => 'sometimes|boolean',

            'start_time'  => [
                'required_unless:closed,true',
                'date_format:H:i',      // 09:00
            ],
            'end_time'    => [
                'required_unless:closed,true',
                'date_format:H:i',
                'after:start_time',
            ],
        ];
    }
}
