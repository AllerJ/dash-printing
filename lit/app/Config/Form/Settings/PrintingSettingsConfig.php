<?php

namespace Lit\Config\Form\Settings;

use Ignite\Crud\Config\FormConfig;
use Ignite\Crud\CrudShow;
use Lit\Http\Controllers\Form\Settings\PrintingSettingsController;

class PrintingSettingsConfig extends FormConfig
{
    /**
     * Controller class.
     *
     * @var string
     */
    public $controller = PrintingSettingsController::class;

    /**
     * Form route prefix.
     *
     * @return string
     */
    public function routePrefix()
    {
        return "settings/printing";
    }

    /**
     * Form singular name. This name will be displayed in the navigation.
     *
     * @return array
     */
    public function names()
    {
        return [
            'singular' => 'Printing Settings',
        ];
    }

    /**
     * Setup form page.
     *
     * @param \Lit\Crud\CrudShow $page
     * @return void
     */
    public function show(CrudShow $page)
    {
        $page->card(function($form) {

           $form->input('current_toner_cost');
           $form->input('tax_1');
           $form->input('tax_2');
           $form->input('chocolate_subsidy')->title('Printer Recovery Fee (PRF)')->prepend('$')->hint('This is a per project fee for wear and tear on the printing equipment for jobs printed in house.');
           $form->input('subsidy_ratio')->title('PRF Ratio')->prepend('1:')->hint('How many clicks does the PRF cover? 1 PRF per 100 clicks. For example, if a job is less than 100 clicks the PRF is not added.');
           
        });
    }
}
