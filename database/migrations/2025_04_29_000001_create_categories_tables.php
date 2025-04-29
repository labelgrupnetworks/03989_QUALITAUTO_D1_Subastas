<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Create FXSEC table (Sections)
        Schema::create('fxsec', function (Blueprint $table) {
            $table->string('gemp_sec');
            $table->string('cod_sec');
            $table->string('des_sec');
            $table->string('key_sec')->nullable();
            $table->string('meta_titulo_sec')->nullable();
            $table->text('meta_description_sec')->nullable();
            $table->text('meta_contenido_sec')->nullable();

            $table->primary(['gemp_sec', 'cod_sec']);
        });

        // Create FXSEC_LANG table for multilanguage support
        Schema::create('fxsec_lang', function (Blueprint $table) {
            $table->string('gemp_sec_lang');
            $table->string('codsec_sec_lang');
            $table->string('lang_sec_lang');
            $table->string('des_sec_lang')->nullable();
            $table->string('key_sec_lang')->nullable();
            $table->string('meta_titulo_sec_lang')->nullable();
            $table->text('meta_description_sec_lang')->nullable();
            $table->text('meta_contenido_sec_lang')->nullable();

            $table->primary(['gemp_sec_lang', 'codsec_sec_lang', 'lang_sec_lang']);
            $table->foreign(['gemp_sec_lang', 'codsec_sec_lang'])->references(['gemp_sec', 'cod_sec'])->on('fxsec')->onDelete('cascade');
        });

        // Create FGORTSEC0 table (Categories)
        Schema::create('fgortsec0', function (Blueprint $table) {
            $table->string('emp_ortsec0');
            $table->string('sub_ortsec0');
            $table->integer('lin_ortsec0');
            $table->string('des_ortsec0');
            $table->string('key_ortsec0')->nullable();
            $table->integer('orden_ortsec0')->default(0);
            $table->string('meta_titulo_ortsec0')->nullable();
            $table->text('meta_description_ortsec0')->nullable();
            $table->text('meta_contenido_ortsec0')->nullable();

            $table->primary(['emp_ortsec0', 'sub_ortsec0', 'lin_ortsec0']);
        });

        // Create FGORTSEC0_LANG table (Categories Language)
        Schema::create('fgortsec0_lang', function (Blueprint $table) {
            $table->string('emp_ortsec0_lang');
            $table->string('sub_ortsec0_lang');
            $table->integer('lin_ortsec0_lang');
            $table->string('lang_ortsec0_lang');
            $table->string('des_ortsec0_lang')->nullable();
            $table->string('key_ortsec0_lang')->nullable();
            $table->integer('orden_ortsec0_lang')->default(0);
            $table->string('meta_titulo_ortsec0_lang')->nullable();
            $table->text('meta_description_ortsec0_lang')->nullable();
            $table->text('meta_contenido_ortsec0_lang')->nullable();

            $table->primary(['emp_ortsec0_lang', 'sub_ortsec0_lang', 'lin_ortsec0_lang', 'lang_ortsec0_lang']);
            $table->foreign(['emp_ortsec0_lang', 'sub_ortsec0_lang', 'lin_ortsec0_lang'])
                ->references(['emp_ortsec0', 'sub_ortsec0', 'lin_ortsec0'])
                ->on('fgortsec0')
                ->onDelete('cascade');
        });

        // Create FGORTSEC1 table (Category-Section Relations)
        Schema::create('fgortsec1', function (Blueprint $table) {
            $table->string('emp_ortsec1');
            $table->string('sub_ortsec1');
            $table->integer('lin_ortsec1');
            $table->string('sec_ortsec1');

            $table->primary(['emp_ortsec1', 'sub_ortsec1', 'lin_ortsec1', 'sec_ortsec1']);

            // Foreign key to FGORTSEC0
            $table->foreign(['emp_ortsec1', 'sub_ortsec1', 'lin_ortsec1'])
                ->references(['emp_ortsec0', 'sub_ortsec0', 'lin_ortsec0'])
                ->on('fgortsec0')
                ->onDelete('cascade');

            // Foreign key to FXSEC (assuming gemp_sec = gemp_sec and cod_sec = sec_ortsec1)
            $table->foreign(['emp_ortsec1', 'sec_ortsec1'])
                ->references(['gemp_sec', 'cod_sec'])
                ->on('fxsec')
                ->onDelete('cascade');
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
        Schema::dropIfExists('fgortsec1');
        Schema::dropIfExists('fgortsec0_lang');
        Schema::dropIfExists('fgortsec0');
        Schema::dropIfExists('fxsec_lang');
        Schema::dropIfExists('fxsec');
    }
}
