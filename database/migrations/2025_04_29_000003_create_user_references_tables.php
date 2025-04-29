<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserReferencesTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Create fsaux1 (Document Types and Other Auxiliary Data) table
        Schema::create('fsaux1', function (Blueprint $table) {
            $table->string('cod1_aux1')->primary();
            $table->string('des_aux1');
            $table->string('emp_aux1');
            $table->string('idioma_aux1', 5);
            $table->enum('baja_aux1', ['S', 'N'])->default('N');
            $table->string('cod2_aux1')->nullable();

            // Add index for faster lookups
            $table->index(['emp_aux1', 'idioma_aux1', 'baja_aux1']);
        });

        // Create FsDiv (Divisa/Currency) table
        Schema::create('fsdiv', function (Blueprint $table) {
            $table->string('cod_div')->primary();
            $table->string('des_div');
            $table->decimal('impd_div', 15, 6)->nullable();
            $table->boolean('impm_div')->default(0);
            $table->decimal('comi_div', 8, 2)->nullable();
            $table->string('divori_div')->nullable();
            $table->string('simbol_div', 10)->nullable();
            $table->string('symbolhtml_div', 20)->nullable();
            $table->enum('pos_div', ['D', 'I'])->default('D');
        });

        // Create FgSg (Street Types) table
        Schema::create('fgsg', function (Blueprint $table) {
            $table->string('cod_sg')->primary();
            $table->string('des_sg');
        });

        // Create FgSg_Lang (Street Types Translations) table
        Schema::create('fgsg_lang', function (Blueprint $table) {
            $table->string('cod_sg_lang');
            $table->string('lang_sg_lang', 5);
            $table->string('des_sg_lang');

            $table->primary(['cod_sg_lang', 'lang_sg_lang']);
            $table->foreign('cod_sg_lang')->references('cod_sg')->on('fgsg');
        });

        // Create FsPaises (Countries) table
        Schema::create('fspaises', function (Blueprint $table) {
            $table->string('cod_paises')->primary();
            $table->string('des_paises');
        });

        // Create FsPaises_Lang (Countries Translations) table
        Schema::create('fspaises_lang', function (Blueprint $table) {
            $table->string('cod_paises_lang');
            $table->string('lang_paises_lang', 5);
            $table->string('des_paises_lang');

            $table->primary(['cod_paises_lang', 'lang_paises_lang']);
            $table->foreign('cod_paises_lang')->references('cod_paises')->on('fspaises');
        });

        // Create FsIdioma (Languages) table
        Schema::create('fsidioma', function (Blueprint $table) {
            $table->string('cod_idioma')->primary();
            $table->string('des_idioma');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Drop tables in reverse order to avoid foreign key constraints
        Schema::dropIfExists('fgsg_lang');
        Schema::dropIfExists('fspaises_lang');
        Schema::dropIfExists('fgsg');
        Schema::dropIfExists('fspaises');
        Schema::dropIfExists('fsidioma');
        Schema::dropIfExists('fsdiv');
        Schema::dropIfExists('fsaux1');
    }
}
