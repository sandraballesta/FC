<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_order',
        'reference',
        'id_costumer',
        'id_address_delivery',
        'current_state',
        'total_paid',
        'date_add'

    ];

}
