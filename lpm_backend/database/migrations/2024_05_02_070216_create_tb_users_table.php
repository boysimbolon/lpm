<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tb_fakultas', function (Blueprint $table) {
            $table->id();
            $table->string('fakultas');
            $table->string('prodi');
            $table->timestamps();
        });
        Schema::create('tb_tipe_audits', function (Blueprint $table) {
            $table->id();
            $table->string('tipe_audit');
            $table->string('periode');
            $table->string('semester');
            $table->unsignedBigInteger('id_fakultas')->nullable(); // Ganti dengan nama kolom yang sesuai
            $table->foreign('id_fakultas')->references('id')->on('tb_fakultas')->onDelete('set null');
            $table->json("idLPM")->nullable();
            $table->timestamps();
        });
        Schema::create('tb_standars', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_standar',50);
            $table->timestamps();
        });
        Schema::create('tb_roles', function (Blueprint $table) {
            $table->id();
            $table->enum('jenis',['admin','auditee','auditor']);
            $table->timestamps();
        });

        Schema::create('tb_users', function (Blueprint $table) {
            $table->id();
            $table->string('username',50);
            $table->string('password',250);
            $table->string('name',50);
            // Mengubah kolom menjadi nullable setelah kita menambahkan foreign key constraint baru
            $table->unsignedBigInteger('id_fakultas')->nullable();
            // Menghapus foreign key constraint lama (jika ada)
            // Menambahkan foreign key constraint pada kolom yang sudah ada
            $table->foreign('id_fakultas')
                ->references('id')->on('tb_fakultas')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreignId('id_roles');
            $table->foreign('id_roles')->references('id')->on('tb_roles')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
       });
        Schema::create('tb_per_standars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_standars');
            $table->foreign('id_standars')->references('id')->on('tb_standars')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('id_tipe_audits');
            $table->foreign('id_tipe_audits')->references('id')->on('tb_tipe_audits')->onDelete('No Action')->onUpdate('No Action');
            $table->text('pernyataan_standar');
            $table->float('poin');
            $table->timestamps();
        });
        Schema::create('tb_audits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_per_standars')->constrained('tb_per_standars')->onDelete('cascade');
            $table->foreignId('id_users')->constrained('tb_users')->onDelete('cascade');
            $table->foreignId('id_tipe_audits')->constrained('tb_tipe_audits')->onDelete('cascade');
            $table->string('nilai_diri')->default('0');
            $table->json('dok_pendukung')->nullable();
            $table->boolean('progress')->default('0');
            $table->text('catatan')->nullable();
            $table->text('feedback')->nullable();
            $table->timestamps();
        });

    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_users');
        Schema::dropIfExists('tb_per_standars');
        Schema::dropIfExists('tb_audits');
        Schema::dropIfExists('tb_tipe_audits');
        Schema::dropIfExists('tb_standars');
        Schema::dropIfExists('tb_roles');
        Schema::dropIfExists('tb_fakultas');
    }
};
