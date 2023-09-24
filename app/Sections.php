<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sections extends Model
{
    protected $fillable = [
        'section_name',
        'description',
        'created_by',
    ];

    public function product()
    {
        return $this->hasOne(products::class, 'id', 'section_id');
    }
    public function invoices(){
        return $this->belongsTo(Invoices::class);
    }
}
