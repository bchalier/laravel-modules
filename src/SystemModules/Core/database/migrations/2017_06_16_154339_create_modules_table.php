<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modules', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 20)->unique();
            $table->string('alias', 20)->unique();
            $table->text('keywords'); // TODO json
            $table->string('path', 150)->unique();
            $table->text('loadParameters')->nullable(); // TODO json
            $table->text('providers')->nullable(); // TODO json
            $table->text('aliases')->nullable(); // TODO json
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('modules');
    }
}
