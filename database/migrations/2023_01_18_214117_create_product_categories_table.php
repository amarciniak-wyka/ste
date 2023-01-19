<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_categories', function (Blueprint $table) {
            $table->id();
            $table->string(column: 'name', length: 255);
            $table->timestamps();
        });
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger(column: 'category_id')->nullable()->after(column: 'price');
            $table->foreign(columns: 'category_id')->references(columns: 'id')->on(table: 'product_categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(index: 'products_category_id_foreign');
            $table->dropColumn(columns: 'category_id');
        });
        Schema::dropIfExists('product_categories');
    }
};
