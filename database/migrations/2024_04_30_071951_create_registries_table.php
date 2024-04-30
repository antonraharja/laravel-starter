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
		Schema::create('registries', function (Blueprint $table) {
			$table->id();
			$table->string('class', 60)->default('DEFAULT');
			$table->string('group', 60);
			$table->string('keyword', 60);
			$table->text('content')->nullable();
			$table->timestamps();
			$table->unique(['class', 'group', 'keyword']);
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('registries');
	}
};