<?php

namespace App\Models;

use Encore\Admin\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SearchWord extends Model
{
    use HasFactory;
    use DefaultDatetimeFormat;

    protected $table = 'tb_search_words';
}
