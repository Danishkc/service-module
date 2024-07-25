<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceQuotation extends Model
{
    use HasFactory;

    protected $fillable = [
        'quotation_reference',
        'service_id',
        'cus_id',
        'gst',
        'expiration',
        'pricelist',
        'payment_terms',
        'total',
        'tax_amt',
        'untax_amt',
        'sales_person',
        'sales_team',
        'online_signature',
        'online_payment',
        'customer_reference',
        'tags',
        'fiscal_position',
        'incoterm',
        'incoterm_location',
        'shipping_policy',
        'delivery_date',
        'source_document',
        'campaign',
        'medium',
        'source',
    ];


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($service_quotations) {
            $service_quotations->quotation_reference = self::generateQuotationReference();
        });
    }

    public static function generateQuotationReference()
    {
        $lastQuotation = self::orderBy('id', 'desc')->first();
        $lastNumber = $lastQuotation ? intval(substr($lastQuotation->quotation_reference, 1)) : 0;
        $newNumber = str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);
        return 'S' . $newNumber;
    }

    
    public function quotationLines()
    {
        return $this->hasMany(ServiceQuotationLine::class, 'quotation_id');
    }

    
    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    
    public function customer()
    {
        return $this->belongsTo(Customers::class, 'cus_id');
    }

    public function lines()
    {
        return $this->hasMany(ServiceQuotationLine::class, 'quotation_id');
    }

    public function serviceQuotation()
    {
        return $this->hasMany(ServiceInvoice::class, 'quotation_id');
    }

    public function salesPerson(){
        return $this->belongsTo(User::class, 'sales_person');
    }
}
