<?php

namespace App\Models;

use Encore\Admin\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    use DefaultDatetimeFormat;

    public function tenant()
    {
        return $this->belongsTo('App\Models\Tenant');
    }

    public function internetChannel()
    {
        return $this->belongsTo('App\Models\InternetChannel');
    }

    public function adminUser()
    {
        return $this->belongsTo('App\Models\AdminUser', 'creator_user_id');
    }
}
