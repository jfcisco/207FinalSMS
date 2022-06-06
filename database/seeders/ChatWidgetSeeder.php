<?php

namespace Database\Seeders;

use App\Models\ChatWidget;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ChatWidgetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::all()->first();

        $chatWidget = new ChatWidget();
        $chatWidget->created_by_id = $user->_id;
        $chatWidget->name = 'Chat Widget';
        $chatWidget->position = 'right';
        $chatWidget->hide_when_offline = false;
        $chatWidget->hide_when_on_desktop = false;
        $chatWidget->hide_when_on_mobile = false;
        $chatWidget->enable_emojis = false;
        $chatWidget->is_active = true;
        $chatWidget->color = "#ff8000";
        $chatWidget->icon = "/assets/chat.svg";
        $chatWidget->inactivity_timeout_minutes = 30;
        $chatWidget->sched_enabled = false;
        $chatWidget->save();

        $user->chat_widgets()->attach($chatWidget->_id);
        $user->save();

    }
}
