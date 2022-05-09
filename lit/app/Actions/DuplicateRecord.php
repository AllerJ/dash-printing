<?php

namespace Lit\Actions;

use Carbon\Carbon;
use App\Models\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Ignite\Page\Actions\ActionModal;
use Ignite\Support\AttributeBag;

class DuplicateRecord
{
    
    public function modal(ActionModal $modal)
    {
        $modal->form(function($form) {
            $form->input('project_title')->title('New Project Title');
            $form->radio('client_type')
                ->title('Client')
                ->options([
                    'new' => 'New',
                    'existing' => 'Existing',
                ]);
            $form->select('client_id')
                ->title('Client')
                ->options(
                    Client::where('deleted_at', '=', null)->get()->sortBy('name')->mapWithKeys(function ($item, $key) {
                        return [$item->id => $item->name];
                    })->toArray()
                )->when('client_type', 'existing');

            $form->input('client_name')->when('client_type', 'new');
            $form->input('client_email')->when('client_type', 'new');
            
        });
    }
    
    
    /**
     * Run the action.
     *
     * @param  Collection  $models
     * @return JsonResponse
     */
    public function run(Collection $models, AttributeBag $attributes)
    {
        $post = $models[0];

        $newPost = $post->replicate();
    
    
        if($attributes->client_type == 'new') {
        
            $client = [
                'name' => $attributes->client_name,
                'email' => $attributes->client_email,
                'contact_email' => $attributes->client_email,
            ];
            
            $new_client = $this->dashboard_api('/api/v1/clients', 'POST', $client);
            $post->client_id = $new_client->id;
            
            
        } else {
            $client = Client::find($attributes->client_id);   
            $attributes->client_name = $client->name;
            $attributes->client_email = $client->email;
            
        }
        
        
        $newPost->created_at = Carbon::now();
        $newPost->status = 'Pending';
        $newPost->invoice_id = '0';
        $newPost->project_title = $attributes->project_title;
        $newPost->client_type = $attributes->client_type;
        $newPost->client_id = $attributes->client_id;
        $newPost->client_email = $attributes->client_email;
        $newPost->client_name = $attributes->client_name;
        
            
    
    
       $newPost->save();
    
    

       return redirect('/admin/projects');
    }
}
