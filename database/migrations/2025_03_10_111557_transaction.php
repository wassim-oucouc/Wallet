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
        schema::create('Transaction',function(Blueprint $table){
            $table->increments('id');
            $table->string('Nom');
            $table->Integer('Receiver_id');
            $table->Integer('Sender_id');
            $table->string('Montant');
            $table->string('Status');
            $table->foreign('Receiver_id')->references('id')->on('Utilisateur')->onDelete('cascade');
            $table->foreign('Sender_id')->references('id')->on('Utilisateur')->onDelete('cascade');
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
        //
    }
};
