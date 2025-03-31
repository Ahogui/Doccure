<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $departments = [
            ['name' => 'Cardiologie', 'description' => 'Département de cardiologie'],
            ['name' => 'Pédiatrie', 'description' => 'Département de pédiatrie'],
            // ...
        ];

        foreach ($departments as $department) {
            Department::create($department);
        }
    }
}
