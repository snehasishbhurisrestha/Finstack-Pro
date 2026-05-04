<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Baji;

class BajiData extends Seeder
{
    public function run(): void
    {
        $bajis = [
            [
                'name' => '1 BAJI',
                'start_time' => '06:00:00',
                'end_time' => '10:05:00',
                'status' => 1,
            ],
            [
                'name' => '2 BAJI',
                'start_time' => '10:06:00',
                'end_time' => '11:35:00',
                'status' => 1,
            ],
            [
                'name' => '3 BAJI',
                'start_time' => '11:36:00',
                'end_time' => '13:05:00',
                'status' => 1,
            ],
            [
                'name' => '4 BAJI',
                'start_time' => '13:06:00',
                'end_time' => '14:35:00',
                'status' => 1,
            ],
            [
                'name' => '5 BAJI',
                'start_time' => '14:36:00',
                'end_time' => '16:05:00',
                'status' => 1,
            ],
            [
                'name' => '6 BAJI',
                'start_time' => '16:06:00',
                'end_time' => '17:35:00',
                'status' => 1,
            ],
            [
                'name' => '7 BAJI',
                'start_time' => '17:36:00',
                'end_time' => '19:05:00',
                'status' => 1,
            ],
            [
                'name' => '8 BAJI',
                'start_time' => '19:06:00',
                'end_time' => '20:35:00',
                'status' => 1,
            ],
        ];

        foreach ($bajis as $baji) {
            Baji::create($baji);
        }
    }
}