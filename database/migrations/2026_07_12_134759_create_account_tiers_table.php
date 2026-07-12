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
        Schema::create('account_tiers', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('code')->unique();
            $table->unsignedTinyInteger('level')->unique();
            $table->text('description')->nullable();

            $table->decimal('minimum_balance', 18, 4)->default(0.0000);
            $table->decimal('maximum_balance', 18, 4)->nullable();

            $table->decimal('maximum_single_transaction', 18, 4)->nullable();
            $table->decimal('daily_transaction_limit', 18, 4)->nullable();
            $table->decimal('daily_withdrawal_limit', 18, 4)->nullable();
            $table->unsignedInteger('maximum_transactions_per_day')->nullable();

            $table->boolean('bvn_required')->default(false);
            $table->boolean('nin_required')->default(false);
            $table->boolean('valid_id_required')->default(false);
            $table->boolean('address_verification_required')->default(false);

            $table->string('created_by')->nullable();
            $table->string('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();

            $table->enum('status', [
                'pending',
                'active',
                'inactive',
            ])->default('pending');

            $table->timestamps();

            $table->index('level');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_tiers');
    }
};
