<?php

namespace App\Http\Controllers;
use App\Models\Customers;
use App\Models\Products;
use App\Models\Service;
use App\Models\ServiceQuotation;
use App\Models\ServiceQuotationLine;
use App\Models\ServiceInvoice;
use App\Models\ServiceInvoiceLine;
use App\Models\User;
use App\Models\Taxcategory;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Requests\InvoiceStoreRequest;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        $serviceStatus=ServiceInvoice::with('service')->find($id);
        $customers = ServiceInvoice::with('customer')->find($id);
        $products = Products::all();
        $serviceInvoice = ServiceInvoice::find($id);
        $users = User::all();
        $currentUser = auth()->user();
        // $serviceQuotationLines = ServiceQuotationLine::find($id);
        $serviceInvoiceLines = ServiceInvoiceLine::where('invoice_id', $id)->get();
        //     ->with('product')
        //     ->get();
        $quotationReference = $serviceInvoice->serviceQuotation->quotation_reference ?? null;
        // dd($serviceLines);
        return Inertia::render('Service/InvoiceIndex', [
            'customer' => $customers,
            'products'=>$products,
            'invoice_id' => $id,
            'serviceInvoice'=>$serviceInvoice,
            'serviceInvoiceLines' =>$serviceInvoiceLines,
            'users'=> $users,
            'serviceStatus'=>$serviceStatus,
            'quotationReference' => $quotationReference,
            'currentUserId' => $currentUser ? $currentUser->id : null,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        $customers = Customers::all();
        $products = Products::all();
        $serviceQuotation = ServiceQuotation::find($id);
        $users = User::all();
        $currentUser = auth()->user();
        $serviceQuotationLines = ServiceQuotationLine::where('quotation_id', $id)
            ->with('product')
            ->orderBy('id')
            ->get();
        $serviceQuotationSection = ServiceQuotationLine::where('quotation_id', $id)
            ->whereNotNull('section')
            ->orderBy('id')    
            ->get();
        $serviceQuotationNote = ServiceQuotationLine::where('quotation_id', $id)
            ->whereNotNull('note')  
            ->orderBy('id')  
            ->get();
        $taxes=Taxcategory::all();
        // dd($serviceQuotationLines);
        return Inertia::render('Service/CreateInvoice', [
            'customers' => $customers,
            'products'=>$products,
            'quotation_id' => $id,
            'serviceQuotation'=>$serviceQuotation,
            'serviceQuotationLines' =>$serviceQuotationLines,
            'serviceQuotationSection'=>$serviceQuotationSection,
            'serviceQuotationNote'=>$serviceQuotationNote,
            'users'=> $users,
            'currentUserId' => $currentUser ? $currentUser->id : null,
            'taxes'=>$taxes
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(InvoiceStoreRequest $request)
    {
        
        try {
            $validated = $request->validated();
        } catch (ValidationException $e) {
            return redirect()->route('invoice.create', $service->id)->withErrors($e->validator->errors());
        }
        

        // $existingInvoice = ServiceInvoice::where('quotation_id', $validated['quotation_id'])->first();
        // if ($existingInvoice) {
        //     return redirect()->route('invoice.index', $existingInvoice->id);
        // }

        $service_invoice = ServiceInvoice::create($validated);
        if (!empty($validated['lines'])) {
            foreach ($validated['lines'] as $line) {
                $service_invoice->lines()->create($line);
            }
        }
  

        return redirect()->route('invoice.index', $service_invoice->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $invoices = ServiceInvoice::where('quotation_id', $id)->get();
        $users=User::all();
        $customers = Customers::all();
        return Inertia::render('Service/ViewInvoices', [
            'users'=>$users,
            'customers'=>$customers,
            'invoices'=>$invoices
        ]);
    }

    public function registerPayment(Request $request, $id) {
        // dd($request->all());
        $validatedData = $request->validate([
            'journal' => 'required|string',
            'pay_method' => 'required|string',
            'pay_date' => 'required|date',
            'pay_amt' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/|min:0'
        ]);

        $invoice = ServiceInvoice::findOrFail($id);
        
        $invoice->update([
            'journal' => $validatedData['journal'],
            'pay_method' => $validatedData['pay_method'],
            'pay_date' => $validatedData['pay_date'],
            'pay_amt' => $validatedData['pay_amt']
        ]);
        return redirect()->route('invoice.index', $invoice->id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $serviceInvoice = ServiceInvoice::findOrFail($id);
        $InvoiceId = ServiceInvoice::where('id', $id)->value('quotation_id');
        $serviceInvoiceLine = ServiceInvoiceLine::where('invoice_id', $id)
            ->with('product')
            ->orderBy('id')
            ->get();
        
        $serviceInvoiceSection = ServiceInvoiceLine::where('invoice_id', $id)
            ->whereNotNull('section')
            ->orderBy('id')    
            ->get();
        $serviceInvoiceNote = ServiceInvoiceLine::where('invoice_id', $id)
            ->whereNotNull('note')  
            ->orderBy('id')  
            ->get();

        $taxes=Taxcategory::all();
        $customers = Customers::all();
        $products = Products::all();
        $users = User::all();
        $currentUser = auth()->user();

        return Inertia::render('Service/EditInvoice', [
            'serviceInvoice' => $serviceInvoice,
            'serviceInvoiceLine' => $serviceInvoiceLine,
            'serviceInvoiceSection'=>$serviceInvoiceSection,
            'serviceInvoiceNote'=>$serviceInvoiceNote,
            'customers'=>$customers,
            'products'=>$products,
            'users'=> $users,
            'invoice_id' => $InvoiceId,
            'currentUserId' => $currentUser ? $currentUser->id : null,
            'taxes'=>$taxes
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(InvoiceStoreRequest $request, $id)
    {
        $serviceInvoice = ServiceInvoice::findOrFail($id);
        try {
            $validated = $request->validated();
        } catch (ValidationException $e) {
            return redirect()->route('invoice.edit', $service->id)->withErrors($e->validator->errors());
        }
        
        $serviceInvoice->update($validated);

        if (!empty($validated['lines'])) {
            foreach ($validated['lines'] as $lineData) {
                $lineData['invoice_id'] = $id;

                if (isset($lineData['id'])) {
                    ServiceInvoiceLine::updateOrCreate(['id' => $lineData['id'], 'invoice_id' => $id], $lineData);
                } else {
                    ServiceInvoiceLine::create($lineData);
                }
            }
        }
        return redirect()->route('invoice.index', $serviceInvoice->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $serviceInvoiceLine = ServiceInvoiceLine::findOrFail($id);
        $serviceInvoiceLine->delete();
        return response()->json(['message' => 'Line deleted successfully'], 200);
    }

    public function destroySelected(Request $request, $ids)
    {
        $ids = explode(',', $ids);
        $ids = array_filter($ids, function ($id) {
            return is_numeric($id) && $id > 0;
        });
        $invoices = ServiceInvoice::whereIn('id', $ids)->get();
        foreach ($invoices as $invoices) {
            $invoices->delete();
        }
        return redirect()->route('services.index');
    }
}
