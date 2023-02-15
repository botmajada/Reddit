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
        Schema::table('user', function (Blueprint $table) {

            $table->boolean('trusted')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user', function (Blueprint $table) {

            $table->dropColumn('trusted');
        });
    }
    // Ejecuta la migración con artisan migrate y comprueba con Tinker que se ha creado la nueva columna.
    // php artisan tinker
    // >>> App\Models\User::first()->trusted
    // => false
    // >>> App\Models\User::first()->trusted = true
    // => true
    // >>> App\Models\User::first()->save()

};
