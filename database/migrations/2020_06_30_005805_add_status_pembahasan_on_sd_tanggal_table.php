<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusPembahasanOnSdTanggalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sd_tanggal', function (Blueprint $table) {
            $table->enum('jenis_pembahasan', ['struktur_murni', 'struktur_perubahan'])->after('sd_tahun_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sd_tanggal', function (Blueprint $table) {
            $table->dropColumn('jenis_pembahasan');
        });
    }
}
