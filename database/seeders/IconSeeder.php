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
            [
                'name' => 'SMS Icon',
                'path' => '/assets/chat2.svg',
            ],
            [
                'name' => 'Two Chat Bubbles Icon',
                'path' => '/assets/chat3.svg',
            ],
            [
                'name' => 'Headset Icon',
                'path' => '/assets/chat4.svg',
            ],
        ]);
    }
}
