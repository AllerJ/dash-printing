<?php

namespace Lit\Http\Controllers\Crud;

use Illuminate\Database\Eloquent\Builder;

use Illuminate\Contracts\Auth\Access\Authorizable;
use Ignite\Crud\Controllers\CrudController;

class MatrixController extends CrudController
{
    /**
     * Authorize request for authenticated lit-user and permission operation.
     * Operations: create, read, update, delete
     *
     * @param Authorizable $user
     * @param string $operation
     * @param integer $id
     * @return boolean
     */
    public function authorize(Authorizable $user, string $operation, $id = null): bool
    {
        // return $user->can("{$operation} matrices");
        return true;
    }
	
	public function fillOnUpdate($post, $attributes)
    {
		if(sizeof($post->vendors) > 0) {
			$average = 0;
			foreach($post->vendors as $vendor){
				$average = $average + $vendor->price;
			}
			$vendor_count = sizeof($post->vendors);
			$average_price = $average / $vendor_count;
			$post->average = $average_price;
		}
		
		if(!empty($post->average) && !empty($attributes['dash_base'])){

			$average = 0;
			foreach($post->vendors as $vendor){
				$average = $average + $vendor->price;
			}
			$vendor_count = sizeof($post->vendors);
			
			$average = round($average / $vendor_count);
			$base = $attributes['dash_base'];
			
			$without = $average - $base;
			$upcharge = ( $without / $base ) * 100;
			// $top_number = $average - $base;
			// $bottom_top_number = $average + $base;
			// $bottom_number = $bottom_top_number / 2;
			// $division = $top_number / $bottom_number;
			// $upcharge = $division * 100;
			$post->percent_upcharge = $upcharge;

		}
    }
}
