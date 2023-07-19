<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => $this->faker->password
        ];
    }

    /**
     * Create natural person user type.
     *
     * @return array
     */
    public function createNaturalPerson()
    {
        return $this->state(function (array $attributes) {
            return [
                'document' => $this->faker->numerify('###########'),
                'type' => 1,
            ];
        });
    }

    /**
     * Create legal entity user type.
     *
     * @return array
     */
    public function createLegalEntity()
    {
        return $this->state(function (array $attributes) {
            return [
                'document' => $this->faker->numerify('##############'),
                'type' => 2,
            ];
        });
    }
}
