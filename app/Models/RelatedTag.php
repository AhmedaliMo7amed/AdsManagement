<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelatedTag extends Model
{
    use HasFactory;

    protected $table = 'tags';

    protected $fillable = [
        'name' ,
        'slug',
    ];

}