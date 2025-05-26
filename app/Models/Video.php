<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    //
     protected $fillable = [
        'title',
        'link',
    ];

     public function morphTags(){
        return $this->morphToMany(Tag::class,'taggable')->withTimestamps();
    }
}
