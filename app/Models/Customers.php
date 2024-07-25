<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customers extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone_1',
        'address'
    ];

    

    public function services()
    {
        return $this->hasMany(Service::class, 'cus_id');
    }

}
