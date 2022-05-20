<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropColumn('session_id');
            $table->dropColumn('created_by_id');
            $table->dropColumn('attachment_path');
            $table->renameColumn('message', 'content');
            $table->string('clientId');
            $table->string('clientType');
            $table->string('clientName');
            $table->renameColumn('is_whisper', 'isWhisper');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->string('session_id');
            $table->string('created_by_id');
            $table->string('attachment_path');
            $table->renameColumn('content', 'message');
            $table->dropColumn('clientId');
            $table->dropColumn('clientType');
            $table->dropColumn('clientName');
            $table->renameColumn('isWhisper', 'is_whisper');
        });
    }
}
