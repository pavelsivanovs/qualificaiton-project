<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserStatusChangeRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_status_change_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user')->references('id')->on('users');
            $table->foreignId('user_requested_status')->references('id')->on('user_statuses');
            $table->foreignId('request_status')->references('id')->on('request_statuses');
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
        Schema::dropIfExists('user_status_change_requests');
    }
}
