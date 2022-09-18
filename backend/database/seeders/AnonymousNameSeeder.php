<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AnonymousNameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('anonymous_names')->insert([
            ['name' => 'Rabbit'],
            ['name' => 'Duck'],
            ['name' => 'Pig'],
            ['name' => 'Bee'],
            ['name' => 'Goat'],
            ['name' => 'Crab'],
            ['name' => 'Fish'],
            ['name' => 'Chicken'],
            ['name' => 'Horse'],
            ['name' => 'Llamas'],
            ['name' => 'Ostrich'],
            ['name' => 'Camel'],
            ['name' => 'Shrimp'],
            ['name' => 'Deer'],
            ['name' => 'Turkey'],
            ['name' => 'Dove'],
            ['name' => 'Sheep'],
            ['name' => 'Cat'],
            ['name' => 'Goose'],
            ['name' => 'Oxen'],
            ['name' => 'Reindeer'],
        ]);
    }
}
