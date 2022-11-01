<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TagList extends Model
{
    use HasFactory;

    protected $table = 'tag_list';
    public $timestamps = false;


    protected $fillable = [
        'ad_id' ,
        'tag_id',
    ];

    public function getAdId()
    {
        return $this->belongsTo(Ad::class , 'ad_id');
    }

    public function getTagId()
    {
        return $this->belongsTo(RelatedTag::class , 'tag_id');
    }

}
