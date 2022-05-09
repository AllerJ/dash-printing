<?php

namespace Lit\Config\Crud;

use Ignite\Crud\CrudShow;
use Ignite\Crud\CrudIndex;
use Ignite\Crud\Config\CrudConfig;
use Illuminate\Support\Str;
use Lit\Http\Controllers\Crud\ClientController;
use App\Models\Client;
use Lit\Http\Controllers\Crud\FinishingController;

class ClientConfig extends CrudConfig
{
    /**
     * Model class.
     *
     * @var string
     */
    public $model = Client::class;

    /**
     * Controller class.
     *
     * @var string
     */
    public $controller = ClientController::class;

    /**
     * Model singular and plural name.
     *
     * @param Finishing|null finishing
     * @return array
     */
    public function names(Client $client = null)
    {
        return [
            'singular' => 'Client',
            'plural'   => 'Clients',
        ];
    }

    /**
     * Get crud route prefix.
     *
     * @return string
     */
    public function routePrefix()
    {
        return 'clients';
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
