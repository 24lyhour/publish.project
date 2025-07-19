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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 8, 2);
            $table->string('imageUrl')->nullable();
            $table->integer('quantity')->default(0); // For inventory
            $table->string('status')->default('active');
            
            // --- This is the most important fix ---
            // This product belongs to a category. We use an ID, not a string.
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');

            // Your other fields from the original products table
            $table->string('size')->nullable();
            $table->text('ingredients')->nullable();
            $table->text('allergens')->nullable();
            $table->text('extras')->nullable();
            $table->string('type')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};