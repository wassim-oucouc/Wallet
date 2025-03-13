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
        Schema::create('Wallet', function (Blueprint $table){
            $table->increments('id');
            $table->Integer('NumeroWallet');
            $table->Integer('Solde');
            $table->string('Currency');
            $table->integer('owner_id');
            $table->foreign('owner_id')->references('id')->on('Utilisateur')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
