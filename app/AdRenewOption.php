<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdRenewOption extends Model
{

    protected $table = 'ad_renew_options';

    protected $guarded = ['id'];

    protected $fillable = ['renewal_period_name', 'renewal_time', 'renewal_time_period', 'renewal_type', 'renewal_discount'];

}
