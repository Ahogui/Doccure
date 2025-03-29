<?php
namespace App\Http\Controllers\Admin;

use App\Models\FinancialTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf; // Ajoutez cette ligne

class FinanceController extends Controller
{
    public function index(Request $request)
    {
        $query = FinancialTransaction::query();

        // Filtres
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('date_from')) {
            $query->where('transaction_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('transaction_date', '<=', $request->date_to);
        }

        $transactions = $query->orderBy('transaction_date', 'desc')->paginate(20);

        $incomeCategories = FinancialTransaction::incomeCategories();
        $expenseCategories = FinancialTransaction::expenseCategories();
        return view('admin.finances.index', [
            'transactions' => $transactions,
            'incomeCategories' => $incomeCategories,
            'expenseCategories' => $expenseCategories
        ]);
    }

    public function create()
    {
        $incomeCategories = FinancialTransaction::incomeCategories();
        $expenseCategories = FinancialTransaction::expenseCategories();
        return view('admin.finances.create', [
            'incomeCategories' => $incomeCategories,
            'expenseCategories' => $expenseCategories
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'transaction_date' => 'required|date',
            'type' => 'required|in:income,expense',
            'amount' => 'required|numeric|min:0',
            'category' => 'required|string',
            'description' => 'nullable|string',
            'reference' => 'nullable|string'
        ]);

        $validated['user_id'] = Auth::id();

        FinancialTransaction::create($validated);

        return redirect()->route('finances.index')
            ->with('success', 'Transaction enregistrée avec succès');
    }

    public function show(FinancialTransaction $finance)
    {
        return view('admin.finances.show', compact('finance'));
    }

    public function edit(FinancialTransaction $finance)
    {
        $incomeCategories = FinancialTransaction::incomeCategories();
        $expenseCategories = FinancialTransaction::expenseCategories();
        return view('admin.finances.edit', [
            'transaction' => $finance,
            'incomeCategories' => $incomeCategories,
            'expenseCategories' => $expenseCategories
        ]);
    }

    public function update(Request $request, FinancialTransaction $finance)
    {
        $validated = $request->validate([
            'transaction_date' => 'required|date',
            'type' => 'required|in:income,expense',
            'amount' => 'required|numeric|min:0',
            'category' => 'required|string',
            'description' => 'nullable|string',
            'reference' => 'nullable|string'
        ]);

        $finance->update($validated);

        return redirect()->route('finances.index')
            ->with('success', 'Transaction mise à jour avec succès');
    }

    public function destroy(FinancialTransaction $finance)
    {
        $finance->delete();

        return redirect()->route('finances.index')
            ->with('success', 'Transaction supprimée avec succès');
    }

    public function dashboard()
    {
        // Statistiques pour le tableau de bord
        $monthlyIncome = FinancialTransaction::where('type', 'income')
            ->whereMonth('transaction_date', now()->month)
            ->sum('amount');

        $monthlyExpenses = FinancialTransaction::where('type', 'expense')
            ->whereMonth('transaction_date', now()->month)
            ->sum('amount');

        $recentTransactions = FinancialTransaction::latest()
            ->take(5)
            ->get();

        $topExpenseCategories = FinancialTransaction::where('type', 'expense')
            ->selectRaw('category, sum(amount) as total')
            ->groupBy('category')
            ->orderBy('total', 'desc')
            ->take(5)
            ->get();

        return view('admin.finances.dashboard', compact(
            'monthlyIncome',
            'monthlyExpenses',
            'recentTransactions',
            'topExpenseCategories'
        ));
    }
    public function generateReceipt($id)
    {
        $transaction = FinancialTransaction::findOrFail($id);

        // Pour PDF
        if(request()->has('pdf')) {
            $pdf = PDF::loadView('admin.finances.receipt-pdf', [
                'data' => $transaction->generateReceipt()
            ]);
            return $pdf->download('recu-'.$transaction->id.'.pdf');
        }

        // Pour vue HTML
        return view('admin.finances.receipt', [
            'data' => $transaction->generateReceipt(),
            'id' => $id
        ]);

    }
}