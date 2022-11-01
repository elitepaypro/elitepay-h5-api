<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TbRegion extends Model
{
    use HasFactory;

    protected $table = 'tb_region';

    public function regionMapping()
    {
        return $this->hasOne('App\Models\TbRegionMapping', 'region_id');
    }

    public function province()
    {
        return $this->hasMany('App\Models\TbRegion', 'parent_id', 'id')->where('level', 2);
    }

    public function city()
    {
        return $this->hasMany('App\Models\TbRegion', 'parent_id', 'id')->where('level', 3);
    }
}
