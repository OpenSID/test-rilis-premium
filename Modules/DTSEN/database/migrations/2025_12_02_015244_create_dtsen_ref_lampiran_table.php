<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('dtsen_ref_lampiran', function (Blueprint $table) {

            // Kolom tanpa primary key
            $table->integer('id_dtsen');
            $table->integer('id_lampiran');
            $table->integer('config_id');

            // Index sesuai dump
            // $table->index('id_dtsen', 'FK_ref_lampiran_dtsen');
            // $table->index('id_lampiran', 'FK_lampiran_dtsen');
            // $table->index('config_id', 'dtsen_ref_lampiran_config_id_foreign');
        });

        // Foreign key
        // Schema::table('dtsen_ref_lampiran', function (Blueprint $table) {

        //     $table->foreign('id_lampiran', 'FK_lampiran_dtsen')
        //           ->references('id')->on('dtsen_lampiran')
        //           ->onDelete('cascade')
        //           ->onUpdate('cascade');

        //     $table->foreign('id_dtsen', 'FK_ref_lampiran_dtsen')
        //           ->references('id')->on('dtsen')
        //           ->onDelete('cascade')
        //           ->onUpdate('cascade');

        //     $table->foreign('config_id', 'dtsen_ref_lampiran_config_id_foreign')
        //           ->references('id')->on('config')
        //           ->onDelete('cascade')
        //           ->onUpdate('cascade');
        // });
    }

    public function down()
    {
        // Schema::table('dtsen_ref_lampiran', function (Blueprint $table) {
        //     $table->dropForeign('FK_lampiran_dtsen');
        //     $table->dropForeign('FK_ref_lampiran_dtsen');
        //     $table->dropForeign('dtsen_ref_lampiran_config_id_foreign');
        // });

        Schema::dropIfExists('dtsen_ref_lampiran');
    }
};
