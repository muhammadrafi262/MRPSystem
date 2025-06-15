<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class order extends Model
{
    use HasFactory;
    protected $primaryKey = 'order_id';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['order_id', 'customer_id', 'period_number', 'item_id', 'quantity', 'order_date'];
    protected $table = 'order';
}
