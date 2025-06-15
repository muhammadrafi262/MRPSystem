<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class customer extends Model
{
    use HasFactory;
    protected $primaryKey = 'customer_id';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['customer_id', 'customer_name'];
    protected $table = 'customer';
}
