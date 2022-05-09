<?php

namespace Lit\Config\Crud;

use Ignite\Crud\CrudShow;

use Ignite\Crud\CrudIndex;
use Ignite\Crud\Config\CrudConfig;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;

use Ignite\Support\Vue\InfoComponent;
use App\Models\HowTo;
use Lit\Http\Controllers\Crud\HowToController;

class HowToConfig extends CrudConfig
{
    /**
     * Model class.
     *
     * @var string
     */
    public $model = HowTo::class;

    /**
     * Controller class.
     *
     * @var string
     */
    public $controller = HowToController::class;

    /**
     * Model singular and plural name.
     *
     * @param HowTo|null howTo
     * @return array
     */
    public function names(HowTo $howTo = null)
    {
        return [
            'singular' => 'How-To',
            'plural'   => 'How-To',
        ];
    }

    /**
     * Get crud route prefix.
     *
     * @return string
     */
    public function routePrefix()
    {
        return 'how-tos';
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
            $table->col('Category')->value('{category}')->sortBy('category');

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
        $page->title('');
        $page->view('howto')->when('closed', true);
            
        $page->card(function($form) {
    
            $form->input('title');
            $form->select('category')
                ->options([
                    "Network" => 'Network',
                    "Website" => 'Website',
                    "Code"    => 'Code',
                    "Hardware"=> 'Hardware',
                    "WordPress"=>'WordPress',
                    "WP-Theme"=> "WP-Theme",
                    "WP-Plugins" => "WP-Plugins"
                ]);
            $form->wysiwyg('objective');
            $form->boolean('closed')
                ->title('Closed')
                ->hint('Toggle to close the HowTo from being edited.')
                ->width(1/3);    
        })->whenNot('closed', true);
            
            
        $page->card(function($form) {
        
            $form->block('howtosteps')
                ->title('Steps')
                ->repeatables(function($repeatables) {
               
                // Add as many repeatables as you want.
                $repeatables->add('Step', function($form, $preview) {
                    // // The block preview.
                    $preview->col('{stepnumber}');
                    // Containing as many form fields as you want.
                    $form->input('stepnumber')->title("Step Number")->type('number');
                    $form->wysiwyg('stepdescription')->title("Step Description");
                    $form->image('image')->title('Screen Shot')->expand()->maxFiles(1);
                });
            });   
        })->whenNot('closed', true);
       
    }
}
