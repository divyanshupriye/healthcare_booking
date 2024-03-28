<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\HealthcareProfessional;

class HealthcareProfessionalsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Define data to be inserted
        $professionals = [
            ['name' => 'Dr. John Doe', 'specialty' => 'Cardiologist'],
            ['name' => 'Dr. Jane Smith', 'specialty' => 'Dermatologist'],
            // Add more professionals as needed
        ];

        // Insert data into the database
        foreach ($professionals as $professional) {
            HealthcareProfessional::create($professional);
        }
    }
}
