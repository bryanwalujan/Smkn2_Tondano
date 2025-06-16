<?php

namespace Database\Seeders;

use App\Models\Classroom;
use Illuminate\Database\Seeder;

class ClassroomSeeder extends Seeder
{
    public function run(): void
    {
        Classroom::create(['name' => 'Kelas 10A']);
        Classroom::create(['name' => 'Kelas 10B']);
        Classroom::create(['name' => 'Kelas 11A']);
    }
}