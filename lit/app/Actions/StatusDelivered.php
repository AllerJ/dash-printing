<?php

namespace Lit\Actions;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class StatusDelivered
{
    /**
     * Run the action.
     *
     * @param  Collection  $models
     * @return JsonResponse
     */
    public function run(Collection $models)
    {        
        $models[0]->status = 'Delivered';
        $models[0]->save();

        return redirect('/admin/projects');
    }
}
