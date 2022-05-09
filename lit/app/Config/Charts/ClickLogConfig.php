<?php

namespace Lit\Config\Charts;

use Ignite\Chart\Chart;
use Illuminate\Support\Collection; 
use Ignite\Chart\Config\AreaChartConfig;

class ClickLogConfig extends AreaChartConfig
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
        return 'Click Count';
    }

    /**
     * Mount.
     *
     * @param Chart $chart
     * @return void
     */
    public function mount(Chart $chart)
    {
        // $chart->format('0,0');
    }

    /**
     * Calculate value.
     *
     * @param Builder $query
     * @return integer
     */
    public function value($query)
    {
        return $this->max($query, 'click_count');
    }
}
