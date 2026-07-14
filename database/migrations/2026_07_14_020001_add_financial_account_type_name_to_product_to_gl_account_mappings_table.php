<?php

use App\Enums\LoanProductFinancialAccountType;
use App\Enums\SavingsProductFinancialAccountType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_to_gl_account_mappings', function (Blueprint $table) {
            $table->string('financial_account_type_name')->nullable()->after('financial_account_type');
        });

        foreach (DB::table('product_to_gl_account_mappings')->get() as $row) {
            $enumClass = $row->product_type === 'loan_product'
                ? LoanProductFinancialAccountType::class
                : SavingsProductFinancialAccountType::class;

            DB::table('product_to_gl_account_mappings')
                ->where('id', $row->id)
                ->update(['financial_account_type_name' => $enumClass::from($row->financial_account_type)->name]);
        }

        Schema::table('product_to_gl_account_mappings', function (Blueprint $table) {
            $table->string('financial_account_type_name')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('product_to_gl_account_mappings', function (Blueprint $table) {
            $table->dropColumn('financial_account_type_name');
        });
    }
};
