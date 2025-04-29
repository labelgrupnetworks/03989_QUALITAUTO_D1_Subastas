<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebRedirectsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('web_redirect_lots', function (Blueprint $table) {
            $table->id('id_web_redirect_lots');
            $table->string('url_web_redirect_lots');
            $table->string('emp_web_redirect_lots');
            // Add additional fields as needed based on your application requirements
            // These are the minimal fields required based on the RoutingServiceProvider code
        });

		Schema::create('web_redirect_pages', function (Blueprint $table) {
            $table->id('id_web_redirect_pages');
            $table->string('url_web_redirect_pages');
            $table->string('emp_web_redirect_pages');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('web_redirect_lots');
		Schema::dropIfExists('web_redirect_pages');
    }
}
