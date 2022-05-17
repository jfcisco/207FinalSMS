<?php

namespace Database\Seeders;

use App\Models\ChatWidget;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Jenssegers\Mongodb\Auth\User;

class ChatWidgetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('chat_widgets')->insert([
            [
                'created_by' => '62838474a7dfb329dd026d6b',
                'name' => 'Chat Widget',
                'color' => null,
                'position' => 'right',
                'hide_when_offline' => false,
                'hide_when_on_desktop' => false,
                'hide_when_on_mobile' => false,
                'enable_emojis' => false,
                'availability_start_time' => false,
                'availability_end_time' => false,
                'allowed_domains' => false,
                'generated_code' => false,
                'direct_chat_link' => false,
                'is_active' => true,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
        ]);

        $adminUser = User::find('62838474a7dfb329dd026d6b');

        ChatWidget::all()->each(function ($chatWidget) use ($adminUser) {
            $chatWidget->agents()->attach($adminUser);
        });
    }
}
