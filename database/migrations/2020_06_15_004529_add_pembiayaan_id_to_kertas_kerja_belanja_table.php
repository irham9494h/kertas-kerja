<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPembiayaanIdToKertasKerjaBelanjaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kertas_kerja_belanja', function (Blueprint $table) {
            $table->bigInteger('pembiayaan_id')->nullable()->after('pendapatan_id');
            $table->float('nilai_pembiayaan', 15,2)->nullable()->after('pembiayaan_id');
            $table->bigInteger('pendapatan_id')->nullable()->change();
            $table->float('nilai')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kertas_kerja_belanja', function (Blueprint $table) {
            $table->dropColumn(['pembiayaan_id', 'nilai_pembiayaan']);
            $table->bigInteger('pendapatan_id')->change();
            $table->float('nilai')->change();
        });
    }
}
