<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameJenisIdOnKertasKerjaBelanjaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kertas_kerja_belanja', function (Blueprint $table) {
            $table->renameColumn('jenis_id', 'rincian_obyek_id');
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
            $table->renameColumn('rincian_obyek_id', 'jenis_id');
        });
    }
}
