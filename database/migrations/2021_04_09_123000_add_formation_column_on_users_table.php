<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFormationColumnOnUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('formation_id')->nullable();
            $table->foreign('formation_id')->references('id')->on('formations')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('formation_id');
            $table->dropColumn('formation_id');
        });
    }
    // jai dit quil a ete cree a 12#30, et l'autre a 12#40
    // on va faire un test, on va deplace ce fichier avant la reation de la table formations, tu vas voir la meme erreur que precedemment
}
