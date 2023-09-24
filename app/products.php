<?php

namespace App;
use App\Sections;
use Illuminate\Database\Eloquent\Model;

class products extends Model
{
    protected $fillable = [
        'product_name',
        'description',
        'section_id',
    ];

    public function section(){
        return $this->belongsTo(Sections::class, 'section_id', 'id');
    }

}
