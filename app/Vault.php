<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vault extends Model
{
	use SoftDeletes;
	
    protected $table = 'vault';
    protected $guarded = ['id'];
    protected $fillable = ['user_id', 'vault_id'];
    
}
