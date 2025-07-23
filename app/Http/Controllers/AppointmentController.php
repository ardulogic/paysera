<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Abstracts\Controller;
use App\Http\Requests\Appointments\ListAppointmentSlotsRequest;
use App\Http\Requests\Appointments\ListAppointmentsRequest;
use App\Http\Requests\Appointments\StoreAppointmentRequest;
use App\Models\Appointment;
use App\Models\WorkingHour;
use Illuminate\Support\Carbon;

class AppointmentController extends Controller
{

    public function store(StoreAppointmentRequest $request)
    {
        $validated = $request->validated();

        $appointment = Appointment::create($validated);

        return response()->json([
            'message' => 'Appointment created!',
            'appointment' => $appointment,
        ], 201);
    }

    public function slots(ListAppointmentSlotsRequest $request)
    {
        $date = Carbon::parse($request->input('date'))->startOfDay();
        $slots = Appointment::availableSlotsForDate($date);

        return response()->json(['slots' => $slots]);
    }

}
