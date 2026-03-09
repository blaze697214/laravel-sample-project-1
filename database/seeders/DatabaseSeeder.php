<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $admin = User::factory()->create([
            'name' => 'blaze1',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin123')
        ]);
        $cdc = User::factory()->create([
            'name' => 'blaze2',
            'email' => 'cdc@gmail.com',
            'password' => Hash::make('cdc123')
        ]);
        // $hod = User::factory()->create([
        //     'name' => 'blaze31',
        //     'email' => 'hod@gmail.com',
        //     'password' => Hash::make('hod123')
        // ]);

        $adminRole = Role::create(['name' => 'admin']);
        $cdcRole = Role::create(['name' => 'cdc']);
        $hodRole = Role::create(['name' => 'hod']);
        $facultyRole = Role::create(['name' => 'faculty']);

        $admin->roles()->attach($adminRole->id);
        $cdc->roles()->attach($cdcRole->id);
        // $hod->roles()->attach($hodRole->id);
    }
}
