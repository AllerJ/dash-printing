<?php

namespace Lit\Actions;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class StatusInProcess
{
    /**
     * Run the action.
     *
     * @param  Collection  $models
     * @return JsonResponse
     */
    public function run(Collection $models)
    {
        $models[0]->status = 'Pending';
        $models[0]->save();
        
        return redirect('/admin/projects');
    }
}
