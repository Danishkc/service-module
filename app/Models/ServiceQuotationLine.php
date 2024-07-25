<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceQuotationLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'quotation_id',
        'pro_id',
        'description',
        'quantity',
        'unit_price',
        'taxes',
        'section',
        'note',
        'option_pro_id',
        'option_description',
        'option_quantity',
        'option_unit_price',
    ];

    // Define the relationship with ServiceQuotation
    public function quotation()
    {
        return $this->belongsTo(ServiceQuotation::class, 'quotation_id');
    }

    // Define the relationship with Product (for pro_id)
    public function product()
    {
        return $this->belongsTo(Products::class, 'pro_id');
    }

    // Define the relationship with Product (for option_pro_id)
    public function optionProduct()
    {
        return $this->belongsTo(Products::class, 'option_pro_id');
    }
}
