<?php

namespace Lit\Http\Controllers\Crud;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Ignite\Crud\Controllers\CrudController;
use App\Models\Inventory;


class TonerLogController extends CrudController
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
        // return $user->can("{$operation} toner_logs");
        return true;
    }
    
    public function fillOnStore($post)
    {
        $all_stock = Inventory::findOrFail($post->inventory_id);
        $all_stock->current_qty = $all_stock->current_qty - 1;
        $all_stock->save();
        

        $url='https://cloud.dashcg.com/getlinda.php';
        $linda=file_get_contents($url);
        $linda_json = json_decode($linda);
        $post->current_copy_count = $linda_json->counter;


    }
    
}
