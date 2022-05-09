<?php

namespace Lit\Config\Charts;

use Ignite\Chart\Chart;
use Illuminate\Support\Collection; 
use Ignite\Chart\Config\AreaChartConfig;

class TonerChartConfig extends AreaChartConfig
{
    /**
     * The model class of the chart.
     *
     * @var string
     */
    public $model = \App\Models\ClickCount::class;

    

    /**
     * Chart title.
     *
     * @return string
     */
    public function title(): string
    {
        return 'Cyan Use Over Time';
    }

    /**
     * Mount.
     *
     * @param Chart $chart
     * @return void
     */
    public function mount(Chart $chart)
    {
//        $chart->format('0');
    }

    /**
     * Calculate value.
     *
     * @param Builder $query
     * @return integer
     */
    public function value($query)
    {
        return $this->min($query, 'cyan');
    }
}
