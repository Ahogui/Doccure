<?php

namespace App\Http\Controllers\Admin;

use App\Models\Sale;
use App\Models\Category;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use App\Models\ExamType;
use App\Models\FinancialTransaction;

class DashboardController extends Controller
{
    public function index()
    {
        $title = 'dashboard';

        // Statistiques existantes
        $total_purchases = Purchase::where('expiry_date','!=',Carbon::now())->count();
        $total_categories = Category::count();
        $total_suppliers = Supplier::count();
        $total_sales = Sale::count();
        $total_expired_products = Purchase::whereDate('expiry_date', '=', Carbon::now())->count();
        $latest_sales = Sale::whereDate('created_at','=',Carbon::now())->get();
        $today_sales = Sale::whereDate('created_at','=',Carbon::now())->sum('total_price');

        // Nouvelles statistiques médicales
        $total_patients = Patient::count();
        $total_analyses = ExamType::count();
        $total_doctors = Doctor::count();
        $total_departments = Department::count();

        $pieChart = app()->chartjs
            ->name('pieChart')
            ->type('pie')
            ->size(['width' => 400, 'height' => 200])
            ->labels(['Patients', 'Analyses', 'Médecins', 'Départements'])
            ->datasets([
                [
                    'backgroundColor' => ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0'],
                    'hoverBackgroundColor' => ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0'],
                    'data' => [$total_patients, $total_analyses, $total_doctors, $total_departments]
                ]
            ])
            ->options([]);
            // Récupération des données financières
            $incomeTotal = FinancialTransaction::where('type', 'income')->sum('amount');
            $expenseTotal = FinancialTransaction::where('type', 'expense')->sum('amount');
            $balance = $incomeTotal - $expenseTotal;

            // Préparation du graphique financier
            $financeChart = app()->chartjs
                ->name('financeChart')
                ->type('pie')
                ->size(['width' => 300, 'height' => 200])
                ->labels(['Entrées', 'Dépenses'])
                ->datasets([
                    [
                        'backgroundColor' => ['#28a745', '#dc3545'],
                        'hoverBackgroundColor' => ['#218838', '#c82333'],
                        'data' => [$incomeTotal, $expenseTotal]
                    ]
                ])
                ->options([
                    'responsive' => true,
                    'plugins' => [
                        'title' => [
                            'display' => true,
                            'text' => 'Répartition financière',
                            'font' => [
                                'size' => 14
                            ]
                        ],
                        'tooltip' => [
                            'callbacks' => [
                                'label' => "function(context) {
                                    return context.label + ': ' + context.formattedValue + ' FCFA';
                                }"
                            ]
                        ]
                    ]
                ]);

        return view('admin.dashboard', compact(
            'title', 'pieChart', 'total_expired_products',
            'latest_sales', 'today_sales', 'total_categories',
            'total_patients', 'total_analyses', 'total_doctors', 'total_departments',
            'financeChart', 'incomeTotal', 'expenseTotal', 'balance'
        ));
    }
}
