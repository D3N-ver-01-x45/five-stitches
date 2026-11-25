<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('category', 20)->index(); // bags | pouches | keychains | flowers
            $table->string('name');
            $table->string('slug')->unique();        // used in the URL: /products/tote-bag
            $table->text('description');
            $table->text('options')->nullable();     // "You can choose" box
            $table->decimal('base_price', 10, 2);    // shown as "from ₱250"
            $table->unsignedTinyInteger('ready_min_days');
            $table->unsignedTinyInteger('ready_max_days');
            $table->string('image')->nullable();     // path under public/, e.g. images/products/tote-bag.webp
            $table->boolean('is_active')->default(true)->index();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
