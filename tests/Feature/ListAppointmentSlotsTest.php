<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\WorkingHour;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class ListAppointmentSlotsTest extends TestCase
{
    use RefreshDatabase;

    protected function getApiUrl(array $params = []): string
    {
        return route('appointment.slots', $params);
    }

    /** @test */
    public function it_returns_available_slots_for_a_future_date()
    {
        // Use a future weekday (e.g. tomorrow)
        $date = Carbon::now()->addDay()->startOfDay();
        $dayOfWeek = $date->dayOfWeek;

        // Set working hours for that weekday (e.g. 09:00 - 17:00)
        WorkingHour::create([
            'day_of_week' => $dayOfWeek,
            'start_time' => '09:00:00',
            'end_time'   => '17:00:00',
        ]);

        // Create a booked slot from 10:00 to 11:00
        Appointment::create([
            'start_at' => $date->copy()->setTime(10, 0),
            'end_at'   => $date->copy()->setTime(11, 0),
            'email'    => 'taken@example.com',
        ]);

        // Freeze current time just before the working day
        Carbon::setTestNow($date->copy()->setTime(8, 0));

        $response = $this->getJson($this->getApiUrl(['date' => $date->toDateString()]));

        $response->assertOk();

        $slots = $response->json('slots');

        // Make sure slots were returned
        $this->assertNotEmpty($slots);

        // Ensure 10:00 is excluded
        foreach ($slots as $slot) {
            $this->assertArrayHasKey('start_at', $slot);
            $this->assertArrayHasKey('end_at', $slot);

            $this->assertNotEquals(
                $date->copy()->setTime(10, 0)->toIso8601String(),
                $slot['start_at'],
                'Slot from 10:00 to 11:00 should be excluded due to conflict'
            );
        }
    }

    /** @test */
    public function it_returns_no_slots_when_fully_booked()
    {
        // Future date
        $date = Carbon::now()->addDay()->startOfDay();
        $dayOfWeek = $date->dayOfWeek;

        // Working hours: 09:00 to 11:00
        WorkingHour::create([
            'day_of_week' => $dayOfWeek,
            'start_time' => '09:00:00',
            'end_time'   => '11:00:00',
        ]);

        // Fully book from 09:00 to 11:00 with 1-hour slots
        Appointment::create([
            'start_at' => $date->copy()->setTime(9, 0),
            'end_at'   => $date->copy()->setTime(10, 0),
            'email'    => 'a@example.com',
        ]);

        Appointment::create([
            'start_at' => $date->copy()->setTime(10, 0),
            'end_at'   => $date->copy()->setTime(11, 0),
            'email'    => 'b@example.com',
        ]);

        // Freeze time before working hours
        Carbon::setTestNow($date->copy()->setTime(8, 0));

        $response = $this->getJson($this->getApiUrl(['date' => $date->toDateString()]));

        $response->assertOk();
        $response->assertJsonCount(0, 'slots'); // No available slots
    }

    /** @test */
    public function it_returns_only_middle_slot_when_edges_are_booked()
    {
        // Use a future weekday
        $date = Carbon::now()->addDay()->startOfDay();
        $dayOfWeek = $date->dayOfWeek;

        // Working hours: 09:00 to 12:00 (3 slots: 09–10, 10–11, 11–12)
        WorkingHour::create([
            'day_of_week' => $dayOfWeek,
            'start_time' => '09:00:00',
            'end_time'   => '12:00:00',
        ]);

        // Book first slot (09:00–10:00)
        Appointment::create([
            'start_at' => $date->copy()->setTime(9, 0),
            'end_at'   => $date->copy()->setTime(10, 0),
            'email'    => 'first@example.com',
        ]);

        // Book last slot (11:00–12:00)
        Appointment::create([
            'start_at' => $date->copy()->setTime(11, 0),
            'end_at'   => $date->copy()->setTime(12, 0),
            'email'    => 'last@example.com',
        ]);

        // Freeze time before working hours
        Carbon::setTestNow($date->copy()->setTime(8, 0));

        $response = $this->getJson($this->getApiUrl(['date' => $date->toDateString()]));

        $response->assertOk();
        $slots = $response->json('slots');

        // Only one available slot
        $this->assertCount(1, $slots);

        // That slot should be 10:00–11:00
        $this->assertEquals($date->copy()->setTime(10, 0)->toIso8601String(), $slots[0]['start_at']);
        $this->assertEquals($date->copy()->setTime(11, 0)->toIso8601String(), $slots[0]['end_at']);
    }

}

