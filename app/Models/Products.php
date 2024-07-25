<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'serial_no'
    ];

    public function services()
    {
        return $this->hasMany(Service::class, 'pro_id');
    }

    public function serviceLine()
    {
        return $this->hasMany(Service::class, 'product_id');
    }

    public function optionProduct()
    {
        return $this->hasMany(ServiceQuotationLine::class, 'option_pro_id');
    }

    
}
