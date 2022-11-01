<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TbStoreMapping extends Model
{
    use HasFactory;

    protected $table = 'tb_store_mapping';

    public function meiTuanSrcStore()
    {
        return $this->belongsTo('App\Models\TbSrcStore', 'src_store_id', 'id')->where('shopid', 2);
    }
}
