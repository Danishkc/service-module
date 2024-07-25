<?php

namespace App\Http\Controllers;
use App\Models\Customers;
use App\Models\Products;
use App\Models\Service;
use App\Models\User;
use App\Models\ServiceLine;
use App\Models\ServiceQuotation;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\ServiceStoreRequest;
use App\Models\ServiceInvoice;
use Illuminate\Support\Facades\Log;

class ServiceController extends Controller
{
    public function index() {

        $currentUserId = auth()->user()->id;
        $services = Service::where('user_id', $currentUserId)->get();
        $customers = Customers::all();
        $products = Products::all();
        $quotations = ServiceQuotation::all();

        // dd($quotation);
        
        // $productIds = json_decode($services->pluck('pro_id'));
    
        return Inertia::render('Service/Index', [
            'services' => $services,
            'customers' => $customers,
            'products' => $products,
            'quotations' =>$quotations
        ]);
    }

    public function create(){
        $customers = Customers::all();
        $products = Products::all();
        $users = User::all();
        $currentUser = auth()->user();

        return Inertia::render('Service/CreateService',[
            'customers'=>$customers,
            'products'=>$products,
            'users'=> $users,
            'currentUserId' => $currentUser ? $currentUser->id : null,
        ]);
    }

    public function store(ServiceStoreRequest $request)
    {
        try {
            $validated = $request->validated();
        } catch (ValidationException $e) {
            return redirect()->route('services.startRepair', $service->id)->withErrors($e->validator->errors());
        }
        $currentUserId = auth()->user()->id;

        $serviceData = array_merge($validated, ['user_id' => $currentUserId]);
        
        $service = Service::create($serviceData);
            if (!empty($validated['lines'])) {
                foreach ($validated['lines'] as $line) {
                    $service->lines()->create($line);
                }
            }
        return redirect()->route('services.startRepair', $service->id);
    }

    public function startRepair($id) {
        $service = Service::findOrFail($id);
        $service->status = Service::STATUS_CONFIRMED;
        $service->component_status = true;
        $service->save();
        $serviceLines = ServiceLine::where('service_id', $id)
            ->with('product')
            ->get();
        
        $existingQuotation = ServiceQuotation::where('service_id', $id)->latest()->first();
        $existingQuotationId = $existingQuotation ? $existingQuotation->id : null;

        $existingInvoice = $existingQuotationId ? ServiceInvoice::where('quotation_id', $existingQuotationId)->latest()->first() : null;
        $existingInvoiceId = $existingInvoice ? $existingInvoice->id : null;

        $customers = Customers::all();
        $products = Products::all();
        $users = User::all();
    
        return Inertia::render('Service/ConfirmRepair', [
            'service' => $service,
            'serviceLines' => $serviceLines,
            'customers'=>$customers,
            'products'=>$products,
            'users'=> $users,
            'existingQuotationId'=>$existingQuotationId,
            'existingInvoiceId'=>$existingInvoiceId,
        ]);
    }

    public function endRepair(ServiceStoreRequest $request, $id)
    {
        $service = Service::findOrFail($id);
        try {
            $validated = $request->validated();
        } catch (ValidationException $e) {
            return redirect()->route('services.startRepair', $service->id)->withErrors($e->validator->errors());
        }
        if ($service->status==2) {
            return redirect()->route('services.index');
        }
        $validated = $request->validated();
        $service->status = Service::STATUS_UNDER_REPAIR;
        $service->save();
        $service->update($validated);
        if (!empty($validated['lines'])) {
            foreach ($validated['lines'] as $lineData) {
                $lineData['service_id'] = $id;

                if (isset($lineData['id'])) {
                    ServiceLine::updateOrCreate(['id' => $lineData['id'], 'service_id' => $id], $lineData);
                } else {
                    ServiceLine::create($lineData);
                }
            }
        }
        $existingQuotation = ServiceQuotation::where('service_id', $id)->first();
        $existingQuotationId = $existingQuotation ? $existingQuotation->id : null;

        $existingInvoice = $existingQuotationId ? ServiceInvoice::where('quotation_id', $existingQuotationId)->first() : null;
        $existingInvoiceId = $existingInvoice ? $existingInvoice->id : null;

        $serviceLines = ServiceLine::where('service_id', $id)
            ->with('product')
            ->get();
        $customers = Customers::all();
        $products = Products::all();
        $users = User::all();
        return Inertia::render('Service/UnderRepair', [
            'service' => $service,
            'customers' => $customers,
            'products' => $products,
            'serviceLines' => $serviceLines,
            'existingQuotationId'=>$existingQuotationId,
            'existingInvoiceId'=>$existingInvoiceId,
            'users' => $users
        ]);
    }

    public function getEndRepair($id){
            $service = Service::findOrFail($id);
            $service->status = Service::STATUS_UNDER_REPAIR;
            $service->component_status = true;
            $service->save();
            $serviceLines = ServiceLine::where('service_id', $id)
                ->with('product')
                ->get();
            
            $existingQuotation = ServiceQuotation::where('service_id', $id)->latest()->first();
            $existingQuotationId = $existingQuotation ? $existingQuotation->id : null;
        
            $existingInvoice = $existingQuotationId ? ServiceInvoice::where('quotation_id', $existingQuotationId)->latest()->first() : null;
            $existingInvoiceId = $existingInvoice ? $existingInvoice->id : null;
            $customers = Customers::all();
            $products = Products::all();
            $users = User::all();
            return Inertia::render('Service/UnderRepair', [
                'service' => $service,
                'customers' => $customers,
                'products' => $products,
                'serviceLines' => $serviceLines,
                'existingQuotationId'=>$existingQuotationId,
                'existingInvoiceId'=>$existingInvoiceId,
                'users' => $users
            ]);
    }

    public function repaired(ServiceStoreRequest $request, $id)
    { 

        $service = Service::findOrFail($id);
        try {
            $validated = $request->validated();
        } catch (ValidationException $e) {
            return redirect()->route('services.endRepair', $service->id)->withErrors($e->validator->errors());
        }

        if ($service->status==3) {
            return redirect()->route('services.index');
        }
            $service->status = Service::STATUS_REPAIRED;
            $service->component_status = false;
            $service->save();
            $service->update($validated);

            if (!empty($validated['lines'])) {
                foreach ($validated['lines'] as $lineData) {
                    $lineData['service_id'] = $id;

                    if (isset($lineData['id'])) {
                        ServiceLine::updateOrCreate(['id' => $lineData['id'], 'service_id' => $id], $lineData);
                    } else {
                        ServiceLine::create($lineData);
                    }
                }
            }
            $existingQuotation = ServiceQuotation::where('service_id', $id)->latest()->first();
            $existingQuotationId = $existingQuotation ? $existingQuotation->id : null;

            $existingInvoice = $existingQuotationId ? ServiceInvoice::where('quotation_id', $existingQuotationId)->latest()->first() : null;
            $existingInvoiceId = $existingInvoice ? $existingInvoice->id : null;
            $serviceLines = ServiceLine::where('service_id', $id)
                ->with('product')
                ->get();
            $customers = Customers::all();
            $products = Products::all();
            $users = User::all();

            return Inertia::render('Service/Repaired', [
                'service' => $service,
                'customers' => $customers,
                'products' => $products,
                'serviceLines' => $serviceLines,
                'existingQuotationId'=>$existingQuotationId,
                'existingInvoiceId'=>$existingInvoiceId,
                'users' => $users
            ]);
    }

    public function getRepaired($id){

        $existingQuotation = ServiceQuotation::where('service_id', $id)->latest()->first();
        $existingQuotationId = $existingQuotation ? $existingQuotation->id : null;

        $existingInvoice = $existingQuotationId ? ServiceInvoice::where('quotation_id', $existingQuotationId)->latest()->first() : null;
        $existingInvoiceId = $existingInvoice ? $existingInvoice->id : null;

        $service = Service::findOrFail($id);
        $serviceLines = ServiceLine::where('service_id', $id)
            ->with('product')
            ->get();
        $customers = Customers::all();
        $products = Products::all();
        $users = User::all();
        return Inertia::render('Service/Repaired', [
            'service' => $service,
            'customers' => $customers,
            'products' => $products,
            'serviceLines' => $serviceLines,
            'existingQuotation'=>$existingQuotation,
            'existingQuotationId'=>$existingQuotationId,
            'existingInvoiceId'=>$existingInvoiceId,
            'users' => $users
        ]);
}

    public function storeCustomer(Request $request){
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email'=> 'nullable|string|email|max:255|unique:users',
            'phone_1'=> 'nullable|integer|unique|min:15',
            'address' => 'nullable|string|max:225'
        ]);
        
        $customer = Customers::create($validated);
        
    }

    public function storeProduct(Request $request){       
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'serial_no' => 'required|string|max:225'
        ]);
        
        $product = Products::create($validated);
        
    }


    public function storeResponsible(Request $request){       
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
        ]);
        
        $user = User::create($validated);
        
    }

    public function destroy($id){
        $serviceLine = ServiceLine::findOrFail($id);
        $serviceLine->delete();
        return response()->json(['message' => 'Line deleted successfully'], 200);
    }

    public function destroySelected(Request $request, $ids)
    {
        $ids = explode(',', $ids);
        $ids = array_filter($ids, function ($id) {
            return is_numeric($id) && $id > 0;
        });
        $services = Service::whereIn('id', $ids)->get();
        foreach ($services as $service) {
            $service->delete();
        }
        return redirect()->route('services.index');
    }

    public function edit($id){

        $service = Service::find($id);

        if (!$service) {
            return response()->json(['message' => 'No such service'], 404);
        }
    
        $status = $service->status;
        $serviceLines = ServiceLine::where('service_id', $id)
            ->with('product')
            ->get();
        
            $customers = Customers::all();
            $products = Products::all();
            $users = User::all();
    
        if ($status == 2) {
            $existingQuotation = ServiceQuotation::where('service_id', $id)->latest()->first();
            $existingQuotationId = $existingQuotation ? $existingQuotation->id : null;

            $existingInvoice = $existingQuotationId ? ServiceInvoice::where('quotation_id', $existingQuotationId)->latest()->first() : null;
            $existingInvoiceId = $existingInvoice ? $existingInvoice->id : null;
            return Inertia::render('Service/UnderRepair', [
                'service' => $service,
                'customers' => $customers,
                'products' => $products,
                'serviceLines' => $serviceLines,
                'existingQuotationId'=>$existingQuotationId,
                'existingInvoiceId'=>$existingInvoiceId,
                'users' => $users
            ]);
        } elseif ($status == 3) {
            $existingQuotation = ServiceQuotation::where('service_id', $id)->latest()->first();
            $existingQuotationId = $existingQuotation ? $existingQuotation->id : null;

            $existingInvoice = $existingQuotationId ? ServiceInvoice::where('quotation_id', $existingQuotationId)->latest()->first() : null;
            $existingInvoiceId = $existingInvoice ? $existingInvoice->id : null;
        
            return Inertia::render('Service/Repaired', [
                'service' => $service,
                'customers' => $customers,
                'products' => $products,
                'serviceLines' => $serviceLines,
                'existingQuotation'=>$existingQuotation,
                'existingQuotationId'=>$existingQuotationId,
                'existingInvoiceId'=>$existingInvoiceId,
                'users' => $users
            ]);
        } elseif ($status ==1) {
            return redirect()->route('services.startRepair', $service->id);
        } elseif($status==4){
            return redirect()->route('services.cancelConfirmRepair', $service->id);
        } elseif($status==5){
            return redirect()->route('services.cancelledUnderRepair', $service->id);
        } {
            return response()->json(['message' => 'Invalid status'], 400);
        }
    }

    public function cancelConfirmRepair($id){
        $service = Service::find($id);

        if (!$service) {
            return response()->json(['message' => 'No such service'], 404);
        }
        $service->status = Service::STATUS_CANCELLED_CONFIRMED;
            $service->component_status = false;
            $service->save();
            $serviceLines = ServiceLine::where('service_id', $id)
                ->with('product')
                ->get();
        
            $customers = Customers::all();
            $products = Products::all();
            $users = User::all();
        return Inertia::render('Service/CancelledConfirmRepair', [
            'service' => $service,
            'customers' => $customers,
            'products' => $products,
            'serviceLines' => $serviceLines,
            'users' => $users
        ]);
    }

    public function cancelledUnderRepair($id){
        $service = Service::find($id);

        if (!$service) {
            return response()->json(['message' => 'No such service'], 404);
        }
        $service->status = Service::STATUS_CANCELLED_UNDER_REPAIR;
            $service->component_status = false;
            $service->save();
            $serviceLines = ServiceLine::where('service_id', $id)
                ->with('product')
                ->get();
        
            $customers = Customers::all();
            $products = Products::all();
            $users = User::all();
        return Inertia::render('Service/CancelledUnderRepair', [
            'service' => $service,
            'customers' => $customers,
            'products' => $products,
            'serviceLines' => $serviceLines,
            'users' => $users
        ]);
    }

}