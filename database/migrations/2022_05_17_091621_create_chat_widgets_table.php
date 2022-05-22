<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatWidgetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_widgets', function (Blueprint $table) {
            $table->id();
            $table->string('created_by_id');
            $table->string('name');
            $table->string('color')->nullable();
            $table->string('position')->nullable();
            $table->boolean('hide_when_offline')->default(false);
            $table->boolean('hide_when_on_desktop')->default(false);
            $table->boolean('hide_when_on_mobile')->default(false);
            $table->boolean('enable_emojis')->default(false);
            $table->timestamp('availability_start_time')->nullable();
            $table->timestamp('availability_end_time')->nullable();
            $table->string('allowed_domains')->nullable();
            $table->string('generated_code')->nullable();
            $table->string('direct_chat_link')->nullable();
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chat_widgets');
    }
}
