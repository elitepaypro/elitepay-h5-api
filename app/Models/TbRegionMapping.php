<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TbRegionMapping extends Model
{
    use HasFactory;

    protected $table = 'tb_region_mapping';

    public function srcRegion()
    {
        return $this->hasOne('App\Models\TbSrcRegion', 'id', 'src_region_id')
            ->where('level', '3')
            ->where('shopid', '2');
    }
}
