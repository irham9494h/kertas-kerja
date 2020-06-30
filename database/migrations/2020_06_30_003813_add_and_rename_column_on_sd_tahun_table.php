<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAndRenameColumnOnSdTahunTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sd_tahun', function (Blueprint $table) {
            $table->renameColumn('status', 'status_murni');
            $table->tinyInteger('status_perubahan')->after('status')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sd_tahun', function (Blueprint $table) {
            $table->renameColumn('status_murni', 'status');
            $table->dropColumn('status_perubahan');
        });
    }
}
