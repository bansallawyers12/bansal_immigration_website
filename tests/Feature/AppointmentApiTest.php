<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\User;
use App\Admin;
use App\Appointment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;

class AppointmentApiTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create a test admin
        $this->user = Admin::create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'first_name' => 'Test',
            'last_name' => 'Admin',
            'role' => 1
        ]);

        // Authenticate user and get token
        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'password'
        ]);

        $this->token = $response->json('data.token');
    }

    /** @test */
    public function it_can_get_all_appointments()
    {
        // Create some test appointments
        Appointment::factory()->count(5)->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/appointments');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'message',
                    'data' => [
                        'current_page',
                        'data' => [
                            '*' => [
                                'id',
                                'full_name',
                                'email',
                                'date',
                                'time',
                                'status'
                            ]
                        ],
                        'total',
                        'per_page'
                    ]
                ]);
    }

    /** @test */
    public function it_can_create_an_appointment()
    {
        $appointmentData = [
            'full_name' => 'John Doe',
            'email' => 'john@example.com',
            'date' => '2024-01-15',
            'time' => '10:00',
            'description' => 'Test consultation',
            'status' => 'pending',
            'appointment_details' => 'Detailed notes about the appointment'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/appointments', $appointmentData);

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'message' => 'Appointment created successfully.'
                ]);

        $this->assertDatabaseHas('appointments', [
            'full_name' => 'John Doe',
            'email' => 'john@example.com',
            'date' => '2024-01-15',
            'time' => '10:00'
        ]);
    }

    /** @test */
    public function it_can_get_single_appointment()
    {
        $appointment = Appointment::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/appointments/' . $appointment->id);

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'message' => 'Appointment retrieved successfully.'
                ])
                ->assertJsonStructure([
                    'data' => [
                        'id',
                        'full_name',
                        'email',
                        'date',
                        'time',
                        'status'
                    ]
                ]);
    }

    /** @test */
    public function it_can_update_an_appointment()
    {
        $appointment = Appointment::factory()->create([
            'status' => 'pending'
        ]);

        $updateData = [
            'status' => 'confirmed',
            'description' => 'Updated description'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->putJson('/api/appointments/' . $appointment->id, $updateData);

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'message' => 'Appointment updated successfully.'
                ]);

        $this->assertDatabaseHas('appointments', [
            'id' => $appointment->id,
            'status' => 'confirmed'
        ]);
    }

    /** @test */
    public function it_can_delete_an_appointment()
    {
        $appointment = Appointment::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->deleteJson('/api/appointments/' . $appointment->id);

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'message' => 'Appointment deleted successfully.'
                ]);

        $this->assertDatabaseMissing('appointments', [
            'id' => $appointment->id
        ]);
    }

    /** @test */
    public function it_can_get_appointment_statistics()
    {
        // Create appointments with different statuses
        Appointment::factory()->count(3)->create(['status' => 'pending']);
        Appointment::factory()->count(5)->create(['status' => 'confirmed']);
        Appointment::factory()->count(2)->create(['status' => 'completed']);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/appointments/statistics/overview');

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'message' => 'Appointment statistics retrieved successfully.'
                ])
                ->assertJsonStructure([
                    'data' => [
                        'total',
                        'pending',
                        'confirmed',
                        'cancelled',
                        'completed',
                        'rescheduled',
                        'today',
                        'this_week',
                        'this_month'
                    ]
                ]);
    }

    /** @test */
    public function it_can_bulk_update_appointment_status()
    {
        $appointments = Appointment::factory()->count(3)->create(['status' => 'pending']);

        $appointmentIds = $appointments->pluck('id')->toArray();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/appointments/bulk-update-status', [
            'appointment_ids' => $appointmentIds,
            'status' => 'confirmed'
        ]);

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'message' => 'Appointments status updated successfully.'
                ]);

        foreach ($appointmentIds as $id) {
            $this->assertDatabaseHas('appointments', [
                'id' => $id,
                'status' => 'confirmed'
            ]);
        }
    }

    /** @test */
    public function it_can_search_appointments()
    {
        Appointment::factory()->create([
            'full_name' => 'John Doe',
            'description' => 'Test consultation'
        ]);

        Appointment::factory()->create([
            'full_name' => 'Jane Smith',
            'description' => 'Another consultation'
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/appointments?search=John');

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'message' => 'Appointments retrieved successfully.'
                ]);

        $data = $response->json('data.data');
        $this->assertCount(1, $data);
        $this->assertEquals('John Doe', $data[0]['full_name']);
    }

    /** @test */
    public function it_can_filter_appointments_by_status()
    {
        Appointment::factory()->create(['status' => 'pending']);
        Appointment::factory()->create(['status' => 'confirmed']);
        Appointment::factory()->create(['status' => 'completed']);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/appointments?status=confirmed');

        $response->assertStatus(200);

        $data = $response->json('data.data');
        $this->assertCount(1, $data);
        $this->assertEquals('confirmed', $data[0]['status']);
    }

    /** @test */
    public function it_requires_authentication_for_protected_endpoints()
    {
        $response = $this->getJson('/api/appointments');

        $response->assertStatus(401);
    }

    /** @test */
    public function it_validates_required_fields_when_creating_appointment()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/appointments', []);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['date', 'time', 'description', 'status', 'appointment_details']);
    }

    /** @test */
    public function it_returns_404_for_nonexistent_appointment()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/appointments/999');

        $response->assertStatus(200)
                ->assertJson([
                    'success' => false,
                    'message' => 'Appointment not found.'
                ]);
    }
} 