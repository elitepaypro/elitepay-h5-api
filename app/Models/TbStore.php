<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TbStore extends Model
{
    use HasFactory;

    protected $table = 'tb_store';

    public function tbBrand()
    {
        return $this->belongsTo('App\Models\TbBrand', 'brand_id', 'id');
    }

    public function tbCategory()
    {
        return $this->belongsTo('App\Models\TbCategory', 'category_id', 'id');
    }

    public function tbCategory2()
    {
        return $this->belongsTo('App\Models\TbCategory', 'category_id_2', 'id');
    }

    public function tbCategory3()
    {
        return $this->belongsTo('App\Models\TbCategory', 'category_id_3', 'id');
    }

    public function city()
    {
        return $this->belongsTo('App\Models\TbRegion', 'city_id', 'id');
    }

    public function district()
    {
        return $this->belongsTo('App\Models\TbRegion', 'district_id', 'id');
    }

    public function tbCommericalLocation()
    {
        return $this->belongsTo('App\Models\TbCommericalLocation', 'commerical_location_id', 'id');
    }

    public function tbSrcStoreMapping()
    {
        return $this->hasMany('App\Models\TbStoreMapping', 'store_id');
    }
}
