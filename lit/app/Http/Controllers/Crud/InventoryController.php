<?php

namespace Lit\Http\Controllers\Crud;

use Illuminate\Database\Eloquent\Builder;
use App\Models\Inventory;
use App\Models\Project;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\Http\Request;
use Ignite\Support\Facades\Form;
use Ignite\Crud\Controllers\CrudController;

class InventoryController extends CrudController
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
        // return $user->can("{$operation} inventories");
        return true;
    }

    public function byid(Request $request) {      

## Get Tax Config
        $printing_settings = Form::load('settings', 'printing_settings'); 
        $tax_1 = floatval($printing_settings->tax_1);
        $tax_2 = floatval($printing_settings->tax_2);
        
        
        if($request->show == 'show'){
            
            
            if($request->inventory_id == 2){
               
                $percent = $request->upcharge_percent;
                $shipping_cost = $request->shipping_cost;           
                $base_cost = $request->fourover_price;
                $upcharge_amount = $base_cost * ($percent / 100);
                $subtotal = $base_cost + $upcharge_amount + $shipping_cost;
                $tax_1_total = $subtotal * ($tax_1 / 100);
                $tax_2_total = $subtotal * ($tax_2 / 100);
                $tax_total = $tax_1_total + $tax_2_total;
                $total_price = $tax_total + $subtotal;
    
                
                $stock['base_cost'] = number_format($base_cost, 2);
                $stock['upcharge_amount'] = number_format($upcharge_amount, 2);
                $stock['subtotal'] = number_format($subtotal, 2);
                $stock['tax_1_total'] = number_format($tax_1_total, 2);
                $stock['tax_2_total'] = number_format($tax_2_total, 2);
                $stock['tax'] = number_format($tax_total, 2);
                $stock['total_cost'] = number_format($total_price, 2);
                $stock['shipping_cost'] = number_format($shipping_cost, 2);
                
                return json_encode($stock);
                
            } else {
                
                    
    ### Fetch Original Project Object
                $project_item = Project::find($request->project_id);


                if($request->qty == 1) {
                    $inventory_id = $project_item->inventory_id;
                    $qty = $project_item->qty;
                        
        //                print_r($request->all());
                    $sides =  $project_item->sides;
                    $per_sheet =  $project_item->per_sheet;
                    $upcharge_percent = $project_item->upcharge_percent;
                    
                } else {
                    $inventory_id = $project_item->inventory_id;
                    $qty = $request->qty != $project_item->qty ? $request->qty : $project_item->qty;
                        
        //                print_r($request->all());
                    $sides = $request->sides != $project_item->sides ? $request->sides : $project_item->sides;
                    $per_sheet = $request->per_sheet != $project_item->per_sheet ? $request->per_sheet : $project_item->per_sheet;
                    $upcharge_percent = $request->upcharge_percent != $project_item->upcharge_percent ? $request->upcharge_percent : $project_item->upcharge_percent;
                }             
                
    ### Fetch Original Constants            
                $tax_1 = $project_item->tax_1;
                $tax_2 = $project_item->tax_2;
                $total_tax = $tax_1 + $tax_2;
                
                $toner_cost = floatval($project_item->printer_base);
                $paper_cost = floatval($project_item->paper_base);
                $multiplier = floatval($project_item->multiplier);
    
    ### If user changes inventory, fetch and set new constants.
                if($request->inventory_id != $project_item->inventory_id && $request->action != 'show'){
                    $inventory_item = Inventory::find($request->inventory_id);
                    $inventory_id = $inventory_item->id;
                    $paper_cost = $inventory_item->current_per_sheet;
                    $multiplier = $inventory_item->multiplier;
                }
    
               $percent = $upcharge_percent;
               
    ### Do Math for Base Costs 
                $toner_cost_sides = ($toner_cost * $sides) * $multiplier;
                $per_page_cost = $toner_cost_sides + $paper_cost;            
                $new_base_cost = $qty * $per_page_cost; 
    
    ### Math for Sub/Tox/Totals            
                $base_cost = $new_base_cost / $per_sheet;
                $upcharge_amount = $base_cost * ($percent / 100);
                $subtotal = $base_cost + $upcharge_amount;
                $tax_1_total = $subtotal * ($tax_1 / 100);
                $tax_2_total = $subtotal * ($tax_2 / 100);
                $tax_total = $tax_1_total + $tax_2_total;
                $total_price = $tax_total + $subtotal;
                
    ### Package it and send it back
                $stock['base_cost'] = number_format($base_cost, 2);
                $stock['upcharge_amount'] = number_format($upcharge_amount, 2);
                $stock['subtotal'] = number_format($subtotal, 2);
                $stock['tax_1_total'] = number_format($tax_1_total, 2);
                $stock['tax_2_total'] = number_format($tax_2_total, 2);
                $stock['tax'] = number_format($tax_total, 2);
                $stock['total_cost'] = number_format($total_price, 2);
                $stock['shipping_cost'] = number_format(0, 2);
                $stock['show'] = $request->show;
                
                $stock['sides'] = $sides;
                $stock['per_sheet'] = $per_sheet;
                $stock['upcharge_percent'] = $upcharge_percent;
                $stock['inventory_id'] = $inventory_id;
                $stock['qty'] = $qty;            
                $stock['show'] = $request->show;
                
               return json_encode($stock);
           }
       }
        
        if($request->inventory_id == 2){
           
            $percent = $request->upcharge_percent;
            $shipping_cost = $request->shipping_cost;           
            $base_cost = $request->fourover_price;
            $upcharge_amount = $base_cost * ($percent / 100);
            $subtotal = $base_cost + $upcharge_amount + $shipping_cost;
            $tax_1_total = $subtotal * ($tax_1 / 100);
            $tax_2_total = $subtotal * ($tax_2 / 100);
            $tax_total = $tax_1_total + $tax_2_total;
            $total_price = $tax_total + $subtotal;

            
            $stock['base_cost'] = number_format($base_cost, 2);
            $stock['upcharge_amount'] = number_format($upcharge_amount, 2);
            $stock['subtotal'] = number_format($subtotal, 2);
            $stock['tax_1_total'] = number_format($tax_1_total, 2);
            $stock['tax_2_total'] = number_format($tax_2_total, 2);
            $stock['tax'] = number_format($tax_total, 2);
            $stock['total_cost'] = number_format($total_price, 2);
            $stock['shipping_cost'] = number_format($shipping_cost, 2);
            
            return json_encode($stock);
            
        } else {

            $inventory_item = Inventory::find($request->inventory_id);
                
            $inventory_id = $request->inventory_id;
            $qty = floatval($request->qty);
            $sides = floatval($request->sides);
            $per_sheet = floatval($request->per_sheet);
            $upcharge_percent = floatval($request->upcharge_percent);
            
            $qty = floatval($request->qty);
            $sides = floatval($request->sides);
            $per_sheet = floatval($request->per_sheet);
            $toner_cost = floatval($printing_settings->current_toner_cost);
            $paper_cost = floatval($inventory_item->current_per_sheet);
            
            $multiplier = floatval($inventory_item->multiplier);
            
            $toner_cost_sides = ($toner_cost * $sides) * $multiplier;
            $per_page_cost = $toner_cost_sides + $paper_cost;
            $new_base_cost = number_format($qty * $per_page_cost, 2); 
            
            $base_cost = number_format($new_base_cost, 2) / $per_sheet;
            $upcharge_amount = $base_cost * ($upcharge_percent / 100);
            $subtotal = $base_cost + number_format($upcharge_amount, 2);
            $tax_1_total = $subtotal * ($tax_1 / 100);
            $tax_2_total = $subtotal * ($tax_2 / 100);
            $tax_total = $tax_1_total + $tax_2_total;
            $total_price = number_format($tax_total + $subtotal, 2);
            
            
            $stock['base_cost'] = number_format($base_cost, 2);
            $stock['upcharge_amount'] = number_format($upcharge_amount, 2);
            $stock['subtotal'] = number_format($subtotal, 2);
            $stock['tax_1_total'] = number_format($tax_1_total, 2);
            $stock['tax_2_total'] = number_format($tax_2_total, 2);
            $stock['tax'] = number_format($tax_total, 2);
            $stock['total_cost'] = number_format($total_price, 2);
            $stock['shipping_cost'] = number_format(0, 2);
            $stock['show'] = $request->show;
            
            
            
            return json_encode($stock);
            
        }

        
    }
    
    // public function fillOnStore($post)
    // {
    //     // $all_stock = Inventory::find($post->id)->stock;
    //     // echo('store');
    //     // print_r($all_stock);
    //     // print_r($post);
    //     // $post->author_id = lit_user()->id;
    // }

    // public function fillOnUpdate($post)
    // {
    //     // $one_stock = Inventory::find($post->id)->newStock;
    //     // echo('------------- update ------------------');
    //     // print_r($one_stock);
    //     //       print_r($post);
    // }
}
