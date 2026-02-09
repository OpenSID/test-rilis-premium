<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('dtsen_pengaturan_program', function (Blueprint $table) {
            $table->id();
            $table->integer('config_id');
            $table->integer('versi_kuisioner');
            $table->string('kode', 25);
            $table->integer('id_bantuan')->nullable();
            $table->string('nilai_default', 50)->nullable();
            $table->string('target_table', 100);
            $table->text('target_field');
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent()->useCurrentOnUpdate();

            // Unique key
            $table->unique(['config_id', 'versi_kuisioner', 'kode'], 'config_idversi_kuisionerkode');

            // Index
            $table->index('id_bantuan', 'FK_dtsen_p_program');
        });

        // Foreign keys
        Schema::table('dtsen_pengaturan_program', function (Blueprint $table) {

            $table->foreign('id_bantuan', 'FK_dtsen_p_program')
                  ->references('id')->on('program')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->foreign('config_id', 'dtsen_pengaturan_program_config_fk')
                  ->references('id')->on('config')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::table('dtsen_pengaturan_program', function (Blueprint $table) {
            $table->dropForeign('FK_dtsen_p_program');
            $table->dropForeign('dtsen_pengaturan_program_config_fk');
        });

        Schema::dropIfExists('dtsen_pengaturan_program');
    }
};
