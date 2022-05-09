<?php

namespace Lit\Config;

use Ignite\Application\Navigation\Config;
use Ignite\Application\Navigation\Navigation;
use Lit\Config\Crud\Project;
use Lit\Config\Crud\Inventory;
use Lit\Config\Crud\Matrix;
use Lit\Config\Crud\HowTo;
use Lit\Config\Crud\TonerLog;
use Lit\Config\Crud\RestockLog;
use Lit\Config\Crud\Finishing;
use Lit\Config\Form\Settings\PrintingSettingsConfig;
use Lit\Config\Form\Settings\LindaConfig;
use Lit\Config\Form\Pages\ReportsConfig;


class NavigationConfig extends Config
{
    /**
     * Topbar navigation entries.
     *
     * @param  \Ignite\Application\Navigation\Navigation $nav
     * @return void
     */
    public function topbar(Navigation $nav)
    {
        $nav->section([
            $nav->preset('profile'),
        ]);

        $nav->section([
            $nav->title(__lit('navigation.user_administration')),

            $nav->preset('user.user', ['icon' => fa('users')]),
            $nav->preset('permissions'),
        ]);
    }

    /**
     * Main navigation entries.
     *
     * @param  \Ignite\Application\Navigation\Navigation $nav
     * @return void
     */
    public function main(Navigation $nav)
    {
        $nav->section([
            $nav->title('Config'),

            $nav->preset(ReportsConfig::class, [
                'icon' => fa('fas', 'print')
            ]),
            
            $nav->preset(PrintingSettingsConfig::class, [
                'icon' => fa('fas', 'print')
            ]),
            
            $nav->preset(LindaConfig::class, [
                'icon' => fa('fas', 'print')
            ]),
           
            $nav->preset('crud.how_to', [
               'icon' => fa('balance-scale-left')
            ])
           

            //
        ]);
        $nav->section([
            $nav->title('Printing'),


            $nav->preset('crud.project', [
                'icon' => fa('fab', 'stack-overflow')
            ]),
            $nav->preset('crud.inventory', [
                'icon' => fa('barcode')
            ]),
			$nav->preset('crud.matrix', [
                'icon' => fa('balance-scale-left')
            ]),
            // $nav->preset('crud.finishing', [
            //     'icon' => fa('map')
            // ]),

            //
        ]);
        $nav->section([
            $nav->title('Logging'),
            $nav->preset('crud.toner_log', [
                'icon' => fa('sort-numeric-down-alt')
            ]),
            $nav->preset('crud.restock_log', [
                'icon' => fa('sort-numeric-down-alt')
            ]),
			$nav->preset('crud.click_count', [
                'icon' => fa('fab', 'deezer')
            ]),
        ]);
    }
}
