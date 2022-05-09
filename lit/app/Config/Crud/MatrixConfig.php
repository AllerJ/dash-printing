<?php

namespace Lit\Config\Crud;

use Ignite\Crud\CrudShow;
use Ignite\Crud\CrudIndex;
use Ignite\Crud\Config\CrudConfig;
use Illuminate\Support\Str;

use App\Models\Matrix;
use Lit\Http\Controllers\Crud\MatrixController;

class MatrixConfig extends CrudConfig
{
    /**
     * Model class.
     *
     * @var string
     */
    public $model = Matrix::class;

    /**
     * Controller class.
     *
     * @var string
     */
    public $controller = MatrixController::class;

    /**
     * Model singular and plural name.
     *
     * @param Matrix|null matrix
     * @return array
     */
    public function names(Matrix $matrix = null)
    {
        return [
            'singular' => 'Comparison',
            'plural'   => 'Comparison',
        ];
    }

    /**
     * Get crud route prefix.
     *
     * @return string
     */
    public function routePrefix()
    {
        return 'comparison';
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

            $table->col('Product')->value('{product}')->sortBy('product');
			$table->col('Specs')->value('<b>Qty:</b> {qty} <br><b>Size:</b> {size} <br><b>Stock:</b> {weight}');
			$table->col('Pricing')->value('<b>Vendor Average:</b> ${average}<br><b>Dash Base: </b> ${dash_base}<br><b>Percent Upcharge:</b> {percent_upcharge}%');
			
        })->search('product');  
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

            $form->input('product')->width(12);
			$form->input('size')->width(4);
			$form->input('weight')->title('Stock')->width(4);
			$form->input('qty')->width(4);
			$form->input('average')->title('Average Vendor Cost')->placeholder('')->width(4)->prepend('$')->hint('Auto calculated');
			$form->input('dash_base')->title('Dash Base Cost')->placeholder('')->width(4)->prepend('$');
			$form->input('percent_upcharge')->title('Percent Upcharge')->placeholder('')->width(4)->hint('Auto calculated')->append('%');
			
			$form->textarea('note')->width(12);
			
            $form->block('vendors')
			    ->title('Vendors')
			    ->repeatables(function($repeatables) {
			
			        // Add as many repeatables as you want.
			        $repeatables->add('vendor', function($form, $preview) {
			            // The block preview.
			            $preview->col('{vendor}');
						$preview->col('{price}');
			
			            // Containing as many form fields as you want.
			            $form->input('vendor')
			                ->title('Vender');
						$form->input('url')->title('URL');
						$form->input('price')->title('Price');
						$form->input('date')->type('date')->title('Date of Quote');
			        });
					
			
					
			    });
			
        });
    }
}
