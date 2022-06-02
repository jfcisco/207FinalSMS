<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IconSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('icons')->insert([
            [
                'name' => 'Default Icon',
                'path' => '/assets/chat.svg',
            ],
            [
                'name' => 'Medical Chat Icon',
                'path' => '/assets/comment-medical-solid.svg',
            ],
        ]);
    }
}
