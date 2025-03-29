<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\Schema;

use App\Models\FinancialTransaction;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        \Illuminate\Support\Facades\DB::statement('SET NAMES utf8');
        // View::composer(['admin.finances.*'], function ($view) {
        //     $view->with([
        //         'incomeCategories' => FinancialTransaction::incomeCategories(),
        //         'expenseCategories' => FinancialTransaction::expenseCategories()
        //     ]);
        // });
    }
}