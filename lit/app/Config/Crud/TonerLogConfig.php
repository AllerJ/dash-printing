<?php

namespace Lit\Config\Crud;

use Ignite\Support\Facades\Lit;
use Ignite\Crud\CrudShow;
use Ignite\Crud\CrudIndex;
use Ignite\Crud\Config\CrudConfig;
use Illuminate\Support\Str;
use App\Models\Inventory;
use Ignite\Support\Facades\Config;
use Lit\Config\Crud\InventoryConfig;
use App\Models\TonerLog;
use Lit\Http\Controllers\Crud\TonerLogController;

class TonerLogConfig extends CrudConfig
{
    /**
     * Model class.
     *
     * @var string
     */
    public $model = TonerLog::class;

    /**
     * Controller class.
     *
     * @var string
     */
    public $controller = TonerLogController::class;

    /**
     * Model singular and plural name.
     *
     * @param TonerLog|null tonerLog
     * @return array
     */
    public function names(TonerLog $tonerLog = null)
    {
        return [
            'singular' => 'Deplete Log',
            'plural'   => 'Deplete Logs',
        ];
    }

    /**
     * Get crud route prefix.
     *
     * @return string
     */
    public function routePrefix()
    {
        return 'toner-logs';
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

            $table->col('Inventory')->value('{inventory.title}');
            $table->date('created_at', 'm-d-Y')->label('Used On');
//            print_r($table);
            
        });  
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
            
            $form->radio('type')
            ->title(' ')
            ->options([
                'toner' => 'Toner',
                'paper' => 'Paper',
            ]);          
            
            $form->select('inventory_id')
            ->title('Select Toner')
            ->when('type', 'toner')
            ->options(
                Inventory::where('type', '=', 'toner')->get()->mapWithKeys(function ($item, $key) {
                    return [$item->id => $item->title];
                })->toArray()
            ); 
           
            $form->select('inventory_id')
            ->title('Select Paper')
            ->when('type', 'paper')
            ->options(
                Inventory::where('type', '=', 'paper')->get()->mapWithKeys(function ($item, $key) {
                    return [$item->id => $item->title];
                })->toArray()
            );
            
            
        });
        
        
        // ->onlyOnCreate(function($form) {
        //        return redirect('/admin/toner-logs');
        //     })
    }
}
