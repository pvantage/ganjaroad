<?php 
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\MessageBag;
use Securimage;
use Sentinel;
use View;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Helpers\Template;
use App\User;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Input;
use Gregwar\Image\Image;
use Carbon\Carbon;
use DB;
use Mail;

use App\Ads;
use App\Blog;
use App\BlogCategory;
use App\Nearme;
use App\NearmeCategory;
use App\Classified;
use App\ClassifiedCategory;
use App\ReportedItems;
use App\Issues;
use App\Page;
use App\Access;
use App\Claim;

class WeedController extends Controller {

	protected $countries = array();
	protected $messageBag = null;

	
	public function __construct()
	{
		$this->messageBag = new MessageBag;
        $this->countries = Template::getCountriesList();
	}

	
	public function cropImage(Request $request)
	{
        $max_width = (int)Template::getSetting('global_image_width');
        $max_height = (int)Template::getSetting('global_image_height');
        
        if ($file = $request->file('file')) {
            $rules = array(
                'file' => 'image|mimes:jpg,jpeg,bmp,png',
            );

            $validator = Validator::make($request->only('file'), $rules);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'msg' => 'File type not supported']);
            }
            
            $file = $request->file('file');
            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $folderName = Template::getTempImageDir();
            $picture = md5(time()) . '.' . $extension;
            $destinationPath = public_path() . $folderName;
            $full_path = $destinationPath.$picture;

            $request->file('file')->move($destinationPath, $picture);
           
            list($width, $height) = getimagesize($full_path);
            if($width > $max_width || $height > $max_height) {
                Image::open($full_path)
                    ->cropResize($max_width, $max_height)
                    ->save($full_path);
            }
            
            return response()->json(['success' => true, 'msg' => 'Success', 'img_name' => $picture]);
        }
    }

    public function showHome()
    {
    	if(User::check()) {
            $user = Sentinel::getUser();
            
            $counters = array();
            $counters['all_ads'] = Ads::count();
            $ads = Ads::getActive();
            $counters['active_ads'] = $ads->count();
            
            $ads_approval = Template::getSetting('ads_approval');
            if($ads_approval) {
                $ads = Ads::getPendingAds();
                $counters['pending_ads'] = $ads->count();
            } else $counters['pending_ads'] = 0;
            
            //get all not deleted users sql
            $sql = DB::table('users')
                    ->whereNull('deleted_at')
                    ->groupBy('users.id');
            
            $counters['all_users'] = count((clone $sql)->get());
            $counters['active_users'] = count((clone $sql)->join('activations', 'users.id', '=', 'activations.user_id')->where('completed', '=', 1)->get());
            $counters['published_users'] = count((clone $sql)->where('published', '=', 1)->get());
            $counters['users_approval'] = Access::where('granted', '=', 0)->count();
            $counters['reps_approval'] = Claim::where('reviewed', '=', 0)->where('approved', '=', 0)->count();
            
            $counters['all_nearme'] = Nearme::all()->count();
            $counters['active_nearme'] = Nearme::getActive()->count();
            
            $nearme_approval = Template::getSetting('nearme_approval');
            if($nearme_approval) {
                $counters['approval_nearme'] = Nearme::where('paid', '=', 1)->where('approved', '=', 0)->count();
            } else $counters['approval_nearme'] = 0;
            
            $counters['all_classifieds'] = Classified::all()->count();
            $counters['active_classifieds'] = Classified::getActive()->count();
            
            $classified_approval = Template::getSetting('classified_approval');
            if($classified_approval) {
                $counters['approval_classifieds'] = Classified::where('paid', '=', 1)->where('approved', '=', 0)->count();
            } else $counters['approval_classifieds'] = 0;
            
            $counters['reported'] = ReportedItems::getAllReportedItems()->get()->count();
            $issues = Issues::getAllIssuedItems();
            $counter = 0;
            if($issues->count()) {
                foreach($issues->get() as $issue) {
                    if($issue->item()->count()) ++$counter;
                }
            }
            $counters['issued'] = $counter;
            $counters['all_issues'] = (int)$counters['reported'] + (int)$counters['issued'];
            
			return View('admin/index', compact('counters', 'user'));
        } else {
			return Redirect::to('admin/signin')->with('error', 'You must be logged in!');
        }
    }

    public function showView($name=null)
    {
    	if(View::exists('admin/'.$name)) {
			if(User::check())
				return View('admin/'.$name);
			else
				return Redirect::to('admin/signin')->with('error', 'You must be logged in!');
		} else {
			return View('admin/404');
		}
    }

    public function showFrontEndView($name = null)
    {
        if(View::exists($name)) {
            return View($name);
        } else {
            try {
                $page = Page::where('url', 'LIKE', $name)->where('published', '=', 1)->firstOrFail();
                if($page->id) {
                    return View('page', compact('page'));
                }
            } catch (ModelNotFoundException $e) {
                return View('404');
            }
            
        }
    }

	public function secureImage(Request $request)
	{
        session_start();
		include_once public_path()."/assets/vendors/secureimage/securimage.php";
		$securimage = new Securimage();
		if ($securimage->check($request->captcha_code) == false) {
			echo "The security code entered was incorrect.<br /><br />";
			echo "Please go <a href='javascript:history.go(-1)'>back</a> and try again.";
			exit;
		}
		else{
			echo "The security code entered was correct. <a href='javascript:history.go(-1)'>back</a><br /><br />";
			exit;
		}

	}
    
    public function modal()
    {
        return View('admin/layouts/text_modal');
    }
    
    public function getSitemap()
    {
        $path = public_path() . DIRECTORY_SEPARATOR . 'sitemap.xml';
        if(file_exists($path)) {            
            return Redirect::to('sitemap.xml');
        }
        
        return redirect('/');
    }
        
    public function reportIssue()
    {
        $user = Sentinel::getUser();
        if($user) {
            $username = $user->first_name.' '.$user->last_name;
            $email = $user->email;
        } else {
            $username = null;
            $email = null;
        }
        
        return View('frontend.report_issue', compact('username', 'email'));
    }
    
    public function postReportIssue(Request $request)
    {
        $upload_dir = Template::getReportImageDir();
        
        $rules = array(
            'user' => 'required|min:3',
            'email' => 'required|email',
            'nature' => 'required|min:3',
        );
        
        $files = $request->file('files');
        $files_count = count($files) - 1;
        foreach(range(0, $files_count) as $index) {
            $rules['files.' . $index] = 'required|mimes:png,jpeg,jpg,gif';
        }
        
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Redirect::back()->withInput()->withErrors($validator);
        }

        $images = array();
        if($files) {
            foreach($files as $file) {
                $filename = $file->getClientOriginalName();
                $file_info = pathinfo($filename);
                $extension = $file->getClientOriginalExtension() ?: 'png';

                $picture = $file_info['filename'] . '-'.time() . '.' . $extension;
                $destinationPath = public_path() . $upload_dir;
                $file->move($destinationPath, $picture);
                $images[$filename] = url($upload_dir.$picture);
            }
        }

        $to = explode(',', Template::getSetting('report_issue_email'));
        $subject = trans('report/form.subject');
               
        $data = $request->all();
        Mail::send('emails.issue_reported', compact('data', 'images'), function ($m) use ($data, $to, $subject) {
            $m->from(env('MAIL_USERNAME', ''), env('MAIL_SENDER', ''));
            $m->to($to, @trans('general.site_name'));
            $m->subject($subject);
        });
        
        return Redirect::back()->with('success', trans('report/message.success.submited'));
    }
    
    public function userPage()
    {
        try {
            $page = Page::where('url', 'LIKE', 'placead')->where('published', '=', 1)->firstOrFail();
            $leftcol = true;

            return View('page', compact('page', 'leftcol'));
        } catch (ModelNotFoundException $e) {
            return View('404');
        }
    }
    
    //temp function to create active_to dates from last_updated
    public function test(Request $request)
    {
        //return View('frontend/hellosign/successfully_signed');
        /*if($request->get('ariel') == 1) {
            $now = Carbon::now();
            $classifieds_expire = Template::getSetting('classifieds_expire');
            $nearme_expire = Template::getSetting('nearme_expire');

            /*
            $ads = Ads::all();
            foreach($ads as $ad) {
                $ad->last_updated = $ad->updated_at;
                $ad->save();
            }
            */
            /*
            $classifieds = Classified::all();
            foreach($classifieds as $classified) {
                $last_updated = Carbon::parse($classified->last_updated);
                if($last_updated->timestamp > 0) {
                    $days = $classifieds_expire->diffInDays($now);
                    $classified->active_to = $last_updated->addDays($days);
                    $classified->save();
                }
            }
            */
            /*
            $nearmes = Nearme::all();
            foreach($nearmes as $nearme) {
                $last_updated = Carbon::parse($nearme->last_updated);
                if($last_updated->timestamp > 0) {
                    $days = $nearme_expire->diffInDays($now);
                    $nearme->active_to = $last_updated->addDays($days);
                    $nearme->save();
                }
            }
            */
        /*}*/
    }
}