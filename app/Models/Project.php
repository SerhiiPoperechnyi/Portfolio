<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
    'user_id',
    'title',
    'slug',
    'description',
    'main_video',
    'thumbnail',
    'is_featured',
    'is_published'
    ];

    public function user(){
      return $this->belongsTo(User::class);
    }

    public function images(){
      return $this->hasMany(ProjectImage::class);
    }

    public function files(){
      return $this->hasMany(ProjectFile::class);
    }
}
