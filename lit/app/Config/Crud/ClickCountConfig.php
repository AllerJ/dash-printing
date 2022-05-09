<?php

namespace Lit\Config\Crud;

use Ignite\Crud\CrudShow;
use Ignite\Crud\CrudIndex;
use Ignite\Crud\Config\CrudConfig;
use Illuminate\Support\Str;
use App\Models\ClickCount;
use Lit\Http\Controllers\Crud\ClickCountController;

use Lit\Config\Charts\ClickLogConfig;
use Lit\Config\Charts\TonerCyanChartConfig;
use Lit\Config\Charts\TonerMagentaChartConfig;
use Lit\Config\Charts\TonerYellowChartConfig;
use Lit\Config\Charts\TonerBlackChartConfig;


class ClickCountConfig extends CrudConfig
{
    /**
     * Model class.
     *
     * @var string
     */
    public $model = ClickCount::class;

    /**
     * Controller class.
     *
     * @var string
     */
    public $controller = ClickCountController::class;

    /**
     * Model singular and plural name.
     *
     * @param ClickCount|null clickCount
     * @return array
     */
    public function names(ClickCount $clickCount = null)
    {
        return [
            'singular' => 'ClickCount',
            'plural'   => 'ClickCounts',
        ];
    }

    /**
     * Get crud route prefix.
     *
     * @return string
     */
    public function routePrefix()
    {
        return 'clickcounts';
    }

    /**
     * Build index page.
     *
     * @param \Ignite\Crud\CrudIndex $page
     * @return void
     */
    public function index(CrudIndex $page)
    {
            
        $page->chart(ClickLogConfig::class)->height('130px')->variant('white');
        $page->chart(TonerCyanChartConfig::class)->height('130px')->variant('white')->width(3);
        $page->chart(TonerMagentaChartConfig::class)->height('130px')->variant('white')->width(3);
        $page->chart(TonerYellowChartConfig::class)->height('130px')->variant('white')->width(3);
        $page->chart(TonerBlackChartConfig::class)->height('130px')->variant('white')->width(3);
        
        $page->info('Raw Stats Polled Every 1/2 Hour');
        $page->table(function ($table) {
            $table->col('Clicks')->value('{click_count}');
			$table->col('Toner Levels')->value('C {cyan}% | M {magenta}% | Y {yellow}% | K {black}%');
			$table->date('created_at', 'm-d-Y')->label('Date');
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
        $page->card(function($form) {

            $form->input('title');
            
        });
    }
}
