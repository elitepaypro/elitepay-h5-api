<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TbCategory extends Model
{
    use HasFactory;

    protected $table = 'tb_category';

    public function categories()
    {
        return $this->hasMany('App\Models\TbCategory', 'parent_id', 'id')->select(['id', 'parent_id', 'name']);
    }
}
