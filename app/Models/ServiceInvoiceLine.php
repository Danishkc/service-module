<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceInvoiceLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'pro_id',
        'label',
        'quantity',
        'unit_price',
        'taxes',
        'section',
        'note',
    ];

    public function serviceInvoice()
    {
        return $this->belongsTo(ServiceInvoice::class, 'invoice_id');
    }

    public function product()
    {
        return $this->belongsTo(Products::class, 'pro_id');
    }
    
}
