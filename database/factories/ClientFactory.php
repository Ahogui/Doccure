<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ClientFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->phoneNumber,
            'address' => $this->faker->address,
            'city' => $this->faker->city,
            'country' => $this->faker->country,
            'tax_number' => $this->faker->optional()->numerify('TVA########'),
            'client_type' => $this->faker->randomElement(['particulier', 'entreprise', 'hopital', 'autre'])
        ];
    }
}