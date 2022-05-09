<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Matrix extends Model 
{
    

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['product', 'size', 'weight', 'note', 'qty', 'average', 'dash_base', 'percent_upcharge'];
    
    
    public function vendors()
	{
	    return $this->repeatables('vendors');
	}

    
}
