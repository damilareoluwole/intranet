<?php

namespace Database\Factories;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class EmployeeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Employee::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'userId' => Str::uuid(), 
            'displayName' => $this->faker->name,
            'distinguishedName' => $this->faker->name,
            'emailAddress' => $this->faker->unique()->safeEmail,
            'givenName' => $this->faker->name,
            'guid' => Str::uuid(),
            'middleName' => $this->faker->name,
            'samAccountName' => $this->faker->name,
            'surname' => $this->faker->name,
            'created_at' => now(),
            'updated_at' => now()
        ];
    }
}
