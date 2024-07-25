<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{

    const STATUS_NEW = 0;
    const STATUS_CONFIRMED = 1;
    const STATUS_UNDER_REPAIR = 2;
    const STATUS_REPAIRED = 3;
    const STATUS_CANCELLED_CONFIRMED= 4;
    const STATUS_CANCELLED_UNDER_REPAIR= 5;

    protected $fillable = [
        'priority',
        'repair_reference',
        'cus_id',
        'pro_id',
        'user_id',
        'product_quantity',
        'return',
        'warranty',
        'scheduled_date',
        'responsible',
        'tags',
        'component_status',
        'repair_notes',
        'operation_type',
    ];

    protected $casts = [
        'warranty' => 'boolean',
        'component_status' =>'boolean'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($service) {
            $service->repair_reference = self::generateRepairReference();
        });
    }

    public static function generateRepairReference()
    {
        $lastService = self::orderBy('id', 'desc')->first();
        $lastNumber = $lastService ? intval(substr($lastService->repair_reference, -5)) : 0;
        $newNumber = str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);
        return 'WH/RO/' . $newNumber;
    }

    public function customer(){
        return $this->belongsTo(Customer::class, 'cus_id');
    }

    public function product()
    {
        return $this->belongsTo(Products::class, 'pro_id');
    }

    public function user(){
        return $this->belongsTo(user::class, 'user_id');
    }

    public function responsible(){
        return $this->belongsTo(user::class, 'responsible');
    }

    public function lines()
    {
        return $this->hasMany(ServiceLine::class);
    }
}
