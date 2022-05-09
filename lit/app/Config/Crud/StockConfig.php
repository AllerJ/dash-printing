<?php

namespace Lit\Config\Crud;

use Ignite\Crud\CrudShow;
use Ignite\Crud\CrudIndex;
use Ignite\Crud\Config\CrudConfig;
use Illuminate\Support\Str;

use App\Models\Stock;
use Lit\Http\Controllers\Crud\StockController;

class StockConfig extends CrudConfig
{
    /**
     * Model class.
     *
     * @var string
     */
    public $model = Stock::class;

    /**
     * Controller class.
     *
     * @var string
     */
    public $controller = StockController::class;

    /**
     * Model singular and plural name.
     *
     * @param Stock|null stock
     * @return array
     */
    public function names(Stock $stock = null)
    {
        return [
            'singular' => 'Stock',
            'plural'   => 'Stocks',
        ];
    }

    /**
     * Get crud route prefix.
     *
     * @return string
     */
    public function routePrefix()
    {
        return 'stocks';
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

        })->search('title');  
    }

    /**
     * Setup show page.
     *
     * @param \Ignite\Crud\CrudShow $page
     * @return void
     */
    public function show(CrudShow $page)
    {
        $page->card(function($form) {

            $form->input('title');
            
        });
    }
}
