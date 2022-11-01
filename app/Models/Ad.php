<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{
    use HasFactory;

    protected $table = 'ads';


    protected $fillable = [
        'advertiser_id' ,
        'category_id',
        'type',
        'title',
        'description',
        'start_date'
    ];

    public function advertiser()
    {
        return $this->belongsTo(Advertiser::class , 'advertiser_id');
    }

    public function adCategory()
    {
        return $this->belongsTo(Advertiser::class , 'category_id');
    }

    public function adTags()
    {
        return $this->hasMany(TagList::class);
    }



}
