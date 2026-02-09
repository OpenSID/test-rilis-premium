<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('dtsen_lampiran')) {
            return;
        }

        Schema::create('dtsen_lampiran', function (Blueprint $table) {
            $table->id();
            $table->integer('config_id');
            $table->integer('id_rtm')->nullable();
            $table->integer('id_keluarga')->nullable();
            $table->string('judul', 30);
            $table->string('keterangan', 100);
            $table->text('foto')->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent()->useCurrentOnUpdate();

            // Indexes
            $table->index('id_keluarga', 'FK_dtsen_lampiran_keluarga');
            $table->index('config_id', 'dtsen_lampiran_config_fk');
        });
        
        // Foreign Keys
        Schema::table('dtsen_lampiran', function (Blueprint $table) {
            $table->foreign('id_keluarga', 'FK_dtsen_lampiran_keluarga')
                  ->references('id')->on('tweb_keluarga')
                  ->onDelete('set null')
                  ->onUpdate('cascade');

            $table->foreign('config_id', 'dtsen_lampiran_config_fk')
                  ->references('id')->on('config')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });

        DB::table('dtsen_lampiran')->insert([
            'config_id'   => identitas('id'),
            'judul'       => 'Dokumen',
            'keterangan'  => 'Dokumen identitas',
        ]);
    }

    public function down()
    {
        Schema::table('dtsen_lampiran', function (Blueprint $table) {
            $table->dropForeign('FK_dtsen_lampiran_keluarga');
            $table->dropForeign('dtsen_lampiran_config_fk');
        });

        Schema::dropIfExists('dtsen_lampiran');
    }
};
