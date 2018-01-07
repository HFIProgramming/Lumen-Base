<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActionLogsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('action_logs', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('user_id');
			$table->string('model_name')->nullable();
			$table->string('action');
			$table->string('description')->nullable();
			$table->string('ip_address');

			$table->unsignedInteger('created_at');
			$table->unsignedInteger('updated_at');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('action_logs');
	}
}
