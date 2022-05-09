<?php

namespace Lit\Actions;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class RestockStatusOrdered
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
		$post->status = 'Ordered';
		$post->save();
		return redirect('/admin/restock-logs');
	}
        
}
