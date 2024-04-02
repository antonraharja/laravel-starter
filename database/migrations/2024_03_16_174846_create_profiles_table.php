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
		Schema::create('profiles', function (Blueprint $table) {
			$table->id();
			$table->foreignIdFor(\App\Models\User::class)->constrained()->cascadeOnDelete();
			$table->string('first_name');
			$table->string('last_name')->nullable();
			$table->string('photo')->nullable();
			$table->date('dob')->nullable();
			$table->string('country')->nullable();
			$table->string('city')->nullable();
			$table->string('address')->nullable();
			$table->mediumText('bio')->nullable();
			$table->string('contact')->nullable();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('profiles');
	}
};
