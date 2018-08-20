<?php 
namespace App;

use Cartalyst\Sentinel\Users\EloquentUser as SentinelUser;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;
use Carbon\Carbon;
use DB;
use DateTime;

use Sentinel;
use Activation;

use App\Claim;
use App\Helpers\Template;

class User extends SentinelUser
{
    private $super_user_group = 'super-admin';
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes to be fillable from the model.
	 *
	 * A dirty hack to allow fields to be fillable by calling empty fillable array
	 *
	 * @var array
	 */
	protected $fillable = [];
	protected $guarded = ['id'];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['password', 'remember_token'];

	/**
	* To allow soft deletes
	*/
	use SoftDeletes;

    protected $dates = ['deleted_at'];
    
    public function save(array $options = [])
    {
        if(!$this->spammer) $this->spammer = 0;
        
        parent::save();
        
        return $this;
    }
        
    public static function login(UserInterface $user, $remember = false)
    {
        return Sentinel::login($user, $remember);
        /*
        $user = Sentinel::login($user, false);
        if($user) {
            if(Template::isAdminRoute()) {
                Session::set('admin_session', 1);
            } else {
                Session::set('user_session', 1);
            }
            return $user;
        }
        
        return false;
        */
    }
    
    public static function authenticate($credentials, $remember = false, $login = true)
    {
        return Sentinel::authenticate($credentials, $remember, $login);
        
        /*
        $user = Sentinel::authenticate($credentials, $remember, $login);
        
        if($user) {
            if(Template::isAdminRoute()) {
                Session::set('admin_session', 1);
            } else {
                Session::set('user_session', 1);
            }
            return $user;
        }
        
        return false;
        */
    }
    
    public static function check()
    {        
       
        return Sentinel::check();
        
        /*if(Template::isAdminRoute()) {
            $admin_session = (bool)Session::get('admin_session');
            if(!$admin_session) return false;            
        } else {
            $user_session = (bool)Session::get('user_session');
            if(!$user_session) return false;
        }*/

    }
    
    public static function logout()
    {
        
        Sentinel::logout();
        Session::flush();
        /* 
        Session::forget('admin_session');
        Session::forget('user_session');
        */
        
    }
    
    public static function guest()
    {
        return !User::check();
    }
    
    public function ads()
    {
        return $this->hasMany('App\Ads', 'user_id');
    }
    
    public function classifieds()
    {
        return $this->hasMany('App\Classified', 'user_id');
    }
    
    public function nearme()
    {
        return $this->hasMany('App\Nearme', 'user_id');
    }
    
    public function claims()
    {
        return $this->hasMany('App\Claim', 'user_id');
    }
    
    public function payments()
    {
        return $this->hasMany('App\Payments', 'user_id');
    }
    
    public function canBeClaimed()
    {
        $roles = $this->getRoles()->lists('id')->all();
        $current_user = Sentinel::getUser();
        $current_user_roles = $current_user->getRoles()->lists('id')->all();
        
        if(!$this->isSuperAdmin()) {
            $claim = Claim::where('user_id', '=', $this->id)->where('approved', '=', '1')->orWhere('admin_id', '=', $current_user->id);
            $claim = Claim::where(function($query)
            {
                $query->where('user_id', '=', $this->id);
                $query->where('approved', '=', 1);
            })->orWhere(function($query) use ($current_user)
            {
                $query->where('user_id', '=', $this->id);
                $query->where('admin_id', '=', $current_user->id);
            })->count();
            
            if($claim == 0) return true;
        }
        
        return false;
    }
    
    public function alreadyClaimed()
    {
        $current_user = Sentinel::getUser();
        $claim = Claim::where('user_id', '=', $this->id)->where('approved', '=', 1)->count();

        if($claim) return true;
        else return false;
    }
    
    public function claimedBy()
    {
        try {
            $claim = Claim::where('user_id', '=', $this->id)->where('approved', '=', 1)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return false;
        }
        
        return Sentinel::findById($claim->admin_id);
    }
    
    public function claimedByCurrentUser()
    {
        $current_user = Sentinel::getUser();
        $claim = Claim::where('admin_id', '=', $current_user->id)->where('user_id', '=', $this->id)->count();
        
        if($claim > 0) return true;
        else return false;
    }
    
    public function cantClaim()
    {
        $current_user = Sentinel::getUser();
        $claim = Claim::where('admin_id', '=', $current_user->id)->where('user_id', '=', $this->id)->where('reviewed', '=', 1)->where('approved', '=', 0)->count();
        
        if($claim > 0) return true;
        else return false;
    }
    
    public function getFullName()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
    
    public function getAmountSpent()
    {
        //return Payments::where('user_id', '=', $this->id)->where('paid', '=', '1')->sum('amount');
        return Payments::where('user_id', '=', $this->id)->where('payments.paid', '=', '1')->leftJoin('payment_items', function($join) {
            $join->on('payment_items.payment_id', '=', 'payments.id');
        })->select(DB::raw("SUM(payment_items.paid) as total"))->get()->sum('total');
    }
    
    public function getSalesAmount()
    {
        $total = 0;
        $users = Claim::where('admin_id', '=', $this->id)->where('approved', '=', '1')->lists('user_id')->toArray();
        if($users) {
            //return Payments::whereIn('user_id', $users)->where('paid', '=', '1')->sum('amount');
            foreach($users as $user) {
                try {
                    $user = User::where('id', '=', $user)->firstOrFail();
                    $total += $user->getAmountSpent();
                } catch (ModelNotFoundException $e) {
                    
                }
            }
        }
        
        return $total;
    }
    
    public function isSuperAdmin()
    {
        return (bool)$this->inRole($this->super_user_group);
    }
    
    public function getFormatedAddress()
    {
        $loc = array();
        if($this->country == 'US') {
            if($this->city) $loc[] = $this->city.',';
            if($this->state) $loc[] = $this->state.'.';
            $loc[] = $this->country;
        } else {
            if($this->country) {
                $countries = Template::getCountriesList(false);
                if(isset($countries[$this->country])) {
                    $loc[] = $countries[$this->country].',';
                }
            }
            if($this->city) $loc[] = $this->city;
        }
        
        if($loc) return implode(' ', $loc);
        else return '';
        
    }

    public function hasAddress()
    {
        return !empty($this->country) && !empty($this->city);
    }

    public function setAddressFromLocation()
    {
        $location = Template::getCurrentLocation();

        $this->country = array_get($location, 'iso_code');
        $this->state   = array_get($location, 'state');
        $this->city    = array_get($location, 'city');

        return $this;
    }

    public function isActive()
    {
        if($this->published) {
            $activation = Activation::completed($this);
            if($activation) return true;
        }
        
        return false;
    }
    
    public static function getReportGrid($type = false, $range = false, $filter_sdate = false, $filter_edate = false)
    {
        $now = Carbon::now();
        $users = User::where(function ($user) {
            $user->whereHas('ads', function($query) use ($user) {
                 $query->withTrashed();
            })->orWhereHas('nearme', function($query) use ($user) {
                 $query->withTrashed();
            })->orWhereHas('classifieds', function($query) use ($user) {
                 $query->withTrashed();
            });
        })->where('published', '=', '1')
        ->leftJoin('payments', function($join) {
            $join->on('payments.user_id', '=', 'users.id');
            $join->where('payments.paid', '=', 1);
        })
        ->leftJoin('payment_items', function($join) {
            $join->on('payment_items.payment_id', '=', 'payments.id');
        })->select(
            'users.*',
            'payment_items.type',
            DB::raw("(SELECT COUNT(id) FROM ads WHERE user_id = users.id AND deleted_at IS NULL) as ads_count"),
            DB::raw("(SELECT COUNT(id) FROM nearme WHERE user_id = users.id AND deleted_at IS NULL) as nearme_count"),
            DB::raw("(SELECT COUNT(id) FROM classifieds WHERE user_id = users.id AND deleted_at IS NULL) as classified_count"),
            'payments.created_at as payment_created_at',
            DB::raw("CONCAT(users.last_name,' ',users.first_name) as fullname"),
            DB::raw("CASE WHEN payments.amount THEN SUM(payments.amount) ELSE 0 END as amount")
        )->having('amount', '>', 0)->groupBy('users.email');
        
        switch($type) {
            case('ads'): $users->having('ads_count', '>=', 1); break;
            case('nearme'): $users->having('nearme_count', '>=', 1); break;
            case('classifieds'): $users->having('classified_count', '>=', 1); break;
        }
        
        switch($range) {
            case('current_month'):
                $users->where(DB::raw('MONTH(payments.created_at)'), '=', $now->month)->where(DB::raw('YEAR(payments.created_at)'), '=', $now->year); break;
            case('last_month'):
                $users->where(DB::raw('MONTH(payments.created_at)'), '=', $now->subMonth()->month)->where(DB::raw('YEAR(payments.created_at)'), '=', $now->subMonth()->year); break;
            case('current_year'):
                $users->where(DB::raw('YEAR(payments.created_at)'), '=', $now->year); break;
            case('last_year'):
                $users->where(DB::raw('YEAR(payments.created_at)'), '=', $now->subYear()->year); break;
            case('custom'):
                if($filter_sdate) {
                    $filter_date = DateTime::createFromFormat(Template::getDisplayedDateTimeFormat(), $filter_sdate);
                    $users->where('payments.created_at', '>', $filter_date);
                }
                if($filter_edate) {
                    $filter_date = DateTime::createFromFormat(Template::getDisplayedDateTimeFormat(), $filter_edate);
                    $users->where('payments.created_at', '<', $filter_date);
                }
                break;
        }

        return $users;
    }
}
