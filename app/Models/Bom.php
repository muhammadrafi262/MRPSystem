<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class bom extends Model
{
    use HasFactory;
    public $incrementing = false;
    public $timestamps = false;
    protected $primaryKey = null;
    protected $fillable = ['level', 'item_id', 'component_id', 'bom_multiplier'];
    protected $table = 'bom';

    public static function findByItemAndComponent($item_id, $component_id)
    {
        return self::where('item_id', $item_id)
                   ->where('component_id', $component_id)
                   ->firstOrFail();
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id', 'item_id');
    }

    public function component()
    {
        return $this->belongsTo(Item::class, 'component_id', 'item_id');
    }

}
