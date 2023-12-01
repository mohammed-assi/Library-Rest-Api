<?php

namespace Database\Seeders;

use App\Models\books;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class bookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        books::insert([
            [
            'name'=>'dd',
            ],

            [
                'name'=>'dd',
                ],

                [
                    'name'=>'dd',
                    ],

                    [
                        'name'=>'dd',
                        ],

        ]);
    }
}
