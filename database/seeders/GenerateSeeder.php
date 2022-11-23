<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Item;
use App\Models\Main_category;
use App\Models\Restaurant;
use App\Models\User;
use Database\Factories\Main_categoryFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class GenerateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Main_category::factory()->count(4)->create();
        Restaurant::factory()
            ->count(15)
            ->has(Category::factory()
                ->count(5)
                ->has(Item::factory()->count(5),'items')
                ,'category')
            ->create();


        User::factory()->create([
            'username' => 'admin',
            'password' => 'admin1'
        ]);
        User::factory()->create([
            'username' => 'client',
            'password' => 'client'
        ]);
        User::factory()->count(25)->create();




    }
}
