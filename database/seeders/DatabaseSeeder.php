<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        collect([
            [
                'name' => Role::ADMIN,
                'status' => true,
                'description' => 'A system administrator',
            ],
            [
                'name' => Role::CASHIER,
                'status' => true,
                'description' => 'A system cashier',
            ],
        ])->each(function ($role) {
            Role::create($role);
        });

        Role::where('name', Role::ADMIN)->first()->save(
            [
                User::create(
                    [
                        'name' => 'Admin',
                        'status' => true,
                        'role_id' => 1,
                        'mobile' => '0620650411',
                        'password' => bcrypt('12312345'),
                    ],
                ),
            ]
        );
        Role::where('name', Role::CASHIER)->first()->save(
            [

                User::create(
                    [
                        'name' => 'Cashier',
                        'status' => true,
                        'role_id' => 2,
                        'mobile' => '0714871033',
                        'password' => bcrypt('12312345'),
                    ],
                ),
            ]
        );
    }
}
