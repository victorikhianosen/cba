<?php

namespace App\Providers;

use App\Models\AccountProduct;
use App\Models\InvestmentProduct;
use App\Models\LoanProduct;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Relation::enforceMorphMap([
            'account_product'    => AccountProduct::class,
            'loan_product'       => LoanProduct::class,
            'investment_product' => InvestmentProduct::class,
        ]);
    }
}
