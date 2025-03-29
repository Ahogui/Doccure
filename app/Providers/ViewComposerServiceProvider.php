<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\View;

class ViewComposerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        View::composer('admin.finances.*', function ($view) {
            $view->with([
                'incomeCategories' => [
                    'Vente médicaments',
                    'Vente produits para-pharmaceutiques',
                    'Services',
                    'Remboursements',
                    'Autres revenus'
                ],
                'expenseCategories' => [
                    'Achats médicaments',
                    'Achats matériel',
                    'Salaires',
                    'Loyer',
                    'Factures (électricité, eau)',
                    'Maintenance',
                    'Fournitures bureau',
                    'Autres dépenses'
                ]
            ]);
        });
    }
}