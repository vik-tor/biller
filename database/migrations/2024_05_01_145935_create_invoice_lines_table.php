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
        Schema::create('invoice_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')
                ->constrained(table: 'companies')->cascadeOnDelete();
            $table->foreignId('invoice_id')->constrained(table: 'invoices');
            $table->string('particulars')->nullable();
            $table->decimal('unit_cost', 12, 2)->nullable();
            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('subtotal', 12, 2);
            $table->decimal('tax', 12, 2)->nullable();
            $table->decimal('total', 12, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_lines');
    }
};
