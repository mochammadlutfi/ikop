<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    protected $table = 'slider';

    protected $fillable = [
        'img', 'type', 'url', 'is_active'
    ];

    protected $appends = [
        'img_url', 'status_badge'
    ];

    public function getStatusBadgeAttribute()
    {
        if ($this->attributes['is_active'] === 1) {
            return '<span class="badge badge-success">Publikasi</span>';
        } else {
            return '<span class="badge badge-danger">Draft</span>';
        }
    }


    public function getImgUrlAttribute()
    {
        if (file_exists( public_path() . '/' . $this->attributes['img']) && $this->attributes['img'] !== null) {
            return asset($this->attributes['img']);
        } else {
            return asset('public/img/poster.png');
        }
    }
}
