<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::table('users', function (Blueprint $table) {
			$table->string('name')->nullable()->change();
			$table->string('username', 20)->after('name')->unique();
			$table->tinyInteger('order_column')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		//
	}
};
