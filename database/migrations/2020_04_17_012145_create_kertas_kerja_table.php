<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKertasKerjaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kertas_kerja', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('sd_tanggal_id');
            $table->bigInteger('unit_id');
            $table->bigInteger('jenis_id');
            $table->string('uraian');
            $table->double('nilai');
            $table->enum('jenis', ['pendapatan', 'belanja', 'pembiayaan'])->nullable();
            $table->bigInteger('sumber_dana_id')->nullable();
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
        Schema::dropIfExists('kertas_kerja');
    }
}
