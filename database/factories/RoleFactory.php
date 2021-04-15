<?php

namespace Database\Factories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Role::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => 'default'
        ];
    }

    /**
     * @return RoleFactory
     */
    public function getAdminRole(): RoleFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'admin',
            ];
        });
    }

    /**
     * @return RoleFactory
     */
    public function getDefaultRole(): RoleFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'default',
            ];
        });
    }
}
