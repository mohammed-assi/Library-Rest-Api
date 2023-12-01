<?php

namespace Database\Seeders;

use App\Models\categories;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        categories::insert([
            [
                'from_year'=> 1,
                'to_year' => 7,
                'color'=> '#A5CF61'
            ],
            [
                'from_year'=> 7,
                'to_year' => 12,
                'color'=> '#F4A15D'

            ],
            [
                'from_year'=> 12,
                'to_year' => 18,
                'color'=> '#A7DDFF'

            ]
                
                

        ]);
    }
}
