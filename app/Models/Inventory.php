<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Ignite\Crud\Models\Repeatable;
use App\Models\TonerLog;
use App\Models\RestockLog;

class Inventory extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title','current_qty','current_per_sheet','multiplier','type', 'low_warning'];


    public function stock()
    {
        return $this->repeatables('stock');
    }

    public function tonerlog()
    {
        return $this->belongsTo('App/Models/TonerLog');
    }

    public function newStock()
    {
        $relation = $this->morphMany(Repeatable::class, 'model')
            ->with('translations')
            ->orderBy('lit_repeatables.created_at', 'DESC');

        $relation->where('field_id', 'stock')->first();

        return $relation;
    }
}
