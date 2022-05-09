<?php

namespace Lit\Config\Form\Pages;

use Ignite\Crud\Config\FormConfig;
use Ignite\Crud\CrudShow;
use Lit\Config\Charts\ClickLogConfig;
use Lit\Config\Charts\TonerCyanChartConfig;
use Lit\Config\Charts\TonerMagentaChartConfig;
use Lit\Config\Charts\TonerYellowChartConfig;
use Lit\Config\Charts\TonerBlackChartConfig;
use Lit\Config\Charts\UpchargeChartConfig;
use Lit\Config\Charts\ProjectChartConfig;

use Lit\Http\Controllers\Form\Pages\ReportsController;

class ReportsConfig extends FormConfig
{
	/**
	 * Controller class.
	 *
	 * @var string
	 */
	public $controller = ReportsController::class;

	/**
	 * Form route prefix.
	 *
	 * @return string
	 */
	public function routePrefix()
	{
		return '/reports';
	}

	/**
	 * Form singular name. This name will be displayed in the navigation.
	 *
	 * @return array
	 */
	public function names()
	{
		return [
			'singular' => 'Report',
		];
	}

	/**
	 * Setup form page.
	 *
	 * @param  \Lit\Crud\CrudShow $page
	 * @return void
	 */
	public function show(CrudShow $page)
	{
		$page->info('Linda from Legal');
		$page->chart(ClickLogConfig::class)->height('130px')->variant('white');
		$page->chart(TonerCyanChartConfig::class)->height('130px')->variant('white')->width(3);
		$page->chart(TonerMagentaChartConfig::class)->height('130px')->variant('white')->width(3);
		$page->chart(TonerYellowChartConfig::class)->height('130px')->variant('white')->width(3);
		$page->chart(TonerBlackChartConfig::class)->height('130px')->variant('white')->width(3);
		$page->info('Accounting');
		$page->chart(ProjectChartConfig::class)->height('130px')->variant('secondary');
		$page->chart(UpchargeChartConfig::class)->height('130px')->variant('secondary');
	}
}