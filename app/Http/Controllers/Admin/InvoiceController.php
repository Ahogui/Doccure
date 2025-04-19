<?php

namespace App\Http\Controllers\Admin;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Patient;
use App\Models\Product;
use App\Models\ExamType;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Purchase;
use PDF;

class InvoiceController extends Controller
{
     public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Invoice::with('patient')->latest()->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('invoice_number', function($row){
                    return 'FACT-'.str_pad($row->id, 6, '0', STR_PAD_LEFT);
                })
                ->addColumn('patient.name', function($row){
                    return $row->patient->full_name;
                })
                ->addColumn('invoice_date', function($row){
                    return $row->invoice_date->format('d/m/Y');
                })
                ->addColumn('grand_total', function($row){
                    return number_format($row->total_amount, 2);
                })
                ->addColumn('status', function($row){
                    return $row->status;
                })
                ->addColumn('action', function($row){
                    $btn = '<a href="'.route('invoices.show', $row->id).'" class="btn btn-sm btn-info viewbtn" data-id="'.$row->id.'">Voir</a>';
                    $btn .= ' <a href="javascript:void(0)" class="btn btn-sm btn-primary editbtn" data-id="'.$row->id.'">Editer</a>';
                    $btn .= ' <form action="'.route("invoices.destroy", $row->id).'" method="POST" style="display:inline">
                                '.csrf_field().'
                                '.method_field("DELETE").'
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm(\'Confirmer la suppression?\')">Supprimer</button>
                            </form>';
                    return $btn;
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }

        $patients = Patient::all();
        $products = Product::all();
         $exams = ExamType::all();

        return view('invoices.index', compact('patients', 'products', 'exams'));
    }
    public function create1($patientId)
    {
        $patient = Patient::with('currentDepartment')->findOrFail($patientId);

        return view('invoices.create', [
            'patient' => $patient,
            'products' => Product::where('quantity', '>', 0)->get(),
            'exams' => ExamType::active()->get(),
            'departments' => Department::with('headDoctor')->get(),
            'defaultDepartment' => $patient->currentDepartment
        ]);
    }

    // Suppression
    public function destroy($id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->delete();

        return redirect()->route('invoices.index')
            ->with('success', 'Facture supprimée avec succès');
    }

    public function create(Patient $patient)
    {
        $products = Product::with('category')->get();
        $exams = ExamType::all();
        $departments = Department::with('headDoctor')->get();

        return view('invoices.create', compact('patient', 'products', 'exams', 'departments'));
    }
    public function store(Request $request)
    {
        // Normaliser les données si elles arrivent sous forme products/exams
        if ($request->has('products') || $request->has('exams')) {
            $items = [];

            // Convertir les produits
            if ($request->has('products')) {
                foreach ($request->products as $product) {
                    if (!empty($product['product_id'])) {
                        $items[] = [
                            'type' => 'product',
                            'id' => $product['product_id'],
                            'quantity' => $product['quantity']
                        ];
                    }
                }
            }

            // Convertir les examens
            if ($request->has('exams')) {
                foreach ($request->exams as $exam) {
                    if (!empty($exam['exam_id'])) {
                        $items[] = [
                            'type' => 'exam',
                            'id' => $exam['exam_id'],
                            'quantity' => 1
                        ];
                    }
                }
            }

            $request->merge(['items' => $items]);
        }
        // Validation de base
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'invoice_date' => 'required|date',
            'payment_method' => 'required|in:cash,card,transfer,check',
            'items' => 'required|array|min:1',
            'items.*.type' => 'required|in:product,exam',
            'items.*.id' => 'required|numeric',
            'items.*.quantity' => 'required|numeric|min:1'
        ]);

        // Vérification approfondie des items
        $products = [];
        $exams = [];
        $totalAmount = 0;

        foreach ($request->items as $item) {
            if ($item['type'] === 'product') {
                $product = Product::findOrFail($item['id']);
                if ($product->purchase->quantity < $item['quantity']) {
                    return back()->withErrors(['items' => 'Stock insuffisant pour '.$product->purchase->product]);
                }
                $products[] = [
                    'id' => $product->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $product->price,
                    'total' => $product->price * $item['quantity'],
                    'purchase_id' => $product->purchase_id
                ];
                $totalAmount += $product->price * $item['quantity'];
            }
            elseif ($item['type'] === 'exam') {
                $exam = ExamType::findOrFail($item['id']);
                $exams[] = [
                    'id' => $exam->id,
                    'price' => $exam->price
                ];
                $totalAmount += $exam->price;
            }
        }

        // Création de la facture
        $invoice = Invoice::create([
            'invoice_number' => 'INV-'.date('Ymd').'-'.strtoupper(uniqid()),
            'patient_id' => $request->patient_id,
            'invoice_date' => $request->invoice_date,
            'total_amount' => $totalAmount,
            'grand_total' => $totalAmount,
            'status' => 'paid',
            'payment_method' => $request->payment_method
        ]);

        // Attachement des produits
        foreach ($products as $product) {
            $invoice->products()->attach($product['id'], [
                'quantity' => $product['quantity'],
                'unit_price' => $product['unit_price'],
                'total' => $product['total']
            ]);
            // Mise à jour du stock
            Purchase::where('id', $product['purchase_id'])
                   ->decrement('quantity', $product['quantity']);
        }

        // Attachement des examens
        foreach ($exams as $exam) {
            $invoice->examTypes()->attach($exam['id'], [
                'price' => $exam['price']
            ]);
        }

        return redirect()->route('invoices.show', $invoice->id)
                       ->with('success', 'Facture créée avec succès');
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['patient', 'products', 'examTypes']);
        return view('invoices.show', compact('invoice'));
    }

    public function generateReceipt(Invoice $invoice)
    {
        $data = [
            'invoice' => $invoice,
            'patient' => $invoice->patient,
            'products' => $invoice->products,
            'exams' => $invoice->examTypes,
            'date' => now()->format('d/m/Y H:i')
        ];

        $pdf = PDF::loadView('receipts.invoice', $data);

        return $pdf;
    }

    public function downloadReceipt(Invoice $invoice)
    {
        $pdf = $this->generateReceipt($invoice);
        return $pdf->download('facture-'.$invoice->invoice_number.'.pdf');
    }
}
