<?php

namespace Lit\Config\Charts;

use Ignite\Chart\Chart;
use Illuminate\Support\Collection; 
use Ignite\Chart\Config\AreaChartConfig;

class ProjectChartConfig extends AreaChartConfig
{
    /**
     * The model class of the chart.
     *
     * @var string
     */
    public $model = \App\Models\Project::class;

    

    /**
     * Chart title.
     *
     * @return string
     */
    public function title(): string
    {
        return 'Total';
    }

    /**
     * Mount.
     *
     * @param Chart $chart
     * @return void
     */
    public function mount(Chart $chart)
    {
        $chart->format('0')->prefix('$ ');
    }

    /**
     * Calculate value.
     *
     * @param Builder $query
     * @return integer
     */
    public function value($query)
    {
        return $this->sum($query, 'total');
    }
}
