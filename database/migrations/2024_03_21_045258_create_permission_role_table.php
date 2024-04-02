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
		schema::create('permission_role', function (Blueprint $table) {
			$table->id();
			$table->unsignedBiginteger('permission_id');
			$table->unsignedBiginteger('role_id');
			$table->timestamps();

			$table->foreign('permission_id')->references('id')
				->on('permissions')->onDelete('cascade');
			$table->foreign('role_id')->references('id')
				->on('roles')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('permission_role');
	}
};
