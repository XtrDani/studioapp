<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\Service;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

class AppointmentFactory extends Factory
{
    protected $model = Appointment::class;

    public function definition()
    {
        $employeeId = Employee::query()->inRandomOrder()->first()?->id ?? 1;
        $serviceId = Service::query()->inRandomOrder()->first()?->id ?? 1;
        return [
            'user_id' => null,
            'employee_id' => $employeeId,
            'service_id' => $serviceId,
            'booking_id' => 'BK-' . strtoupper(uniqid()),
            'name' => $this->faker->name(),
            'email' => $this->faker->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'notes' => $this->faker->optional()->sentence(),
            'amount' => $this->faker->randomFloat(2, 100, 1000),
            'booking_date' => $this->faker->dateTimeBetween('-2 months', 'now')->format('Y-m-d'),
            'booking_time' => $this->faker->time('H:i'),
            'status' => 'Completed',
        ];
    }
}
