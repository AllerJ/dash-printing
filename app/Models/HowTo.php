<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Ignite\Crud\Models\Traits\HasMedia;
use Spatie\MediaLibrary\HasMedia as HasMediaContract;


class HowTo extends Model implements HasMediaContract
{
    use HasMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'objective', 'author', 'category', 'closed'];
 
 
     protected $casts = [
         'closed' => 'boolean'
     ];
     
     
    public function content()
    {
        return $this->repeatables('content');
    }
    
    public function howtosteps()
    {
        return $this->repeatables('howtosteps');
    }
    
    public function getImagesAttribute()
    {
        return $this->getMedia('images');
    }

    
}
