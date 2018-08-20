<?php
// Here is another test by Bryan! 
namespace App\Http\Controllers;

use App\Access;
use App\Http\Requests;
use App\Http\Requests\AccessRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Response;
use Sentinel;
use Lang;
use View;
use Mail;
use Redirect;
use Session;

use App\Helpers\Template;

class AccessController extends WeedController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $access = Access::all()->sortByDesc('id');

        return View('admin.access.index', compact('access'));
    }
    
    
    public function edit(Access $access)
    {
        return view('admin.access.edit', compact('access'));
    }
    
    public function grantAccess(Access $access)
    {
        $user = Sentinel::findById($access->user_id);
        $flag = $has_access = false;
               
        if($user) {
            switch($access->type) {
                case('ads'):
                    $resource_access = array('create.ads', 'update.ads');
                    $has_access = $user->hasAccess($resource_access);
                    if(!$has_access) {
                        $role_id = Template::getSetting('ads_access_group');
                        $role = Sentinel::findRoleById($role_id);

                        if($role) {
                            if(!$user->inRole($role->slug)) {
                                $role->users()->attach($user);
                                $flag = true;
                            }
                        }
                    }
                    break;
                case('nearme'):
                    $resource_access = array('create.nearme', 'update.nearme');
                    $has_access = $user->hasAccess($resource_access);
                    if(!$has_access) {
                        $role_id = Template::getSetting('nearme_access_group');
                        $role = Sentinel::findRoleById($role_id);

                        if($role) {
                            if(!$user->inRole($role->slug)) {
                                $role->users()->attach($user);
                                $flag = true;
                            }
                        }
                    }
                    break;
            }
            
            if($has_access) {
                return redirect('admin/access')->withInput()->with('error', trans('access/message.error.user_has_access'));
            }
            
            if($flag) {
                $access->granted = 1;
                $access->save();
                $user->save();
                
                $type = '';
                switch($access->type) {
                    case('neame'): $type = 'Near Me';break;
                    case('ads'): $type = 'Ads';break;
                }
                
                $to = $user->email;
                $subject = trans('emails/general.request-access.granted');
                $data = array();
                
                Mail::send('emails.access-granted', compact('data', 'type'), function ($m) use ($data, $to, $subject) {
                    $m->from(env('MAIL_USERNAME', ''), env('MAIL_SENDER', ''));
                    $m->to($to, @trans('general.site_name'));
                    $m->subject($subject);
                });
            }
            
            return redirect('admin/access')->with('success', trans('access/message.success.granted'));
        } else {
            return redirect('admin/access')->withInput()->with('error', trans('access/message.error.user_no_exist'));
        }
        
        return redirect('admin/access');
    }
    
    public function indexFront()
    {
        $type = null;
        $types = Access::getAllowedTypes();
        
        return View::make('frontend.access', compact('types', 'type'));
    }
    
    public function userAccess($type)
    {
        $types = Access::getAllowedTypes();
        $user_id = Sentinel::getUser()->id;
        try {
            $access = Access::where('user_id', '=', $user_id)->where('type', 'LIKE', $type)->firstOrFail();
            Session::set('notice', trans('access/form.notice'));
        } catch (ModelNotFoundException $e) {
            Session::set('notice', trans('access/form.requested'));
        }
        
        return View::make('frontend.access', compact('types', 'type'));
    }
    
    public function postAccess(AccessRequest $request)
    {
        $user_id = Sentinel::getUser()->id;
        $access = Access::where('user_id', '=', $user_id)->where('type', 'LIKE', $request->get('type'));
        
        if($access->count()) {
            return redirect('my-account')->with('success', trans('access/message.success.submited'));
        }
        
        $user = Sentinel::findById($user_id);
        
        $resource_access = array();
        switch($request->get('type')) {
            case('ads'): $resource_access = array('create.ads', 'update.ads'); break;
            case('nearme'): $resource_access = array('create.nearme', 'update.nearme'); break;
        }
        
        if($user->hasAccess($resource_access)) {
            return redirect('my-account')->with('success', trans('access/message.success.already_have'));
        }
        
        $access = new Access($request->only('type', 'business', 'contact', 'email', 'phone', 'address'));
        $access->user_id = $user_id;
        $access->save();
        
        $user_data = $user->toArray();
        
        $to = Template::getSetting('contactform_email');
        $subject = trans('emails/general.request-access.form');
        $data = (array)$request->all();

        if ($access->id) {
            Mail::send('emails.request-access', compact('data', 'user_data'), function ($m) use ($data, $to, $subject) {
                $m->from(env('MAIL_USERNAME', ''), env('MAIL_SENDER', ''));
                $m->to($to, @trans('general.site_name'));
                $m->subject($subject);
            });
            
            $to = $user->email;
            $data['user_email'] = true;
            
            Mail::send('emails.request-access', compact('data'), function ($m) use ($data, $to, $subject) {
                $m->from(env('MAIL_USERNAME', ''), env('MAIL_SENDER', ''));
                $m->to($to, @trans('general.site_name'));
                $m->subject($subject);
            });
                
            return redirect('my-account')->with('success', trans('access/message.success.create'));
        } else {
            return redirect('my-account')->withInput()->with('error', trans('access/message.error.create'));
        }
    }
    
    public function getModalDelete(Access $access)
    {
        $model = 'access';
        $confirm_route = $error = null;
        try {
            $confirm_route = route('delete/access', ['access' => $access->id]);

            return View('admin/layouts/modal_confirmation', compact('error', 'model', 'confirm_route'));
        } catch (GroupNotFoundException $e) {

            $error = trans('access/message.error.delete', compact('id'));
            return View('admin/layouts/modal_confirmation', compact('error', 'model', 'confirm_route'));
        }
    }

    public function destroy(Access $access)
    {
        if ($access->delete()) {
            return redirect('admin/access')->with('success', trans('access/message.success.delete'));
        } else {
            return redirect('admin.access')->withInput()->with('error', trans('access/message.error.delete'));
        }
    }
}
