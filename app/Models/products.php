<?php

namespace App\Models;

use App\Models\section;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class products extends Model
{
    protected $fillable = [
        'Product_name',
        'description',
        'section_id',
     ];

     public function section()
     {
         return $this->belongsTo(section::class);
     }

}
