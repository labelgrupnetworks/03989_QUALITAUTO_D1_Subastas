<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewslettersTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Create newsletter categories table
        Schema::create('fx_newsletter', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_newsletter');
            $table->string('gemp_newsletter', 10);
            $table->string('name_newsletter', 100);
            $table->string('lang_newsletter', 5);
            $table->text('description_newsletter')->nullable();

            // Create composite unique key
            $table->unique(['id_newsletter', 'gemp_newsletter', 'lang_newsletter'], 'unique_newsletter');
        });

        // Create newsletter subscriptions table
        Schema::create('fx_newsletter_suscription', function (Blueprint $table) {
            $table->increments('id_newsletter_suscription');
            $table->string('gemp_newsletter_suscription', 10);
            $table->string('emp_newsletter_suscription', 10);
            $table->integer('id_newsletter');
            $table->string('email_newsletter_suscription', 100);
            $table->string('lang_newsletter_suscription', 5);
            $table->timestamp('create_newsletter_suscription')->useCurrent();

            // Create indexes
            $table->index(['gemp_newsletter_suscription', 'email_newsletter_suscription'], 'idx_email_newsletter');
            $table->index(['id_newsletter', 'gemp_newsletter_suscription'], 'idx_newsletter_relation');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fx_newsletter_suscription');
        Schema::dropIfExists('fx_newsletter');
    }
}
