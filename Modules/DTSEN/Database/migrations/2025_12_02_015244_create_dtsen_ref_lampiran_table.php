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
            $table->integer('id_dtks');
            $table->integer('id_lampiran');
            $table->integer('config_id');

            // Index sesuai dump
            $table->index('id_dtks', 'FK_ref_lampiran_dtks');
            $table->index('id_lampiran', 'FK_lampiran_dtks');
            $table->index('config_id', 'dtks_ref_lampiran_config_id_foreign');
        });

        // Foreign key
        // Schema::table('dtsen_ref_lampiran', function (Blueprint $table) {

        //     $table->foreign('id_lampiran', 'FK_lampiran_dtks')
        //           ->references('id')->on('dtks_lampiran')
        //           ->onDelete('cascade')
        //           ->onUpdate('cascade');

        //     $table->foreign('id_dtks', 'FK_ref_lampiran_dtks')
        //           ->references('id')->on('dtks')
        //           ->onDelete('cascade')
        //           ->onUpdate('cascade');

        //     $table->foreign('config_id', 'dtks_ref_lampiran_config_id_foreign')
        //           ->references('id')->on('config')
        //           ->onDelete('cascade')
        //           ->onUpdate('cascade');
        // });
    }

    public function down()
    {
        // Schema::table('dtsen_ref_lampiran', function (Blueprint $table) {
        //     $table->dropForeign('FK_lampiran_dtks');
        //     $table->dropForeign('FK_ref_lampiran_dtks');
        //     $table->dropForeign('dtks_ref_lampiran_config_id_foreign');
        // });

        Schema::dropIfExists('dtsen_ref_lampiran');
    }
};
