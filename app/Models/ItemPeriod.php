<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemPeriod extends Model
{
    use HasFactory;
    public $incrementing = false;
    protected $primaryKey = null;
    protected $fillable = ['item_id', 'period_number', 'gross_requirement', 'projected_inventory', 'planned_order_receipt', 'planned_order_release'];
    protected $table = 'item_period';

    // ItemPeriod.php
    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id', 'item_id');
    }
}


