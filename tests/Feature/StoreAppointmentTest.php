<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\WorkingHour;
use Carbon\CarbonInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class StoreAppointmentTest extends TestCase
{
    use RefreshDatabase;

    protected string $requestDateFormat = 'Y-m-d\TH:i:sP';
    protected string $databaseDateFormat = 'Y-m-d H:i:s';

    protected function getApiUrl(): string
    {
        return route('appointment.store');
    }

    protected function setUp(): void
    {
        parent::setUp();

        // Add working hours for Monday (1)
        WorkingHour::create([
            'day_of_week' => 1,
            'start_time' => '09:00',
            'end_time' => '17:00',
        ]);
    }

    /** @test */
    public function it_accepts_booking_within_working_hours(): void
    {
        $monday = Carbon::now()->next(CarbonInterface::MONDAY)->setTime(10, 0);

        $response = $this->postJson($this->getApiUrl(), [
            'start_at' => $monday->format($this->requestDateFormat),
            'end_at' => $monday->copy()->addMinutes(30)->format($this->requestDateFormat),
            'email' => 'john@example.com',
        ]);

        $response->assertCreated();
    }

    /** @test */
    public function it_rejects_booking_outside_working_hours(): void
    {
        $monday = Carbon::now()->next(CarbonInterface::MONDAY)->setTime(20, 0); // 8pm

        $response = $this->postJson($this->getApiUrl(), [
            'start_at' => $monday->format($this->requestDateFormat),
            'end_at' => $monday->copy()->addMinutes(30)->format($this->requestDateFormat),
            'email' => 'late@example.com',
        ]);

        $response->assertStatus(422);
    }

    /** @test */
    public function it_rejects_appointments_that_overlap(): void
    {
        $start = Carbon::now()->next(CarbonInterface::MONDAY)->setTime(10, 0);
        $end = $start->copy()->addHour();

        Appointment::create([
            'start_at' => $start,
            'end_at' => $end,
            'email' => 'existing@example.com',
        ]);

        $overlapStart = $start->copy()->addMinutes(30); // 10:30
        $overlapEnd = $overlapStart->copy()->addHour(); // 11:30

        $response = $this->postJson($this->getApiUrl(), [
            'start_at' => $overlapStart->format($this->requestDateFormat),
            'end_at' => $overlapEnd->format($this->requestDateFormat),
            'email' => 'test@example.com',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['start_at']);
    }

    /** @test */
    public function it_allows_non_overlapping_appointments(): void
    {
        $start = Carbon::now()->next(CarbonInterface::MONDAY)->setTime(10, 0);
        $end = $start->copy()->addHour();

        Appointment::create([
            'start_at' => $start,
            'end_at' => $end,
            'email' => 'existing@example.com',
        ]);

        $newStart = $end->copy(); // 11:00
        $newEnd = $newStart->copy()->addHour(); // 12:00

        $response = $this->postJson($this->getApiUrl(), [
            'start_at' => $newStart->format($this->requestDateFormat),
            'end_at' => $newEnd->format($this->requestDateFormat),
            'email' => 'new@example.com',
        ]);

        $response->assertCreated();
        $this->assertDatabaseHas('appointments', [
            'email' => 'new@example.com',
            'start_at' => $newStart->format($this->databaseDateFormat),
        ]);
    }
}
