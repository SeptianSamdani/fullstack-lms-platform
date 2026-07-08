<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = ['Web Development', 'Data Science', 'Mobile Development', 'UI/UX Design', 'Business'];
        foreach ($categories as $name) {
            Category::create(['name' => $name]);
        }
    }
}
