<?php

namespace Lit\Config\Crud;

use Ignite\Crud\CrudShow;
use Ignite\Crud\CrudIndex;
use Ignite\Crud\Config\CrudConfig;
use Illuminate\Support\Str;
use Ignite\Support\Facades\Lit;
use App\Models\Inventory;
use Lit\Http\Controllers\Crud\InventoryController;
use Lit\Repeatables\StockRepeatable;

Lit::style('/css/custom.css');
class InventoryConfig extends CrudConfig
{
    /**
     * Model class.
     *
     * @var string
     */
    public $model = Inventory::class;

    /**
     * Controller class.
     *
     * @var string
     */
    public $controller = InventoryController::class;

    /**
     * Model singular and plural name.
     *
     * @param Inventory|null inventory
     * @return array
     */
    public function names(Inventory $inventory = null)
    {
        return [
            'singular' => 'Inventory',
            'plural'   => 'Inventories',
        ];
    }

    /**
     * Get crud route prefix.
     *
     * @return string
     */
    public function routePrefix()
    {
        return 'inventories';
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
            $table->col('Title')->value('{title}')->sortBy('title');
            $table->col('Qty On Hand')->value('current_qty');
            $table->col('Per Sheet')->value('current_per_sheet');
        })->search('id')->perPage('50');
    }

    /**
     * Setup show page.
     *
     * @param \Ignite\Crud\CrudShow $page
     * @return void
     */
    public function show(CrudShow $page)
    {
        $page->card(function ($form) {
            $form->input('title')->width(4);
            $form->input('current_qty')
                ->title('Quantity On Hand')
                ->placeholder('')
                ->width(4);
            $form->input('low_warning')
                ->title('Low Stock Alert')
                ->placeholder('')
                ->width(4);
            $form->input('current_per_sheet')->title('Price Per')->placeholder('')->width(4);
            $form->select('multiplier')
            ->title('Multiplier')
            ->options([
                1 => 'Letter',
                2 => 'Tabloid'
            ])
            ->width(4);
            $form->select('type')
            ->title('Type')
            ->options([
                'Paper',
                'Toner',
                '4Over'
            ])
            ->width(4);

          
        });
    }
}
