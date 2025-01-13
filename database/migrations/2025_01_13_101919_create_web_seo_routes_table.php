<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if (!Schema::hasTable('web_seo_routes')) {
			Schema::create('web_seo_routes', function (Blueprint $table) {
				$table->increments('id_seo_routes'); // Se usa increments para una columna AUTO_INCREMENT
				$table->string('key_seo_routes');
				$table->string('lang_seo_routes', 2)->nullable(); // Se puede ser nullable si es opcional
				$table->string('keylang_seo_routes');
				$table->string('domain_seo_routes')->nullable(); // Puede ser nullable
				$table->string('id_emp', 3); // Longitud fija de 3 caracteres
				$table->timestamps(); // Opcional, si quieres agregar campos created_at y updated_at
			});
		}
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('web_seo_routes');
	}
};
