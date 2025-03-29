<?php

namespace App\Http\Controllers\Admin;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Client;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class InvoiceController extends Controller
{
    /**
     * Affiche la liste des factures
     */
    public function index()
    {
        return view('invoices.index', [
            'clients' => Client::all(),
            'products' => Product::all()
        ]);
    }

    /**
     * Stocke une nouvelle facture
     */
    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'invoice_date' => 'required|date',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'status' => 'required|in:paid,unpaid',
            'payment_method' => 'required|in:cash,card,transfer,check'
        ]);

        DB::beginTransaction();

        try {
            // Création de la facture
            $invoice = Invoice::create([
                'invoice_number' => Invoice::generateInvoiceNumber(),
                'client_id' => $request->client_id,
                'invoice_date' => $request->invoice_date,
                'grand_total' => 0, // Calculé plus bas
                'status' => $request->status,
                'payment_method' => $request->payment_method,
                'notes' => $request->notes
            ]);

            // Ajout des produits à la facture
            $grandTotal = 0;
            foreach ($request->products as $product) {
                $dbProduct = Product::find($product['product_id']);
                $total = $product['quantity'] * $dbProduct->price;

                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $product['product_id'],
                    'quantity' => $product['quantity'],
                    'unit_price' => $dbProduct->price,
                    'total' => $total
                ]);

                $grandTotal += $total;

                // Mise à jour du stock (optionnel)
                $dbProduct->decrement('quantity', $product['quantity']);
            }

            // Mise à jour du total de la facture
            $invoice->update(['grand_total' => $grandTotal]);

            DB::commit();

            return redirect()->route('invoices.index')
                ->with('success', 'Facture créée avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur lors de la création de la facture: ' . $e->getMessage());
        }
    }

    /**
     * Affiche une facture spécifique
     */
    public function show(Invoice $invoice)
    {
        $invoice->load('client', 'items.product');
        return response()->json($invoice);
    }

    /**
     * Met à jour une facture
     */
    public function update(Request $request, Invoice $invoice)
    {
        $request->validate([
            'status' => 'required|in:paid,unpaid,cancelled'
        ]);

        $invoice->update($request->only('status'));

        return back()->with('success', 'Statut de la facture mis à jour.');
    }

    /**
     * Supprime une facture
     */
    public function destroy(Invoice $invoice)
    {
        DB::beginTransaction();

        try {
            // Restauration du stock (optionnel)
            foreach ($invoice->items as $item) {
                $item->product->increment('quantity', $item->quantity);
            }

            $invoice->items()->delete();
            $invoice->delete();

            DB::commit();

            return redirect()->route('invoices.index')
                ->with('success', 'Facture supprimée avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur lors de la suppression: ' . $e->getMessage());
        }
    }

    /**
     * Récupère les données pour DataTables
     */
    public function getInvoices(Request $request)
    {
        $invoices = Invoice::with('client')
            ->select('invoices.*')
            ->latest();

        return datatables()->of($invoices)
            ->addColumn('action', function ($invoice) {
                return '
                    <div class="actions text-center">
                        <a class="btn btn-sm bg-info-light viewbtn" href="#" data-id="'.$invoice->id.'">
                            <i class="fe fe-eye"></i> Voir
                        </a>
                        <a class="btn btn-sm bg-success-light editbtn" href="#" data-id="'.$invoice->id.'">
                            <i class="fe fe-pencil"></i> Editer
                        </a>
                        <a class="btn btn-sm bg-danger-light deletebtn" href="#" data-id="'.$invoice->id.'">
                            <i class="fe fe-trash"></i> Supprimer
                        </a>
                    </div>
                ';
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }
}