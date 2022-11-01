<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TbSrcStore extends Model
{
    use HasFactory;

    protected $table = 'tb_src_store';

    public function city()
    {
        return $this->hasOne('App\Models\TbSrcRegion', 'id', 'src_city_id');
    }
}
