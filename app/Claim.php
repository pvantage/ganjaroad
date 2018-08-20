<?php 

namespace App;

use Illuminate\Database\Eloquent\Model;

class Claim extends Model
{
	protected $table = 'claim_user';
	protected $fillable = [];
	protected $guarded = ['id'];
	
    public function admin()
    {
		return $this->belongsTo('App\User', 'admin_id');
    }
	
	public function user()
    {
		return $this->belongsTo('App\User', 'user_id');
    }
    
    public static function getReportGrid()
    {
        return Claim::where('approved', '=', '1')->groupBy('admin_id');
    }
}
