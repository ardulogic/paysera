<?php

namespace App\Http\Requests\Appointments;

use Illuminate\Foundation\Http\FormRequest;

class ListAppointmentSlotsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'date' => [
                'required',
                'date_format:Y-m-d',
                'after_or_equal:today',
            ],
        ];
    }
}
