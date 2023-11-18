<?php

namespace Database\Seeders;

use App\Models\BankAcount;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BankAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $date = Carbon::now();

        $payloads = [
            [
                'id' => 1,
                'account_name' => 'account 1',
                'account_number' => '111111',
                'created_at'=> $date,
                'updated_at'=> $date
            ],
            [
                'id' => 2,
                'account_name' => 'account 2',
                'account_number' => '222222',
                'created_at'=> $date,
                'updated_at'=> $date
            ],
            [
                'id' => 3,
                'account_name' => 'account 3',
                'account_number' => '333333',
                'created_at'=> $date,
                'updated_at'=> $date
            ],
        ];

        BankAcount::insert($payloads);
    }
}
