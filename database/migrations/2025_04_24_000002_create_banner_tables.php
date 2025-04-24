<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBannerTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Create the banner type table first since it's referenced by the main banner table
        Schema::create('web_newbanner_tipo', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('bloques')->nullable();
            $table->text('opciones')->nullable();
            $table->boolean('completo')->default(0);
        });

        // Create the main banner table
        Schema::create('web_newbanner', function (Blueprint $table) {
            $table->id();
            $table->string('key')->index();
            $table->string('empresa');
            $table->unsignedBigInteger('id_web_newbanner_tipo');
            $table->boolean('activo')->default(1);
            $table->string('ubicacion')->nullable();
            $table->integer('orden')->default(0);

            // Foreign key relationships
            $table->foreign('id_web_newbanner_tipo')->references('id')->on('web_newbanner_tipo');

            // Create index for empresa for the global scope
            $table->index('empresa');
        });

        // Create the banner item table
        Schema::create('web_newbanner_item', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_web_newbanner');
            $table->integer('bloque')->default(0);
            $table->text('texto')->nullable();
            $table->string('url')->nullable();
            $table->boolean('ventana_nueva')->default(0);
            $table->boolean('activo')->default(1);
            $table->string('lenguaje', 10)->default('ES');
            $table->integer('orden')->default(0);

            // Foreign key relationships
            $table->foreign('id_web_newbanner')->references('id')->on('web_newbanner')->onDelete('cascade');

            // Create indexes
            $table->index(['id_web_newbanner', 'activo', 'lenguaje']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Order matters for foreign key constraints - drop tables in reverse order
        Schema::dropIfExists('web_newbanner_item');
        Schema::dropIfExists('web_newbanner');
        Schema::dropIfExists('web_newbanner_tipo');
    }
}
