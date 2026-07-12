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
        Schema::create('account_products', function (Blueprint $table) {
            $table->id();

            $table->foreignId('currency_id')
                ->constrained('currencies')
                ->restrictOnDelete();

            $table->string('name');

            $table->string('code')
                ->unique();

            $table->text('description')
                ->nullable();

            $table->decimal('interest_rate', 8, 4)
                ->default(0.0000);

            $table->enum('interest_type', [
                'flat',
                'daily',
                'tiered',
            ])->default('flat');

            $table->enum('interest_posting', [
                'daily',
                'weekly',
                'monthly',
                'quarterly',
                'annually',
            ])->default('monthly');

            $table->boolean('overdraft_allowed')
                ->default(false);

            $table->decimal('overdraft_limit', 18, 2)
                ->nullable();

            $table->decimal('transfer_fee', 18, 2)
                ->default(0.00);

            $table->decimal('maintenance_fee', 18, 2)
                ->default(0.00);

            $table->unsignedInteger('dormancy_days')
                ->default(365);

            $table->string('created_by')
                ->nullable();

            $table->string('approved_by')
                ->nullable();

            $table->timestamp('approved_at')
                ->nullable();

            $table->enum('status', [
                'pending',
                'active',
                'inactive',
            ])->default('pending');

            $table->timestamps();

            $table->index('currency_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_products');
    }
};
