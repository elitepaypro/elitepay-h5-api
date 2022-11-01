<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TbBank extends Model
{
    use HasFactory;

    protected $table = 'tb_bank';

    protected $connection = 'elite_v2_offline';
}
