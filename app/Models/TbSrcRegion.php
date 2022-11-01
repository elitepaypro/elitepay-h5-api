<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TbSrcRegion extends Model
{
    use HasFactory;

    protected $table = 'tb_src_region';

    public function city()
    {
        return $this->hasMany('App\Models\TbSrcRegion', 'parent_id', 'id')->where('level', 3);
    }

    public function store()
    {
        return $this->hasMany('App\Models\TbSrcStore', 'src_city_id', 'id'); // ->where('status', 1)
    }
}
