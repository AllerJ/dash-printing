<?php

namespace Lit\Http\Controllers\Crud;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Ignite\Crud\Controllers\CrudController;
use Ignite\Support\Facades\Form;
use App\Models\Inventory;
use App\Models\Client;

class ProjectController extends CrudController
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
        // return $user->can("{$operation} projects");
        return true;
    }
    
    public function fillOnUpdate($post, $attributes){
        
        $printing_settings = Form::load('settings', 'printing_settings'); 
        $post->shipping_cost ??= 0;
        $post->qty ??= 0;
        $post->sides ??= 1;
        $post->per_sheet ??= 1;
        $post->upcharge_percent ??= 0;
                
        $attributes['sides'] ??= $post->sides;
        $attributes['qty'] ??= $post->qty;
        $attributes['per_sheet'] ??= $post->per_sheet;
        $attributes['upcharge_percent'] ??= $post->upcharge_percent;
        
        $tax_1 = floatval($post->tax_1);
        $tax_2 = floatval($post->tax_2);
        $total_tax = $tax_1 + $tax_2;
        $post->upcharge_percent = floatval($attributes['upcharge_percent']);
        $percent = floatval($post->upcharge_percent);
        $post->shipping_cost = floatval($post->shipping_cost);
        $shipping_cost = floatval($post->shipping_cost);
        
        if($post->inventory_id == 2){
            
            $base_cost = floatval($post->fourover_price);
            $upcharge_amount = $base_cost * ($percent / 100);
            $subtotal = $base_cost + $upcharge_amount + $shipping_cost;
            $tax_1_total = $subtotal * ($tax_1 / 100);
            $tax_2_total = $subtotal * ($tax_2 / 100);
            $tax_total = $tax_1_total + $tax_2_total;
            $total_price = $tax_total + $subtotal;
            
            $post->base_cost = number_format($base_cost, 4);
            $post->upcharge_amount = number_format($upcharge_amount, 4);
            $post->subtotal = number_format($subtotal, 2);
            $post->tax1_amount = number_format($tax_1_total, 3);
            $post->tax2_amount = number_format($tax_2_total, 3);
            $post->tax = number_format($tax_total, 3);
            $post->total = number_format($total_price, 2);
            $post->shipping_cost = number_format($shipping_cost, 2);
        //    $post->status = "Pending";
            
        } else {

            if(isset($attributes['inventory_id'])){
                $inventory_item = Inventory::find($attributes['inventory_id']);
                $paper_cost = floatval($inventory_item->current_per_sheet);
                $multiplier = floatval($inventory_item->multiplier);
            } else {
                $paper_cost = floatval($post->paper_base);
                $multiplier = floatval($post->multiplier);
            }
            
            if(!empty($attributes['client_name'])) {
                
                $client = [
                    'name' => $post->client_name,
                    'email' => $post->client_email,
                    'contact_email' => $post->client_email,
                ];
                
                $new_client = $this->dashboard_api('/api/v1/clients', 'POST', $client);
                $post->client_id = $new_client->id;
                
                
            } else {
                $client = Client::find($post->client_id);   
                $post->client_name = $client->name;
                $post->client_email = $client->email;
            }
            
            $attributes['paper_base'] ??= $post->paper_base;
            
            $qty = floatval($attributes['qty']);
            $sides = floatval($attributes['sides']);
            $per_sheet = floatval($attributes['per_sheet']);
            $toner_cost = floatval($post->printer_base);
              
            $toner_cost_sides = ($toner_cost * $sides) * $multiplier;
            $per_page_cost = $toner_cost_sides + $paper_cost;
            $new_base_cost = $qty * $per_page_cost; 
            
            $base_cost = $new_base_cost / $per_sheet;
            $upcharge_amount = $base_cost * ($percent / 100);
            $subtotal = $base_cost + $upcharge_amount;
            $tax_1_total = $subtotal * ($tax_1 / 100);
            $tax_2_total = $subtotal * ($tax_2 / 100);
            $tax_total = $tax_1_total + $tax_2_total;
            $total_price = $tax_total + $subtotal;
            
            
            $post->base_cost = number_format($base_cost, 2);
            $post->upcharge_amount = number_format($upcharge_amount, 2);
            $post->subtotal = number_format($subtotal, 2);
            $post->tax1_amount = number_format($tax_1_total, 2);
            $post->tax2_amount = number_format($tax_2_total, 2);
            $post->tax = number_format($tax_total, 2);
            $post->total = number_format($total_price, 2);
            $post->shipping_cost = number_format($shipping_cost, 2);
      //      $post->status = "Pending";
            
        }
        
       
      
    }
    public function fillOnStore($post)
    {

        if($post->client_type == 'new') {
            
            $client = [
                'name' => $post->client_name,
                'email' => $post->client_email,
                'contact_email' => $post->client_email,
            ];
            
            $new_client = $this->dashboard_api('/api/v1/clients', 'POST', $client);
            $post->client_id = $new_client->id;
            
            
        } else {
            $client = Client::find($post->client_id);   
            $post->client_name = $client->name;
            $post->client_email = $client->email;
        }

        $printing_settings = Form::load('settings', 'printing_settings'); 
        $post->shipping_cost ??= 0;
        $post->qty ??= 0;
        $post->sides ??= 1;
        $post->per_sheet ??= 1;
        
        $tax_1 = floatval($printing_settings->tax_1);
        $tax_2 = floatval($printing_settings->tax_2);
        
        $post->tax_1 = $tax_1;
        $post->tax_2 = $tax_2;
       
        
        $total_tax = $tax_1 + $tax_2;
        $post->upcharge_percent = floatval($post->upcharge_percent);
        $percent = floatval($post->upcharge_percent);
        $post->shipping_cost = floatval($post->shipping_cost);
        $shipping_cost = floatval($post->shipping_cost);
        
        if($post->inventory_id == 2){
            
            $base_cost = $post->fourover_price;
            $upcharge_amount = $base_cost * ($percent / 100);
            $subtotal = $base_cost + $upcharge_amount + $shipping_cost;
            $tax_1_total = $subtotal * ($tax_1 / 100);
            $tax_2_total = $subtotal * ($tax_2 / 100);
            $tax_total = $tax_1_total + $tax_2_total;
            $total_price = $tax_total + $subtotal;
            
            $post->base_cost = number_format($base_cost, 2);
            $post->upcharge_amount = number_format($upcharge_amount, 2);
            $post->subtotal = number_format($subtotal, 2);
            $post->tax1_amount = number_format($tax_1_total, 2);
            $post->tax2_amount = number_format($tax_2_total, 2);
            $post->tax = number_format($tax_total, 2);
            $post->total = number_format($total_price, 2);
            $post->shipping_cost = number_format($shipping_cost, 2);
            $post->status = "Pending";
            
        } else {

            $inventory_item = Inventory::find($post->inventory_id);
            
            $post->printer_base = $printing_settings->current_toner_cost;
            $post->paper_base = $inventory_item->current_per_sheet;
            
            $qty = floatval($post->qty);
            $sides = floatval($post->sides);
            $per_sheet = floatval($post->per_sheet);
            $toner_cost = floatval($printing_settings->current_toner_cost);
            $paper_cost = floatval($inventory_item->current_per_sheet);
            
            $multiplier = floatval($inventory_item->multiplier);
            $post->multiplier = $multiplier;
            
            $toner_cost_sides = ($toner_cost * $sides) * $multiplier;
            $per_page_cost = $toner_cost_sides + $paper_cost;
            $new_base_cost = $qty * $per_page_cost; 
            
            $base_cost = $new_base_cost / $per_sheet;
            $upcharge_amount = $base_cost * ($percent / 100);
            $subtotal = $base_cost + $upcharge_amount;
            $tax_1_total = $subtotal * ($tax_1 / 100);
            $tax_2_total = $subtotal * ($tax_2 / 100);
            $tax_total = $tax_1_total + $tax_2_total;
            $total_price = $tax_total + $subtotal;
            
            $post->base_cost = number_format($base_cost, 4);
            $post->upcharge_amount = number_format($upcharge_amount, 2);
            $post->subtotal = number_format($subtotal, 2);
            $post->tax1_amount = number_format($tax_1_total, 2);
            $post->tax2_amount = number_format($tax_2_total, 2);
            $post->tax = number_format($tax_total, 2);
            $post->total = number_format($total_price, 2);
            $post->shipping_cost = number_format($shipping_cost, 2);
            $post->status = "Pending";
            
        }
        


    }
    
    public function dashboard_api($endpoint, $action, $payload){
        $my_ch = curl_init();
        
        curl_setopt_array($my_ch, array(
            CURLOPT_URL => "https://dashboard.dashcg.com".$endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $action,
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => array(
                "Accept: application/json",
                "Content-Type: application/json",
                "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiMGY4ZTlmYmVmMzViYTU5ZmI3ZGJjMTQwMTBmNTQ5MjY2YTg5NGNlMDcwOTk2ZTIxZjlhNGRlOTdkNWY3NDE1MmVlZTBiOTE3YTQzNGY2OTUiLCJpYXQiOjE2MDMyOTkzNjQsIm5iZiI6MTYwMzI5OTM2NCwiZXhwIjoxNjM0ODM1MzY0LCJzdWIiOiIzIiwic2NvcGVzIjpbXX0.gE5gROI0k8SYTJCa--8VFjhCATv9edPLCJB2N23zn1OHKLVfN-vCMWTy1q2Qox1YtqBANgwy2aLMg3oeuP3fsjdgBXdFSLKUVhnhddCA3Ccg7P4FEhbUVOK8VmUCXXpVm6m7-6VeDiJ1IpshZctmyFTGgB-bdFdw_dDPsY9OQk5MW2ySenYb_WQxWPN43I-jCXHnHKOFfrCere4fppP0nHImqi0QeKFV5tE_XUeGY_sG3MLG3SPeU0V19k5wohNS5GKtS4dskB3XGV2cdZjkQlBpP100jiBhZwRsP7ptAmT9Sc11TML8J9QDNEzWARZuYHuXnaEceg4xn1rBF_tLa3UZugb1WrwqtECmhqXKhy-kMmiG2F-TkrE5ZYEJ9AqTTAKjXEAE53cgWiqWAHeTNr2KZFiExsz7WSOt5gxdJmr-duT3OjjEtg5oq9H7VcP0yUiu-lHbcF2rJ8tBke9ASfedEXYxDpmA_s31xz-jBc1ZADXlizM9g3wKadpE3EWhh-KXc7BIzy26JgQNYogzu6fXyB5pj1Rnq37XNnLzvhkzrW4apEPUNhNtZH7NP9Dsgag-Afz_IKsFL4UfmdJKWCtJwcTZ6bIkdKKMqhcXTGHCGL2wVJqTNof0KAVjJlUxzfkYA2o0hnqWQeEpl6gkDsPkr0F1Z-HSpij_FZIiLO0"
            ),
        
        ));
        $final_results = curl_exec($my_ch);
        $err = curl_error($my_ch);
        curl_close($my_ch);
        $result_json = json_decode($final_results);
        return $result_json;
    }
}
