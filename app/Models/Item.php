<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class item extends Model
{
    use HasFactory;
    protected $primaryKey = 'item_id';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['item_id', 'item_name', 'lot_size', 'lead_time', 'inventory', 'level', 'satuan', 'tipe'];
    protected $table = 'item';
}
