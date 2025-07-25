<?php
// All times are handled in UTC for consistency with frontend and backend

namespace Tests\Feature;

use App\Models\WorkingHour;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateWorkingHoursTest extends TestCase
{
    use RefreshDatabase;

    protected function getApiUrl(): string
    {
        return route('working-hours.update');
    }


    /** @test */
    public function it_creates_working_hours_for_a_day(): void
    {
        $payload = [
            'day_of_week' => 1,          // Monday
            'start_time'  => '09:00',
            'end_time'    => '17:00',
        ];

        $response = $this->putJson($this->getApiUrl(), $payload);

        $response->assertStatus(200);

        $this->assertDatabaseHas('working_hours', [
            'day_of_week' => 1,
            'start_time'  => '09:00',
            'end_time'    => '17:00',
        ]);
    }

    /** @test */
    public function it_updates_existing_working_hours_for_a_day(): void
    {
        // Seed an existing row
        WorkingHour::create([
            'day_of_week' => 2,
            'start_time'  => '08:00',
            'end_time'    => '16:00',
        ]);

        $payload = [
            'day_of_week' => 2,
            'start_time'  => '10:00',
            'end_time'    => '18:00',
        ];

        $response = $this->putJson($this->getApiUrl(), $payload);

        $response->assertStatus(200);
        $this->assertDatabaseHas('working_hours', [
            'day_of_week' => 2,
            'start_time'  => '10:00',
            'end_time'    => '18:00',
        ]);
    }

    /** @test */
    public function it_marks_a_day_as_closed(): void
    {
        $payload = [
            'day_of_week' => 1,          // Monday
            'start_time'  => '09:00',
            'end_time'    => '17:00',
        ];

        $response = $this->putJson($this->getApiUrl(), $payload);

        $response->assertStatus(200);

        $payload = [
            'day_of_week' => 1,          // Friday
            'closed'      => true,
        ];

        $response = $this->putJson($this->getApiUrl(), $payload);
        $response->assertStatus(200);

        $this->assertDatabaseMissing('working_hours', [
            'day_of_week' => 5,
        ]);
    }

    /** @test */
    public function it_rejects_invalid_time_format(): void
    {
        $payload = [
            'day_of_week' => 3,
            'start_time'  => '09:00:00',  // should be H:i
            'end_time'    => '17:00:00',
        ];

        $response = $this->putJson($this->getApiUrl(), $payload);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['start_time', 'end_time']);
    }
}
