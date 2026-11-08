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
        if (! Schema::hasTable('csirt')) {
            Schema::create('csirt', function (Blueprint $table) {
                $table->increments('id');
                $table->string('judul', 200);
                $table->text('deskripsi');
                $table->string('kategori', 80);
                $table->string('status', 20)->default('baru');
                $table->string('thumbnail', 255)->nullable();
                $table->unsignedInteger('created_by')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('csirt');
    }
};
