<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('loggable.log_table'), function (Blueprint $table) {
            $table->increments('id');
            $table->smallInteger('level')->default(0);
            $table->string('level_name')->index();
            $table->text('message');
            $table->text('context')->nullable();
            $table->morphs('loggable');
            $table->integer('user_id')->index()->nullable();
            $table->string('remote_ip', 100)->nullable();
            $table->text('user_agent')->nullable();
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
        Schema::dropIfExists(config('loggable.log_table'));
    }
}
