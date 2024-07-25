<?php

namespace App\Http\Controllers;
use App\Models\Customers;
use App\Models\Products;
use App\Models\Service;
use App\Models\ServiceLine;
use App\Models\ServiceQuotation;
use Inertia\Inertia;
use App\Models\User;
use App\Models\Taxcategory;
use Illuminate\Http\Request;
use App\Http\Requests\QuotationStoreRequest;
use App\Models\ServiceInvoice;
use App\Models\ServiceQuotationLine;

class QuotationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        $serviceStatus=ServiceQuotation::with('service')->find($id);
        $existingInvoice = ServiceInvoice::where('quotation_id', $id)->first();
        $customers = ServiceQuotation::with('customer')->find($id);
        $products = Products::all();
        $serviceQuotation = ServiceQuotation::find($id);
        $users = User::all();
        $currentUser = auth()->user();
        // $serviceQuotationLines = ServiceQuotationLine::find($id);
        $serviceQuotationLines = ServiceQuotationLine::where('quotation_id', $id)->get();
        //     ->with('product')
        //     ->get();

        // dd($serviceLines);
        return Inertia::render('Service/QuotationIndex', [
            'customer' => $customers,
            'products'=>$products,
            'quotation_id' => $id,
            'serviceStatus'=>$serviceStatus,
            'serviceQuotation'=>$serviceQuotation,
            'serviceQuotationLines' =>$serviceQuotationLines,
            'existingInvoice'=>$existingInvoice,
            'users'=> $users,
            'currentUserId' => $currentUser ? $currentUser->id : null,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        // $serviceStatus=Service::find($id);
        $customers = Customers::all();
        $products = Products::all();
        $service = Service::find($id);
        $users = User::all();
        $currentUser = auth()->user();
        // dd($serviceStatus);
        $serviceLines = ServiceLine::where('service_id', $id)
            ->with('product')
            ->get();

        $taxes=Taxcategory::all();
        
        return Inertia::render('Service/CreateQuotation', [
            'customers' => $customers,
            'products'=>$products,
            'service_id' => $id,
            'service'=>$service,
            'serviceLines' =>$serviceLines,
            'users'=> $users,
            'currentUserId' => $currentUser ? $currentUser->id : null,
            'taxes'=>$taxes,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(QuotationStoreRequest $request)
    {
        try {
            $validated = $request->validated();
        } catch (ValidationException $e) {
            return redirect()->route('quotation.create', $service->id)->withErrors($e->validator->errors());
        }

        

        // $existingQuotation = ServiceQuotation::where('service_id', $validated['service_id'])->first();
        // if ($existingQuotation) {
        //     return redirect()->route('quotation.index', $existingQuotation->id);
        // }
        $service_quotation = ServiceQuotation::create($validated);
        if (!empty($validated['lines'])) {
            foreach ($validated['lines'] as $line) {
                $service_quotation->lines()->create($line);
            }
        }
  
        

        return redirect()->route('quotation.index', $service_quotation->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $quotations = ServiceQuotation::where('service_id', $id)->get();
        $users=User::all();
        $customers = Customers::all();
        $invoices = ServiceInvoice::all();
        return Inertia::render('Service/ViewQuotations', [
            'quotations' => $quotations,
            'users'=>$users,
            'customers'=>$customers,
            'invoices'=>$invoices
        ]);
    }
    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $serviceQuotation = ServiceQuotation::findOrFail($id);
        $serviceId = ServiceQuotation::where('id', $id)->value('service_id');
        $serviceQuotationLine = ServiceQuotationLine::where('quotation_id', $id) 
            ->with('product')
            ->orderBy('id')
            ->get();

        $serviceQuotationOptionLine = ServiceQuotationLine::where('quotation_id', $id)
            ->whereNotNull('option_pro_id')
            ->with('optionProduct')       
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
        $customers = Customers::all();
        $products = Products::all();
        $users = User::all();
        $currentUser = auth()->user();

        return Inertia::render('Service/EditQuotation', [
            'serviceQuotation' => $serviceQuotation,
            'serviceQuotationLine' => $serviceQuotationLine,
            'serviceQuotationOptionLine'=>$serviceQuotationOptionLine,
            'serviceQuotationSection'=>$serviceQuotationSection,
            'serviceQuotationNote'=>$serviceQuotationNote,
            'customers'=>$customers,
            'products'=>$products,
            'users'=> $users,
            'service_id' => $serviceId,
            'currentUserId' => $currentUser ? $currentUser->id : null,
            'taxes'=>$taxes
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(QuotationStoreRequest $request, $id)
    {
        $serviceQuotation = ServiceQuotation::findOrFail($id);
        try {
            $validated = $request->validated();
        } catch (ValidationException $e) {
            return redirect()->route('services.edit', $service->id)->withErrors($e->validator->errors());
        }
        
        $serviceQuotation->update($validated);

        if (!empty($validated['lines'])) {
            foreach ($validated['lines'] as $lineData) {
                $lineData['quotation_id'] = $id;

                if (isset($lineData['id'])) {
                    ServiceQuotationLine::updateOrCreate(['id' => $lineData['id'], 'quotation_id' => $id], $lineData);
                } else {
                    ServiceQuotationLine::create($lineData);
                }
            }
        }
        return redirect()->route('quotation.index', $serviceQuotation->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $serviceQuotationLine = ServiceQuotationLine::findOrFail($id);
        $serviceQuotationLine->delete();
        return response()->json(['message' => 'Line deleted successfully'], 200);
    }

    public function destroySelected(Request $request, $ids)
    {

        $ids = explode(',', $ids);
        $ids = array_filter($ids, function ($id) {
            return is_numeric($id) && $id > 0;
        });
        $quotations = ServiceQuotation::whereIn('id', $ids)->get();
        foreach ($quotations as $quotations) {
            $quotations->delete();
        }
        return redirect()->route('services.index');
    }
    
}
