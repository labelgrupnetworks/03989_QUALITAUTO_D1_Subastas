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
		//create only if not exists
		if (!Schema::hasTable('web_config')) {
			Schema::create('web_config', function (Blueprint $table) {
				$table->id('id_web_config'); // Número entero largo
				$table->string('key', 100); // Clave con hasta 100 caracteres
				$table->string('value', 100)->nullable(); // Valor con hasta 100 caracteres
				$table->string('emp', 4); // Empresa con hasta 4 caracteres
				$table->longText('info')->nullable(); // Campo largo para información adicional
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
		Schema::dropIfExists('web_config');
	}
};
