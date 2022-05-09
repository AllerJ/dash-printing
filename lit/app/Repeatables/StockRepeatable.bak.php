<?php

namespace Lit\Repeatables;

use Ignite\Crud\Fields\Block\Repeatable;
use Ignite\Crud\Fields\Block\RepeatableForm;
use Ignite\Page\Table\ColumnBuilder;
use App\Models\Inventory;

class StockRepeatable extends Repeatable
{
    /**
     * Repeatable type.
     *
     * @var string
     */
    protected $type = 'stock';

    /**
     * The represantive view of the repeatable.
     *
     * @var string
     */
    protected $view = 'rep.stock';

    /**
     * Build the repeatable preview.
     *
     * @param  ColumnBuilder $preview
     * @return void
     */
    public function preview(ColumnBuilder $preview): void
    {
        $preview->col('Qty: {qty}');
        $preview->col('Per: ${cost}');
        $preview->col('On: {ordered_on}');
    }

    /**
     * Build the repeatable form.
     *
     * @param  RepeatableForm $form
     * @return void
     */
    public function form(RepeatableForm $form): void
    {
        $form->input('qty')
            ->title('Qty Ordered')->width(3);
        $form->input('cost')
            ->prepend('$')
            ->title('Price Each')->width(3);
        $form->input('yield')->type('number')
            ->title('Yield')->width(3);
        $form->input('per_sheet')
            ->title('Price Per Yield')->width(3);
        $form->input('ordered_on')->type('date')
            ->title('Restocked On')->width(6);
        $form->input('supplier')
            ->title('Supplier URL')->type('url')->width(6);
    }

    /**
     * Prepare attributes before updating the content of the repeatable.
     *
     * @param  array $attributes
     * @return array
     */
    public function prepareAttributes($attributes, $repeatableModel)
    {
        if(isset($attributes['en']['cost']) && isset($attributes['en']['yield'])) {
            $parentModel = Inventory::find($repeatableModel->model_id);
            $attributes['en']['per_sheet'] = $attributes['en']['cost'] / $attributes['en']['yield'];
            $parentModel->current_per_sheet = $attributes['en']['per_sheet'];
            $parentModel->save();
        }
        
        if(isset($attributes['en']['qty'])) {
            $parentModel = Inventory::findOrFail($repeatableModel->model_id);
            $parentModel->current_qty = $parentModel->current_qty + $attributes['en']['qty'];
            $parentModel->save();            
        }
        
        
        return $attributes;
    }
}
