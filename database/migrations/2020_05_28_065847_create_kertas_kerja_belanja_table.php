<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKertasKerjaBelanjaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kertas_kerja_belanja', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('sd_tanggal_id');
            $table->bigInteger('unit_id');
            $table->bigInteger('jenis_id');
            $table->bigInteger('pendapatan_id');
            $table->string('uraian');
            $table->double('nilai');
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
        Schema::dropIfExists('kertas_kerja_belanja');
    }
}
