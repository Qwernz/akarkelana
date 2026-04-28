<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            // Mengubah kolom lama menjadi nullable agar tidak error saat create produk
            $table->decimal('price', 12, 2)->nullable()->change();
            $table->integer('stock')->nullable()->change();
            $table->integer('weight')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('price', 12, 2)->nullable(false)->change();
            $table->integer('stock')->nullable(false)->change();
            $table->integer('weight')->nullable(false)->change();
        });
    }
};
