<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Inventory;

class RestockLog extends Model 
{
    

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['inventory_id', 'qty', 'cost', 'per_sheet', 'yield', 'supplier', 'ordered_on', 'status'];
    
    protected $appends = ['inventory_title'];
  
    public function inventory()
    {
      return $this->hasOne('App\Models\Inventory', 'id', 'inventory_id');
    }
    
    public function getInventoryTitleAttribute()
    {
        if(is_null($this->inventory)) {
            return null;
        } else {
            return $this->inventory->title;		
        }
    }
    
  
    
}
