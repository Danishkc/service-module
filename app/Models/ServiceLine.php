<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'type',
        'product_id',
        'demand',
        'done',
        'used',
    ];

    public function services()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function product()
    {
        return $this->belongsTo(Products::class, 'product_id');
    }

}
