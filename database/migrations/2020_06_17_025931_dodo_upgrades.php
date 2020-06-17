<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DodoUpgrades extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('to_dos', function(Blueprint $table) {
            $table->string('label', 100)->change();
            $table->string('details', 150)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('to_dos', function(Blueprint $table) {
            $table->string('label', 20)->change();
            $table->string('details', 20)->change();
        });
    }
}
