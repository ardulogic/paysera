<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Abstracts\Controller;
use App\Http\Requests\WorkingHours\UpdateWorkingHourRequest;
use App\Models\WorkingHour;
use Illuminate\Http\JsonResponse;

class WorkingHoursController extends Controller
{
    public function update(UpdateWorkingHourRequest $request): JsonResponse
    {
        $data = $request->validated();

        if ($data['closed'] ?? false) {
            WorkingHour::where('day_of_week', $data['day_of_week'])->delete();

            return response()->json([
                'message' => 'Day marked as closed',
                'day_of_week' => $data['day_of_week'],
            ], 200);
        }

        $workingHour = WorkingHour::updateOrCreate(
            ['day_of_week' => $data['day_of_week']],
            [
                'start_time' => $data['start_time'],
                'end_time'   => $data['end_time'],
            ]
        );

        return response()->json([
            'message' => 'Working hours updated!',
            'working_hour' => $workingHour,
        ], 200);
    }


    public function list(): JsonResponse
    {
        $workingHours = WorkingHour::all()->keyBy('day_of_week');

        return response()->json([
            'working_hours' => $workingHours,
        ], 200);
    }
}
