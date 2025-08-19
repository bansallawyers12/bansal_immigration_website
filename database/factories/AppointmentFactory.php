<?php

namespace Database\Factories;

use App\Appointment;
use Illuminate\Database\Eloquent\Factories\Factory;

class AppointmentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Appointment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $statuses = ['pending', 'confirmed', 'cancelled', 'completed', 'rescheduled'];
        
        return [
            'user_id' => $this->faker->numberBetween(1, 10),
            'client_id' => $this->faker->numberBetween(1, 10),
            'client_unique_id' => 'CLI' . $this->faker->unique()->numberBetween(1000, 9999),
            'timezone' => $this->faker->timezone,
            'email' => $this->faker->unique()->safeEmail,
            'noe_id' => $this->faker->numberBetween(1, 5),
            'service_id' => $this->faker->numberBetween(1, 5),
            'assinee' => $this->faker->numberBetween(1, 10),
            'full_name' => $this->faker->name,
            'date' => $this->faker->dateTimeBetween('now', '+30 days')->format('Y-m-d'),
            'time' => $this->faker->time('H:i'),
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph,
            'invites' => $this->faker->numberBetween(0, 5),
            'status' => $this->faker->randomElement($statuses),
            'related_to' => $this->faker->optional()->sentence(2),
            'preferred_language' => $this->faker->randomElement(['English', 'Spanish', 'French', 'German']),
            'inperson_address' => $this->faker->optional()->address,
            'timeslot_full' => $this->faker->time('H:i') . '-' . $this->faker->time('H:i'),
            'appointment_details' => $this->faker->paragraphs(2, true),
            'order_hash' => $this->faker->optional()->uuid,
        ];
    }

    /**
     * Indicate that the appointment is pending.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function pending()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'pending',
            ];
        });
    }

    /**
     * Indicate that the appointment is confirmed.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function confirmed()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'confirmed',
            ];
        });
    }

    /**
     * Indicate that the appointment is completed.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function completed()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'completed',
            ];
        });
    }

    /**
     * Indicate that the appointment is cancelled.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function cancelled()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'cancelled',
            ];
        });
    }

    /**
     * Indicate that the appointment is for today.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function today()
    {
        return $this->state(function (array $attributes) {
            return [
                'date' => now()->format('Y-m-d'),
            ];
        });
    }

    /**
     * Indicate that the appointment is for this week.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function thisWeek()
    {
        return $this->state(function (array $attributes) {
            return [
                'date' => $this->faker->dateTimeBetween(now()->startOfWeek(), now()->endOfWeek())->format('Y-m-d'),
            ];
        });
    }

    /**
     * Indicate that the appointment is for this month.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function thisMonth()
    {
        return $this->state(function (array $attributes) {
            return [
                'date' => $this->faker->dateTimeBetween(now()->startOfMonth(), now()->endOfMonth())->format('Y-m-d'),
            ];
        });
    }
}
