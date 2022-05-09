<?php

namespace Lit\Actions;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Ignite\Support\Facades\Form;
use App\Models\Inventory;

class RestockStatusDelivered
{
    /**
     * Run the action.
     *
     * @param  Collection  $models
     * @return JsonResponse
     */
    public function run(Collection $models)
    {
		$post = $models[0];
		$post->status = 'Delivered';
		
		$post->per_sheet = $post->cost / $post->yield; 
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
				   $parentModel->current_qty = $post->qty;
			}
		}	 
		$parentModel->save();       		
		$post->save();

		$toners = Inventory::where('type', '=', 'Toner')->get();
		$toner_per_click = 0;
		foreach($toners as $one_toner){
			$toner_per_click = $toner_per_click + $one_toner->current_per_sheet;
		}
		
		$printing_settings = Form::load('settings', 'printing_settings');
		$printing_settings->update(['current_toner_cost' => $toner_per_click]);	         
		
		
        return redirect('/admin/restock-logs');
        
    }
}
