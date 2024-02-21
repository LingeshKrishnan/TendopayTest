<?php

namespace Database\Seeders;

use App\Models\Categories;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        Categories::truncate();
        Schema::enableForeignKeyConstraints();

        $categories = [
            'Novel',
            'SciFi',
            'Poem',
            'Literature',
            'Politics',
            'Comedy',
            'Love'
        ];

        foreach ($categories as $data) {
            Categories::insert(
                [
                    'name' => $data
                ]
            );
        }
    }
}
