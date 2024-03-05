<?php

namespace Database\Seeders;

use App\Models\Activity;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Activity::insert([
            [
                'description' => 'Actividad De Prueba',
                'hours' => 8,
                'technician_id' => 1,
                'type_id' => 1
            ],
            [
                'description' => 'Actividad De Prueba 2',
                'hours' => 16,
                'technician_id' => 1,
                'type_id' => 2
            ]
        ]);
    }
}
