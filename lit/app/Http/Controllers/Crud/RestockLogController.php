<?php

namespace Lit\Http\Controllers\Crud;

use Illuminate\Database\Eloquent\Builder;
use App\Models\Inventory;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Ignite\Crud\Controllers\CrudController;
use Ignite\Support\Facades\Form;

class RestockLogController extends CrudController
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
        // return $user->can("{$operation} restock_logs");
        return true;
    }
   
   public function fillOnUpdate($post, $attributes)
   {
	  
	}
    
    public function fillOnStore($post)
    {
      if( !empty($post->cost) && !empty($post->yield)){
         $post->per_sheet = $post->cost / $post->yield; 
      }

      if(empty($post->ordered_on)){
         $post->ordered_on = date("y-m-d");
      }

      if( $post->status == 'Delivered' ) {
         $cost = $post->cost;
         $yield = $post->yield;
         
         $parentModel = Inventory::findOrFail($post->inventory_id);
            
         if($parentModel->current_per_sheet != 0) {
            
            $new_per_sheet = $cost / $yield;
            $price_per_sheet_packsordered = $new_per_sheet * $post->qty;
            $old_per_sheet_packs = $parentModel->current_qty * $parentModel->current_per_sheet;
            $new_total_packs = $post->qty + $parentModel->current_qty;
            $parentModel->current_per_sheet = ($old_per_sheet_packs + $price_per_sheet_packsordered) / $new_total_packs;	
            $parentModel->current_qty = $new_total_packs;
         
         } else {
         
            if( !empty($post->cost) && !empty($post->yield)){
               $parentModel->current_per_sheet = $post->per_sheet;
            }
         
         }
         
         $parentModel->save();  
         
         $toners = Inventory::where('type', '=', 'Toner')->get();
         $toner_per_click = 0;
         foreach($toners as $one_toner){
            $toner_per_click = $toner_per_click + $one_toner->current_per_sheet;
         }
         $printing_settings = Form::load('settings', 'printing_settings');
         $printing_settings->update(['current_toner_cost' => $toner_per_click]);	         
      }
    }
    
   
}
