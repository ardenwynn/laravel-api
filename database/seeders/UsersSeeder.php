<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->times(100)->create();
        $allUsers = User::all();
        $defaultRole = Role::factory()->getDefaultRole()->create();
        foreach($allUsers as $user) {
            $user->roles()->attach($defaultRole);
        }
        $adminRole = Role::factory()->getAdminRole()->create();
        $adminUser = User::factory()->getAdminUser()->create();
        $adminUser->roles()->attach($adminRole);
    }
}
