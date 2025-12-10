<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('dtsen_lampiran', function (Blueprint $table) {
            $table->id();
            $table->integer('config_id');
            $table->integer('id_rtm')->nullable();
            $table->string('judul', 30);
            $table->string('keterangan', 100);
            $table->text('foto');
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent()->useCurrentOnUpdate();

            // Indexes
            $table->index('id_rtm', 'FK_dtks_lampiran_rtm');
            $table->index('config_id', 'dtks_lampiran_config_fk');
        });
        
        // Foreign Keys
        // Schema::table('dtsen_lampiran', function (Blueprint $table) {
        //     $table->foreign('id_rtm', 'FK_dtks_lampiran_rtm')
        //           ->references('id')->on('tweb_rtm')
        //           ->onDelete('set null')
        //           ->onUpdate('cascade');

        //     $table->foreign('config_id', 'dtks_lampiran_config_fk')
        //           ->references('id')->on('config')
        //           ->onDelete('cascade')
        //           ->onUpdate('cascade');
        // });
    }

    public function down()
    {
        // Schema::table('dtsen_lampiran', function (Blueprint $table) {
        //     $table->dropForeign('FK_dtks_lampiran_rtm');
        //     $table->dropForeign('dtks_lampiran_config_fk');
        // });

        Schema::dropIfExists('dtsen_lampiran');
    }
};
