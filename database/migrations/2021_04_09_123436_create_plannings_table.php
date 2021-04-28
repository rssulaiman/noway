<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlanningsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    { // du prof ? Yep
        Schema::create('plannings', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date_debut');
            $table->dateTime('date_fin');
            $table->timestamps();
            $table->foreignId('cours_id');
            $table->foreign('cours_id')->references('id')->on('cours');;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plannings');
    }
}
