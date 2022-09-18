<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

use Spatie\Permission\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $guard = 'web';

        $sysAdmin = Role::create([
            'id' => 1, 
            'name' => 'admin', 
            'guard_name' => $guard
        ]);

        $kaprodi = Role::create([
            'id' => 2, 
            'name' => 'user', 
            'guard_name' => $guard
        ]);
    }
}
