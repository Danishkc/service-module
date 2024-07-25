<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceInvoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'quotation_id',
        'cus_id',
        'place_of_supply',
        'gst',
        'invoice_date',
        'payment_reference',
        'total',
        'tax_amt',
        'untax_amt',
        'due_date',
        'payment_terms',
        'customer_reference',
        'sales_person',
        'sales_team',
        'recipient_bank',
        'delivery_date',
        'incoterm',
        'incoterm_location',
        'fiscal_position',
        'cash_round_method',
        'auto_post',
        'to_check',
        'journal',
        'pay_method',
        'pay_date',
        'pay_amt'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($service_invoices) {
            $service_invoices->invoice_reference = self::generateInvoiceReference();
        });
    }

    public static function generateInvoiceReference()
    {
        $lastService = self::orderBy('id', 'desc')->first();
        $lastNumber = $lastService ? intval(substr($lastService->invoice_reference, -5)) : 0;
        $newNumber = str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);
        $currentYear = date('Y');
        return 'INV/' . $currentYear . '/' . $newNumber;
    }

    public function serviceQuotation()
    {
        return $this->belongsTo(ServiceQuotation::class, 'quotation_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customers::class, 'cus_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }
    

    public function lines()
    {
        return $this->hasMany(ServiceInvoiceLine::class, 'invoice_id');
    }
}
