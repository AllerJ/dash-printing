<?php

namespace Lit\Config\Crud;

use Ignite\Crud\CrudShow;
use Ignite\Crud\CrudIndex;
use Ignite\Crud\Config\CrudConfig;
use Illuminate\Support\Str;
use App\Models\Inventory;
use App\Models\Client;
use App\Models\Project;
use Lit\Actions\StatusInProcess;
use Lit\Actions\StatusPrinted;
use Lit\Actions\StatusDelivered;
use Lit\Actions\CreateInvoice;
use Lit\Actions\DuplicateRecord;
use Lit\Actions\BulkInvoice;
use Lit\Config\Crud\ClientConfig;
use Ignite\Support\Facades\Form;

use Lit\Http\Controllers\Crud\ProjectController;

class ProjectConfig extends CrudConfig
{
    /**
     * Model class.
     *
     * @var string
     */
    public $model = Project::class;

    /**
     * Controller class.
     *
     * @var string
     */
    public $controller = ProjectController::class;

    /**
     * Model singular and plural name.
     *
     * @param Project|null project
     * @return array
     */
    public function names(Project $project = null)
    {
        return [
            'singular' => 'Project',
            'plural'   => 'Projects',
        ];
    }

    /**
     * Get crud route prefix.
     *
     * @return string
     */
    public function routePrefix()
    {
        return 'projects';
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

          $table->col('Status')->value('status', [
            'Pending' => '<i class="fas fa-spinner text-danger"></i>',
            'Printed' => '<i class="fas fa-print text-primary"></i>',
            'Delivered' => '<i class="fas fa-truck text-success"></i>',
            ], '{state}')->sortBy('status')->small();
          
            $table->col('Client')->value('{client_name}');
            $table->col('Project')->value('{project_title}')->sortBy('created_at');          

          
          
                     
            $table->col('Price')->value('${total}')->small();
            $table->action('Invoice', CreateInvoice::class)->when('invoice_id', '0')->small();
            $table->actions([
              'Status: Pending' => StatusInProcess::class,
              'Status: Printed' => StatusPrinted::class,
              'Status: Delivered' => StatusDelivered::class,
              'Duplicate Project' => DuplicateRecord::class,
            ]);
        })->action('Combine in 1 Invoice', BulkInvoice::class)->perPage('50');
    }

    /**
     * Setup show page.
     *
     * @param \Ignite\Crud\CrudShow $page
     * @return void
     */
    public function show(CrudShow $page)
    {
//        $page->bindToView($data);

        // $page->view('blank', compact($page));
        $page->view('blank');
        
        $page->card(function ($form) {
            $form->input('project_title');
            $form->radio('client_type')
            ->title('Client')
            ->options([
                'new' => 'New',
                'existing' => 'Existing',
            ]);
            $form->select('client_id')
            ->title('Client')
            ->options(
                Client::where('deleted_at', '=', null)->get()->sortBy('name')->mapWithKeys(function ($item, $key) {
                    return [$item->id => $item->name];
                })->toArray()
            )->when('client_type', 'existing');

            $form->input('client_name')->when('client_type', 'new');
            $form->input('client_email')->when('client_type', 'new');
        });
        
        $page->card(function ($form) {
            $form->select('inventory_id')
                ->title('Inventory')
                ->options(
                    Inventory::where('type', '!=', 'toner')->get()->mapWithKeys(function ($item, $key) {
                       // return [$item->id => $item->title." (|".$item->current_per_sheet."|".$item->multiplier."|)"];
                       return [$item->id => $item->title];
                    })->toArray()
                )->width(6);

            $form->input('qty')->title('Quantity')->hint('Total finished pieces needed?')->placeholder('')->width(6)->whenNot('inventory_id', '2');

            $form->input('fourover_id')
                ->title('4 Over Order ID')
                ->placeholder('')
                ->when('inventory_id', '2')->width(6);
            
            $form->input('fourover_price')->title('4Over Price')->placeholder('0')->prepend('$')->when('inventory_id', '2');  
            $form->input('shipping_cost')->title('Shipping')->placeholder('0')->prepend('$')->when('inventory_id', '2');  
            $form->input('finished_size')->append('L" x W"')->width(6)->whenNot('inventory_id', '2');
            $form->input('per_sheet')->hint('How many will be printed on a single sheet of inventory?')->width(6)->whenNot('inventory_id', '2');
            
            $form->radio('sides')
                ->title('Printed Sides')
                ->options([
                    '1' => '1',
                    '2' => '2',
                ])->hint('How many sides will be printed on?')->width(6)->whenNot('inventory_id', '2');
                
            $form->input('upcharge_percent')
                ->title('Upcharge Percent')
                ->placeholder('')
                ->append('%')
                ->width(6);  
        })->width(8);
        

        $page->card(function ($form) {
          $printing_settings = Form::load('settings', 'printing_settings');
          $total_tax = $printing_settings->tax_1+$printing_settings->tax_2;
          $form->info('Invoice')->class('invoice-card')
            ->text('Base Cost: <span class="right">$<span id="base_cost"></span></span>')
  //          ->text('Finishing Cost: <span class="right">$<span id="finishing_cost"></span></span>')
            ->text('Shipping Cost:<span class="right">$<span id="shipping_cost"></span></span>', 'hidden')
            ->text('Upcharge Amt:<span class="right">$<span id="upcharge_amount"></span></span>')
            ->text('<b>Subtotal:</b><b><span class="right">$<span id="subtotal"></span></span><b>')
            ->text('<span>State Tax: (<span id="tax_1">'.$printing_settings->tax_1.'</span>%)</span> <span class="right">$<span id="tax_1_total"></span></span>')
            ->text('<span>Local Tax: (<span id="tax_2">'.$printing_settings->tax_2.'</span>%)</span> <span class="right">$<span id="tax_2_total"></span></span>')
            ->text('<span>Total Tax: ('.$total_tax.'%)</span> <span class="right">$<span id="tax"></span></span>')
            ->text('<b>Total Retail Cost:</b><b><span class="right">$<span id="total_cost"></span></span></b>')
            ;
          })->secondary()->width(4);
          
          $page->card(function ($form) {
            $form->group(function($form) {
              $form->textarea('note')->title('Notes about the project')->rows('7')->placeholder('');
            })->width(6);
            $form->group(function($form) {
              $form->input('date_reserved')->type('date')->title('Date Reserved')->hint('Date this project should be printed on, or printed by.')->width(6);
              $form->input('invoice_id')->title('Invoice Number')->width(6);
              $form->input('file')->title('File Location/Name')->hint('OwnCloud File Location and File Name')->placeholder('');
            })->width(6);
          });
        
    }
}
