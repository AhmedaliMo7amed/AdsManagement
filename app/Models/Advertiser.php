<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Advertiser extends Model
{
    use HasFactory;

    protected $table = 'advertisers';

    protected $fillable = [
        'firstName',
        'lastName',
        'email',
    ];

    public function userAds()
    {
        return $this->hasMany(Ad::class);
    }
}
