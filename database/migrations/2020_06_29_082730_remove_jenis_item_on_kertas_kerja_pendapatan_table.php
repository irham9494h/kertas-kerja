<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveJenisItemOnKertasKerjaPendapatanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kertas_kerja_pendapatan', function (Blueprint $table) {
            $table->dropColumn('jenis');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kertas_kerja_pendapatan', function (Blueprint $table) {
            $table->enum('jenis', ['pendapatan', 'belanja', 'pembiayaan'])->nullable();
        });
    }
}
