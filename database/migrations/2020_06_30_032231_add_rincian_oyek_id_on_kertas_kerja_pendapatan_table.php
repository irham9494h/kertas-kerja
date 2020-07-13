<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRincianOyekIdOnKertasKerjaPendapatanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kertas_kerja_pendapatan', function (Blueprint $table) {
            $table->bigInteger('rincian_obyek_id')->after('unit_id');
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
            $table->dropColumn('rincian_obyek_id');
        });
    }
}
