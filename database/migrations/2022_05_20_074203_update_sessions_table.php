<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sessions', function (Blueprint $table) {
            $table->renameColumn('visitor_id', 'clientId');
            $table->renameColumn('socket_id', 'socketId');
            $table->string('clientType');
            $table->renameColumn('chat_widget_id', 'widgetId');
            $table->renameColumn('started_at', 'startedAt');
            $table->renameColumn('ended_at', 'endedAt');
            $table->dropColumn('ip_address');
            $table->dropColumn('browser');
            $table->dropColumn('webpage_source');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sessions', function (Blueprint $table) {
            $table->renameColumn('clientId', 'visitor_id');
            $table->renameColumn('socketId', 'socket_id');
            $table->dropColumn('clientType');
            $table->renameColumn('chatWidgetId', 'chat_widget_id');
            $table->renameColumn('startedAt', 'started_at');
            $table->renameColumn('endedAt', 'ended_at');
            $table->string('ip_address')->nullable();
            $table->string('browser')->nullable();
            $table->string('webpage_source')->nullable();
        });
    }
}
