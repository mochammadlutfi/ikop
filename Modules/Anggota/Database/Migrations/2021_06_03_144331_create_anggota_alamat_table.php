<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnggotaAlamatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('anggota_alamat', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('anggota_id')->unsigned();
            $table->enum('domisili', ['1', '0']);
            $table->char('wilayah_id', 13);
            $table->string('pos', 20);
            $table->text('alamat');
            $table->timestamps();

            
            $table->foreign('anggota_id')->references('id')->on('cabang')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('anggota_alamat');
    }
}
