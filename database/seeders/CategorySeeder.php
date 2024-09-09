<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $category = new Category();

        $category->name = 'Motos';
        $category->save();

        $category = new category();

        $category->name = 'Ropa';
        $category->save();

        $category = new category();

        $category->name = 'Computadores';
        $category->save();
    }
}
