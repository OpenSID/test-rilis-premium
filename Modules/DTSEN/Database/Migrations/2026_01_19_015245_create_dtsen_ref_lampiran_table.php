<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('dtsen_ref_lampiran')) {
            return;
        }

        Schema::create('dtsen_ref_lampiran', function (Blueprint $table) {
            $table->unsignedBigInteger('id_lampiran'); 
            $table->integer('id_dtsen');               
            $table->integer('config_id');              

            $table->index('id_dtsen', 'FK_ref_lampiran_dtsen');
            $table->index('id_lampiran', 'FK_lampiran_dtsen');
            $table->index('config_id', 'dtsen_ref_lampiran_config_id_foreign');
        });


        // Foreign key
        Schema::table('dtsen_ref_lampiran', function (Blueprint $table) {

            $table->foreign('id_lampiran', 'FK_lampiran_dtsen')
                  ->references('id')->on('dtsen_lampiran')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->foreign('id_dtsen', 'FK_ref_lampiran_dtsen')
                  ->references('id')->on('dtsen')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->foreign('config_id', 'dtsen_ref_lampiran_config_id_foreign')
                  ->references('id')->on('config')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::table('dtsen_ref_lampiran', function (Blueprint $table) {
            $table->dropForeign('FK_lampiran_dtsen');
            $table->dropForeign('FK_ref_lampiran_dtsen');
            $table->dropForeign('dtsen_ref_lampiran_config_id_foreign');
        });

        Schema::dropIfExists('dtsen_ref_lampiran');
    }
};
