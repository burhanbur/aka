<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'username' => 'rsari',
            'email' => 'ressy.ressy11@gmail.com',
            'password' => Hash::make('password123'),
        ]);

        $admin = User::whereIn('username', array('rsari'))->get();

        foreach ($admin as $value) {
            $value->assignRole('admin');
        }
    }
}
