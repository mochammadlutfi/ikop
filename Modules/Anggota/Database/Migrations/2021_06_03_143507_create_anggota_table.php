<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnggotaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('anggota', function (Blueprint $table) {
            $table->bigInteger('anggota_id')->unique()->primary()->unsigned();
            $table->string('nik', 30);
            $table->string('nama');
            $table->enum('jk', ['L', 'P']);
            $table->string('tmp_lahir');
            $table->date('tgl_lahir');
            $table->string('no_hp', 20)->unique()->nullable();
            $table->string('no_telp', 20)->unique()->nullable();
            $table->string('email')->unique()->nullable();
            $table->enum('status_pernikahan', ['Lajang', 'Menikah', 'Duda/Janda']);
            $table->string('pendidikan');
            $table->string('pekerjaan');
            $table->string('nama_ibu');
            $table->string('ktp');
            $table->string('foto');
            $table->bigInteger('cabang_id')->unsigned();
            $table->timestamps();
            
            $table->foreign('cabang_id')->references('id')->on('cabang')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('anggota');
    }
}
