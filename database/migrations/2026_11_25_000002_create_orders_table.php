<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number', 20)->nullable()->unique(); // FS-001, set after insert
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // buyer account, if any
            $table->string('customer_name');
            $table->string('customer_contact'); // Facebook name or mobile number
            $table->string('release_method', 20); // pickup | meetup | delivery
            $table->string('delivery_address')->nullable();
            $table->date('date_needed')->nullable();
            $table->string('payment_method', 20); // gcash | cash
            $table->boolean('down_payment_paid')->default(false);
            $table->string('status', 30)->default('new')->index();
            $table->text('notes')->nullable();
            $table->decimal('total', 10, 2)->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index('created_at');
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('product_name'); // copied at order time, so later price/name edits don't change old orders
            $table->string('color')->nullable();
            $table->unsignedInteger('quantity');
            $table->decimal('unit_price', 10, 2);
            $table->string('details')->nullable(); // size, add-ons, initials…
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};
