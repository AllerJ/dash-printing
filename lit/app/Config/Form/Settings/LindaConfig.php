<?php

namespace Lit\Config\Form\Settings;

use Ignite\Crud\Config\FormConfig;
use Ignite\Crud\CrudShow;
use Lit\Http\Controllers\Form\Settings\LindaController;

class LindaConfig extends FormConfig
{
    /**
     * Controller class.
     *
     * @var string
     */
    public $controller = LindaController::class;

    /**
     * Form route prefix.
     *
     * @return string
     */
    public function routePrefix()
    {
        return "settings/linda";
    }

    /**
     * Form singular name. This name will be displayed in the navigation.
     *
     * @return array
     */
    public function names()
    {
        return [
            'singular' => 'Linda',
        ];
    }

    /**
     * Setup form page.
     *
     * @param \Lit\Crud\CrudShow $page
     * @return void
     */
    public function show(CrudShow $page)
    {
        $page->expand();
        $page->view('linda');
        $page->card(function($form) {

            $form->block('settings')
                ->title('Print Dialogue Settings')
                ->repeatables(function($repeatables) {
            
                    // Add as many repeatables as you want.
                    $repeatables->add('Document Presets', function($form, $preview) {
                        // The block preview.
                        $preview->col('{document_name}');
            
                        // Containing as many form fields as you want.
                        $form->input('document_name')
                            ->title('Document Name or Client Name')->placeholder('');
                        $form->input('form_header_large')->title('Adobe Print Dialogue'); 
                        $form->input('form_header')->title('Output');
                        $form->select('printer_resolution')
                            ->title('Printer Resolution')
                            ->options([
                                1 => '212 lpi / 1200dpi',
                                2 => '141 lpi / 600dpi',
                            ]);
                        $form->input('form_header')->title('Color Management');
                        $form->select('color_handling')
                            ->title('Color Handling')
                            ->options([
                                1 => 'Let Illustrator Determine Colors',
                                2 => 'Let Postscript Printer Determine Colors',
                            ])->width(3);
                        
                        
                        
                        $form->select('printer_profile')
                            ->title('Printer Profile')
                            ->options([
                                1 => 'sRGB IEC61966-2.1',
                                2 => 'U.S. Web Coated (SWOP) v2',
                                3 => 'RICOH Aficio MP C3500/C4500 (CMYK)'
                            ])->width(3);
                        
                        $form->select('render_intent')
                            ->title('Render Intent')
                            ->options([
                                1 => 'Perceptual',
                                2 => 'Saturation',
                                3 => 'Relative',
                                4 => 'Absolute'
                            ])->width(3);
                        
                        $form->checkboxes('preserve')
                            ->title('')
                            ->options([
                                'preserve' => 'Preserve RGB/CMYK Numbers',
                            ])->width(3);    
                        
                        $form->input('form_header')->title('Advanced');     


                        $form->select('overprint_preset')
                            ->title('Overprint -> Preset')
                            ->options([
                                1 => '[High Resolution]',
                                2 => '[Medium Reolution]',
                            ]);
                        
                        $form->input('form_header_large')->title('Printer Setup');   
                        $form->checkboxes('duplex')
                        ->title('')
                        ->options([
                            'duplex' => 'Two-Sided',
                        ])->width(3);      
                        


                        $form->select('two_sided')
                            ->title('Two Sided')
                            ->options([
                                1 => 'Long Edge Binding',
                                2 => 'Short Edge Binding',
                                3 => 'Off'
                            ])->width(9);


                            $form->input('form_header')->title('Color Matching');
                            $form->radio('color_matching')
                                ->title('')
                                ->options([
                                    'color_sync' => 'Color Sync',
                                    'in-printer' => 'In Printer',
                            ]);
                            $form->select('profile')
                                ->title('Profile')
                                ->options([
                                    1 => 'Auto'
                                ])->when('color_matching', 'color_sync');                   
                                                                              
                        
                       
                            $form->input('form_header')->title('Paper Feed');
    
                            $form->select('all_pages')
                                ->title('All Pages')
                                ->options([
                                    1 => 'Auto Select',
                                    2 => 'Tray 1',
                                    3 => 'Tray 2',
                                    4 => 'Bypass Tray'
                                ]);
                        
                       
    
                        $form->input('form_header')->title('Printer Features');
                        
                        $form->select('resolution')
                            ->title('Resolution')
                            ->options([
                                1 => '600 dpi',
                                2 => '1200 dpi'
                            ])->width(6);
                            
                        $form->select('gradation')
                            ->title('Gradation')
                            ->options([
                                1 => 'Fast',
                                2 => 'Fine',
                                3 => 'Standard'
                            ])->width(6);
                        
                        $form->select('smoothing')
                            ->title('Image Smoothing')
                            ->options([
                                1 => 'Auto',
                                2 => 'Off',
                                3 => 'On',
                                
                            ])->width(6)->hint('
                            <b>Off</b>
                            Disables image smoothing.<br><br>
                            <b>On</b>
                            Performs image smoothing unconditionally.<br><br>
                            <b>Auto</b>
                            Performs image smoothing automatically for images that have a resolution less than 25% of supported
                            printer resolution.
                            ');

                        
                            
                        $form->select('color_setting')
                            ->title('Color Setting')
                            ->options([
                                1 => 'Off',
                                2 => 'Fine',
                                3 => 'Super Fine'
                            ])->width(6)->hint("
                            
                            <b>Off</b>
                            No modification to the color setting.<br><br>
                            <b>Fine</b>
                            Select this setting to perform color matching based on one of the printer's built- in color rendering
                            dictionaries and perform CMYK conversion. This setting performs the printing which output target is
                            Monitor Gamma= 1.8.<br><br>
                            <b>Super Fine</b>
                            Select this setting to use a color rendering dictionary as in the [Fine] setting but produce output that is
                            more vivid. Use this setting to emphasize light colors. This setting performs the printing which output
                            target is Monitor Gamma= 2.2.
                            
                            ");
                        
                        $form->select('color_profile')
                            ->title('Color Profile')
                            ->options([
                                1 => 'Auto',
                                2 => 'Photographic',
                                3 => 'Presentation',
                                4 => 'Solid Color',
                                5 => 'User Setting',
                                6 => 'CLP Simulation 1',
                                7 => 'CLP Simulation 2'
                            ])->width(6)->hint("
                            
                            <b>Auto</b>
                            Use this setting to configure the best color profile pattern automatically depending on the appearance
                            of the document to be printed.<br><br>
                            <b>Photographic</b>
                            Use this setting to enhance the reproduction of photos and graphics that include midtones.<br><br>
                            <b>Presentation</b>
                            Use this setting to enhance the reproduction of documents that contain text and graphics. This CRD is
                            best for printing colored charts, graphs, presentation materials and so on. If you use this CRD for
                            printing photographs, the color or gradations might not be reproduced well.<br><br>
                            <b>Solid Color</b>
                            Use this setting to print specific colors, logos and so on.<br><br>
                            <b>User Setting</b>
                            Use this setting to print images with downloaded CRD from your application.<br><br>
                            <b>CLP Simulation1</b>
                            Use this setting to print blue color more brightly and vividly.<br><br>
                            <b>CLP Simulation2</b>
                            Use this setting to print blue color more brightly and vividly. Print results are lighter than those of CLP
                            Simulation1.
                            
                            ");
                        
                        
                        $form->select('dithering')
                            ->title('Dithering')
                            ->options([
                                1 => 'Auto',
                                2 => 'Photographic',
                                3 => 'Text',
                                4 => 'User Setting'
                            ])->width(6)->hint("
                            <b>Auto</b>
                            Use this setting to configure the best dithering method automatically depending on the appearance
                            of the document to be printed.<br><br>
                            <b>Photographic</b>
                            Performs dithering using an appropriate pattern for photographs.<br><br>
                            <b>Text</b>
                            Performs dithering using an appropriate pattern for text.<br><br>
                            <b>User Setting</b>
                            Use this setting to print images set in half tone in your application.
                            ");
                            
                        $form->select('paper_type')
                            ->title('Paper Type')
                            ->options([
                                1 => 'Plain',
                                2 => 'Middle Thick',
                                3 => 'Thick 1',
                                4 => 'Thick 2',
                                5 => 'Thick 3',
                                6 => 'Thick 4'
                            ])->width(4);
                        
                        $form->select('destination')
                        ->title('Destination')
                        ->options([
                            1 => 'Printer Default',
                            2 => 'Finisher Booklet Tray',
                        ])->width(4);
                        
                        $form->select('staple')
                        ->title('Staple')
                        ->options([
                            1 => 'Off',
                            2 => '2 at Center',
                        ])->width(4);
                    
                                      

                    });
            
                });

        })->class('linda');
    }
}
