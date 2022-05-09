<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Inventory;
use App\Models\Client;

class Project extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['project_title','fourover_id','client_type','client_id','inventory_id','finished_size','per_sheet','sides','qty','base_cost','finishing_cost','shipping_cost','upcharge_percent','upcharge_amount','subtotal','tax1_amount','tax2_amount','total','status','tax', 'client_email', 'client_name', 'paper_base', 'printer_base', 'tax_1', 'tax_2', 'multiplier', 'chocolate_subsidy', 'note', 'file', 'date_reserved', 'invoice_id'];

    public function inventory()
    {
        return $this->hasMany(Inventory::class);
    }
    
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }
}
