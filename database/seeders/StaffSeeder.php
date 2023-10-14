<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = [
            [
                'id' => 1,
                'name' => 'Admin',
                'email' => 'admin@roerar.com',
                'password' => Hash::make('password'),
                'created_at'=> Carbon::now(),
                'updated_at'=> Carbon::now()
            ]
        ];

        User::insert($user);

        $user = User::find(1);

        $user->staff()->create([
            'position' => 'Admin',
            'phone_number' => '09123456789',
            'salary' => '1000000',
            'address' => 'Yangon',
        ]);
    }
}
