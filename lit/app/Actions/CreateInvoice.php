<?php

namespace Lit\Actions;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Ignite\Support\Facades\Form;
use App\Models\Inventory;
use Ignite\Page\Actions\ActionModal;

class CreateInvoice
{
    
    public function modal(ActionModal $modal)
    {
        $modal->confirmVariant('primary')
            ->confirmText('Create Invoice')
            ->message('Are you sure you are ready to create an invoice for the client??');
    }



    /**
     * Run the action.
     *
     * @param  Collection  $models
     * @return JsonResponse
     */
    public function run(Collection $models)
    {
        $post = $models[0];
     
        $printing_settings = Form::load('settings', 'printing_settings'); 
        
        $tax_1 = floatval($printing_settings->tax_1);
        $tax_2 = floatval($printing_settings->tax_2);
        $total_tax = $tax_1 + $tax_2;

        $inventory_item = Inventory::find($post->inventory_id);

        $line_item = [
            'quantity' => '1',
            'unit_cost' =>  $post->subtotal,
            'name' => $post->project_title,
            'tax' => $post->tax,
            'tax_rate' => $total_tax,
            'description' => 'Qty:'.$post->qty . ' - ' . $inventory_item->title . ' - ' . $post->finished_size
        ];
        
        $invoice = [
            'client_id' => $post->client_id,
            'currency' => 'USD',
            'notes' => 'Invoice valid for 30 days. Printing price is not gauranted if paid after 30 days. Invoice subject to late fee if paid after 30 days. Print production does not commence until invoice is paid.',
           
            'line_items' => [
                $line_item
            ]
        ];
            
        $new_invoice = $this->dashboard_api('/api/v1/invoices', 'POST', $invoice);
        $post->invoice_id = $new_invoice->id;
        $post->save();
        

        return response()->success('Action executed.');
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
