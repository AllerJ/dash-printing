<?php

namespace Lit\Config\Crud;

use Ignite\Crud\CrudShow;
use Ignite\Crud\CrudIndex;
use Ignite\Crud\Config\CrudConfig;
use Illuminate\Support\Str;

use App\Models\Finishing;
use Lit\Http\Controllers\Crud\FinishingController;

class FinishingConfig extends CrudConfig
{
    /**
     * Model class.
     *
     * @var string
     */
    public $model = Finishing::class;

    /**
     * Controller class.
     *
     * @var string
     */
    public $controller = FinishingController::class;

    /**
     * Model singular and plural name.
     *
     * @param Finishing|null finishing
     * @return array
     */
    public function names(Finishing $finishing = null)
    {
        return [
            'singular' => 'Finishing',
            'plural'   => 'Finishings',
        ];
    }

    /**
     * Get crud route prefix.
     *
     * @return string
     */
    public function routePrefix()
    {
        return 'finishings';
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
             $form->input('price_per')->width(4);
             $form->input('qty')->width(4);
        });
    }
}
