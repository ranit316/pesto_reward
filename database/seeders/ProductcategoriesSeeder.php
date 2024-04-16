<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class ProductcategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::create([
            'name' => 'SwitchBox',
            'description' => 'This is for Test Purpose',
             'image' => 'images/category/1705557544.jpg',
            'status' => 'active',
        ]);
        Category::create([
            'name' => 'Wire',
            'description' => 'This is for Test Purpose',
             'image' => 'images/category/1706334058.png',
            'status' => 'active',
        ]);

    }
}
