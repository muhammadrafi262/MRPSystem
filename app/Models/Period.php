<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class period extends Model
{
    use HasFactory;
    protected $primaryKey = 'period_number';
    public $incrementing = false;
    protected $fillable = ['period_number', 'start_date', 'end_date'];
    protected $table = 'period';
}
