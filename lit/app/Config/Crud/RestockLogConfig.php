<?php

namespace Lit\Config\Crud;

use Ignite\Crud\CrudShow;
use Ignite\Crud\CrudIndex;
use Ignite\Crud\Config\CrudConfig;

use Ignite\Support\Facades\Config;
use Lit\Config\Crud\InventoryConfig;

use Lit\Config\Crud\RestockLogConfig;

use Ignite\Support\Facades\Form;
use Lit\Actions\RestockStatusDelivered;
use Lit\Actions\RestockStatusNeeded;
use Lit\Actions\RestockStatusOrdered;

use Illuminate\Support\Str;
use App\Models\Inventory;
use App\Models\RestockLog;
use Lit\Http\Controllers\Crud\RestockLogController;

class RestockLogConfig extends CrudConfig
{
    /**
     * Model class.
     *
     * @var string
     */
    public $model = RestockLog::class;

    /**
     * Controller class.
     *
     * @var string
     */
    public $controller = RestockLogController::class;

    /**
     * Model singular and plural name.
     *
     * @param RestockLog|null restockLog
     * @return array
     */
    public function names(RestockLog $restockLog = null)
    {
        return [
            'singular' => 'Restock Log',
            'plural'   => 'Restock Logs',
        ];
    }

    /**
     * Get crud route prefix.
     *
     * @return string
     */
    public function routePrefix()
    {
        return 'restock-logs';
    }

    /**
     * Build index page.
     *
     * @param \Ignite\Crud\CrudIndex $page
     * @return void
     */
    public function index(CrudIndex $page)
    {
        $page->table(function ($table) {
//			$table->col('Status')->value('{status}')->sortBy('status');
            $table->col('Status')->value('status', [
                'Delivered' => '<div class="badge badge-success">Delivered</div>',
                'Ordered' => '<div class="badge badge-info">Ordered</div>',
                'Needed' => '<div class="badge badge-warning">Needed</div>',
            ], '<div class="badge badge-secondary">{state}</div>')->sortBy('status')->small();
            
            $table->col('Item')->value('{inventory.title}')->sortBy('inventory.title');
            $table->col('qty')->label('Qty')->value('{qty}')->small();
            $table->date('ordered_on', 'm-d-Y')->label('Delivery Date');
            $table->action(
              'Delivered?', RestockStatusDelivered::class
		  	)->when('status', 'Ordered');
        })->perPage('50');  
    }

    /**
     * Setup show page.
     *
     * @param \Ignite\Crud\CrudShow $page
     * @return void
     */
    public function show(CrudShow $page)
    {
        $url = '/admin/'. $this->routePrefix();
        $page->view('redirect', compact('url'));
        
        $page->card(function($form) {

            $form->select('inventory_id')
            ->title('Inventory')
            ->options(
                Inventory::where('type', '!=', '4Over')->get()->mapWithKeys(function ($item, $key) {
                    return [$item->id => $item->title];
                })->toArray()
            )->placeholder('Pick an Inventory Item')->width(6);
            $form->select('status')
            ->title('Restock Status')
            ->options(
				[
					'Ordered' => 'Ordered',
					'Needed' => 'Needed',
					'Delivered' => 'Delivered'
				]	
            )->creationRules('required')->placeholder('Set Status')->width(6);
            
        $form->input('qty')
            ->title('Quantity')->width(3);
            
        
        $form->input('cost')
            ->prepend('$')
            ->title('Price Each')->width(3);
        $form->input('yield')->type('number')
            ->title('Yield')->width(3);
        $form->input('per_sheet')
            ->title('Price Per Yield')->width(3);
        $form->input('ordered_on')->type('date')
            ->title('Restocked On')->width(6);
        $form->input('supplier')
            ->title('Supplier URL')->type('url')->width(6);
                        
        });
    }
}
