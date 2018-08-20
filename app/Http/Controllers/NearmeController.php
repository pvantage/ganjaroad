<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Response;
use Request;
use Carbon\Carbon;
use File;
use Mail;
use Validator;
use URL;
use Lang;
use Form;
use Redirect;
use Exception;

use Sentinel;
use GeoIP;
use Gregwar\Image\Image;
use Geocode;
use Datatables;
use Excel;

use App\User;
use App\Nearme;
use App\NearmeCategory;
use App\NearmeItemsCategory;
use App\Images;
use App\NearmeItems;
use App\Cart;
use App\AdRenewOption;
use App\Http\Requests;
use App\Http\Requests\NearmeRequest;
use App\Helpers\Template;
use App\Settings;

class NearmeController extends WeedController
{

    private $frontend_allowed_fields = array('category_id', 'title', 'content', 'active', 'recurring', 'url', 'email', 'phone', 'first_time', 'facebook', 'instagram', 'twitter', 'other_address', 'full_address', 'lattitude', 'longitude', 'address1', 'address2', 'country', 'city', 'state', 'zip', 'delivery', 'atm', 'min_age', 'wheelchair', 'security', 'credit_cards', 'hours', 'nearmeitems');

    public function __construct()
    {
        parent::__construct();
    }

    public function getIndexFrontend($squery = '')
    {
        $limit = (int)Template::getSetting('listview_nearme_limit');
        $approval = (int)Template::getSetting('nearme_approval');

        $search_query = session('nearme_search');
        if($search_query) {
            $location = $search_query;
        } else {
            $location = Template::getCurrentLocation();
        }

        $lat = $location['lat'];
        $lng = $location['lon'];

        $nearme = Nearme::getActive()
                ->selectRaw(
                    DB::raw('*, ( 3959 * acos( cos( radians(' . $lat . ') ) * cos( radians( lattitude ) ) * cos( radians( longitude ) - radians(' . $lng . ') ) + sin( radians(' . $lat .') ) * sin( radians(lattitude) ) ) ) AS distance')
                )->where(function($query) use ($squery)
                {
                    if($squery) $query->where('title', 'LIKE', '%'.$squery.'%');
                })->orderBy('distance')->paginate($limit);

        return View('frontend.nearme.index', compact('nearme' , 'location'));
    }

    public function getNearmeCategory($slug = '')
    {
        if ($slug == '') {
            $nearme = NearmeCategory::first();
        }

        $location = Template::getCurrentLocation();
        $lat = $location['lat'];
        $lng = $location['lon'];

        try {
            $nearmecategory = NearmeCategory::findBySlugOrIdOrFail($slug);

            if($nearmecategory->id) {
                $limit = (int)Template::getSetting('listview_nearme_limit');
                $approval = (int)Template::getSetting('nearme_approval');

                //$nearme = Nearme::getActive()->where('category_id', '=', $nearmecategory->id)->orderBy('updated_at', 'DESC')->paginate($limit);
                $nearme = Nearme::getActive()->where('category_id', '=', $nearmecategory->id)
                ->selectRaw(
                    DB::raw('*, ( 3959 * acos( cos( radians(' . $lat . ') ) * cos( radians( lattitude ) ) * cos( radians( longitude ) - radians(' . $lng . ') ) + sin( radians(' . $lat .') ) * sin( radians(lattitude) ) ) ) AS distance')
                )->orderBy('distance')->paginate($limit);
            }

        } catch (ModelNotFoundException $e) {
            return Response::view('404', array(), 404);
        }

        return View('frontend.nearme.index', compact('nearme','nearmecategory', 'location'));
    }

    public function getNearmeFrontend($slug = '')
    {
        if ($slug == '') {
            $nearme = Nearme::first();
        }
        try {
            $nearme = Nearme::findBySlugOrIdOrFail($slug);
        } catch (ModelNotFoundException $e) {
            return Response::view('404', array(), 404);
        }

        if(!$nearme->isActive()) return Response::view('404', array(), 404);

        $nearme->increment('views');

        $user = Sentinel::findById($nearme->user_id);


        if($nearme->category_id != 1) $dispensaries = Nearme::getActive()->where('category_id', '=', 1)->where('user_id', '=', $nearme->user_id)->get();
        else $dispensaries = false;

        if($nearme->category_id != 2)$delivery = Nearme::getActive()->where('category_id', '=', 2)->where('user_id', '=', $nearme->user_id)->get();
        else $delivery = false;

        if($nearme->category_id != 3)$deals = Nearme::getActive()->where('category_id', '=', 3)->where('user_id', '=', $nearme->user_id)->get();
        else $deals = false;

        $menu = NearmeItems::getActive()->where('nearme', '=', $nearme->id)->orderBy('category_id')->orderBy('name');
        $categories = $menu->lists('category_id');

        $items_categories = NearmeItemsCategory::whereIn('id', $categories)->lists('title', 'title');
        $menu = $menu->get();

        $location = GeoIP::getLocation();

        return View('frontend.nearme.item', compact('user', 'nearme', 'menu', 'items_categories', 'deals', 'dispensaries', 'delivery', 'location'));

    }

    public function index()
    {
        $nearmes = Nearme::all();

        return View('admin.nearme.index', compact('nearmes'));
    }

    public function approvalQueue()
    {
        $nearmes = Nearme::where('paid', '=', 1)->where('approved', '=', 0)->get();

        return View('admin.nearme.approval', compact('nearmes'));
    }

    public function create()
    {
        $users = $this->getNearmeAllowedUsers();
        $current_user = Sentinel::getUser()->id;
        $nearmecategory = NearmeCategory::lists('title', 'id');

        return view('admin.nearme.create', compact('users', 'current_user', 'nearmecategory'));
    }

    public function frontendCreate()
    {
        $nearmecategory = NearmeCategory::lists('title', 'id');
        $adrenewoptions = AdRenewOption::where('renewal_type', 'nearme')->get();

        return view('frontend.nearme.create', compact('nearmecategory','adrenewoptions'));
    }

    public function frontendEdit($slug = '')
    {
        try {
            $user_id = Sentinel::getUser()->id;
            $nearme = Nearme::where('slug', 'LIKE', $slug)->where('user_id', '=', $user_id)->firstOrFail();
            $adrenewoptions = AdRenewOption::where('renewal_type', 'nearme')->get();

            if(!$nearme->id) {
                return redirect('my-account');
            }

        } catch (ModelNotFoundException $e) {
            return Response::view('404', array(), 404);
        }

        $nearmecategory = NearmeCategory::lists('title', 'id');

        return view('frontend.nearme.edit', compact('nearme', 'nearmecategory','adrenewoptions'));
    }

    public function frontendUpdate(NearmeRequest $request, Nearme $nearme)
    {
        /*if(!$request->hasFile('image') && !$nearme->image) {
            return Redirect::back()->withInput()->with('error', trans('front/general.nearme_image_required'));
        }*/

        $fields = $this->frontend_allowed_fields;
        if ($nearme->update($request->only($fields))) {
            if ($request->hasFile('image')) {
                $nearme->image = $this->uploadMainImage($request, $nearme->id);
                $nearme->save();
            }
            if($request->get('renewal')) {
                $adrenewoption = AdRenewOption::where('id', $request->get('renewal'))->first();

                if($adrenewoption) {
                    $nearme->recurring_period = $adrenewoption->renewal_time;
                    $nearme->recurring_period_type = $adrenewoption->renewal_time_period;
                    $nearme->recurring_discount = $adrenewoption->renewal_discount;
                }
            }
    
            $nearme->save();
            Images::updateImages('nearme', $nearme->id, $request->get('images'));
            NearmeItems::addItems($request, $nearme->id);

            $msg = trans('front/general.nearme_updated');

            $item = Cart::insertToCart('nearme', $nearme);
            $nearme = Nearme::find($nearme->id);

            Log::info('Nearme ad frontendUpdate: ' . $nearme->toJson());

            if(!$item || $nearme->paid) {
                return redirect('mynearme')->with('success', $msg);
            } else return redirect('review');
        } else {
            return Redirect::back()->withInput()->with('error', trans('nearme/message.error.update'));
        }
    }

    public function frontendStore(NearmeRequest $request)
    {
        /*if(!$request->hasFile('image')) {
            return Redirect::back()->withInput()->with('error', trans('front/general.nearme_image_required'));
        }*/

        $fields = $this->frontend_allowed_fields;
        $nearme = new Nearme($request->only($fields));

        $nearme->user_id = Sentinel::getUser()->id;
        $nearme->published = 1;
        $nearme->active = 1;

        if($request->get('renewal')) {
            $adrenewoption = AdRenewOption::where('id', $request->get('renewal'))->first();

            if($adrenewoption) {
                $nearme->recurring_period = $adrenewoption->renewal_time;
                $nearme->recurring_period_type = $adrenewoption->renewal_time_period;
                $nearme->recurring_discount = $adrenewoption->renewal_discount;
            }
        }


        $nearme->save();

        if ($nearme->id) {
            if ($request->hasFile('image')) {
                $nearme->image = $this->uploadMainImage($request, $nearme->id);
                $nearme->save();
            } elseif($picture = $request->get('old_image')) {
                $image = $this->assignTempImage($picture, $nearme->id);
                if($image) {
                    $nearme->image = $image;
                    $nearme->save();
                }
            }

            $to = Template::getSetting('nearme_email');
            $subject = trans('emails/general.nearme.created');
            $data = $nearme->toArray();
            $category = $nearme->category->toArray();

            Images::updateImages('nearme', $nearme->id, $request->get('images'));
            NearmeItems::addItems($request, $nearme->id);

            $approval = (int)Template::getSetting('nearme_approval');
            if($approval) $msg = trans('front/general.nearme_created_need_approval');
            else $msg = trans('front/general.nearme_created');

            $item = Cart::insertToCart('nearme', $nearme);
            $nearme = Nearme::find($nearme->id);

            Log::info('Nearme ad frontendStore: ' . $nearme->toJson());

            if(!$item || $nearme->paid) {
                return redirect('mynearme')->with('success', $msg);
            } else {
                return redirect('review');
            }


            return redirect('mynearme')->with('success', $msg);


        } else {
            return Redirect::back()->withInput()->with('error', trans('general.nearme_created_faild'));
        }

    }

    public function store(NearmeRequest $request)
    {
        $request = Template::replaceDates($request);
        $nearme = new Nearme($request->except('image', 'images', '_method'));

        if(!$request->get('user_id')) $nearme->user_id = Sentinel::getUser()->id;
        $nearme->save();

        if ($nearme->id) {
            if ($request->hasFile('image')) {
                $nearme->image = $this->uploadMainImage($request, $nearme->id);
                $nearme->save();
            }
            Images::updateImages('nearme', $nearme->id, $request->get('images'));
            NearmeItems::addItems($request, $nearme->id);

            Log::info('Nearme ad adminStore: ' . $nearme->toJson());

            return redirect('admin/nearme')->with('success', trans('nearme/message.success.create'));
        } else {
            return redirect('admin/nearme')->withInput()->with('error', trans('nearme/message.error.create'));
        }

    }

    public function show(Nearme $nearme)
    {
        return view('admin.nearme.show', compact('nearme'));
    }


    public function edit(Nearme $nearme)
    {
        $users = $this->getNearmeAllowedUsers();
        $nearmecategory = NearmeCategory::lists('title', 'id');
        $last_transaction = $nearme->getItemLastPaidTransaction();

        return view('admin.nearme.edit', compact('users', 'nearme', 'nearmecategory', 'last_transaction'));
    }


    public function update(NearmeRequest $request, Nearme $nearme)
    {
        $request = Template::replaceDates($request);
        if ($nearme->update($request->except('image', 'images', '_method'))) {
            if ($request->hasFile('image')) {
                $nearme->image = $this->uploadMainImage($request, $nearme->id);
                $nearme->save();
            }
            Images::updateImages('nearme', $nearme->id, $request->get('images'));
            NearmeItems::addItems($request, $nearme->id);

            Log::info('Nearme ad adminUpdate: ' . $nearme->toJson());

            return redirect('admin/nearme')->with('success', trans('nearme/message.success.update'));
        } else {
            return redirect('admin/nearme')->withInput()->with('error', trans('nearme/message.error.update'));
        }
    }

    public function getModalDelete(Nearme $nearme)
    {
        $model = 'nearme';
        $confirm_route = $error = null;

        try {
            $confirm_route = route('delete/nearme', ['id' => $nearme->id]);
            return View('admin.layouts.modal_confirmation', compact('error', 'model', 'confirm_route'));
        } catch (GroupNotFoundException $e) {

            $error = trans('nearme/message.error.delete', compact('id'));
            return View('admin.layouts.modal_confirmation', compact('error', 'model', 'confirm_route'));
        }
    }

    public function getFrontModalDelete(Nearme $nearme)
    {
        $model = 'nearme';
        $confirm_route = $error = null;

        try {
            $confirm_route = route('delete/nearme/nearme', ['id' => $nearme->id]);
            return View('layouts.modal_confirmation', compact('error', 'model', 'confirm_route'));
        } catch (GroupNotFoundException $e) {

            $error = trans('nearme/message.error.delete', compact('id'));
            return View('layouts.modal_confirmation', compact('error', 'model', 'confirm_route'));
        }
    }

    public function frontDestroy(Nearme $nearme)
    {

        if ($nearme->delete()) {
            return redirect('mynearme')->with('success', trans('nearme/message.success.delete'));
        } else {
            return redirect('mynearme')->withInput()->with('error', trans('nearme/message.error.delete'));
        }
    }


    public function destroy(Nearme $nearme)
    {
        if ($nearme->delete()) {
            return Redirect::back()->withInput()->with('success', trans('nearme/message.success.delete'));
        } else {
            return Redirect::back()->withInput()->with('error', trans('nearme/message.error.delete'));
        }
    }

    public function getClosest()
    {
        if(Request::ajax()) {
            $id = Request::get('id');
            if($id == '#') $parent = 0;
            else $parent = (int)$id;

            $categories = ClassifiedCategory::where('parent', $parent)->orderBy('position', 'asc')->get(['id', 'title', 'published']);

            return Datatables::of($categories)
                ->add_column('text', function($category){
                    return $category->title;

                })
                ->add_column('type', function($category){
                    $types = array();
                    if($category->published == 0) $type = 'notpublished';
                    elseif(!(int)$category->parent) $type = 'root';

                    return $type;

                })
                ->add_column('children', function($category) {
                    if(ClassifiedCategory::where('parent', $category->id)->count()) $count = true;
                    else $count = false;
                    return $count;
                })
                ->add_column('state', function($category) {
                    $state = array();
                    if($classified_id = Request::get('classified')) {
                        $checked = $category->isChecked($classified_id);
                        if($checked) $state['selected'] = true;
                        else $state['selected'] = false;
                    }
                    return $state;
                })
                ->make(true);
        }
    }

    public function userList()
    {
        $user = Sentinel::getUser();
        $nearmes = Nearme::where('user_id', $user->id)->get();
        $require_approval = (int)Template::getSetting('nearme_approval');

        return View('frontend.nearme.user', compact('nearmes', 'require_approval'));
    }

    public function uploadMainImage(NearmeRequest $request, $id)
    {
        $max_width = (int)Template::getSetting('nearme_image_width');
        $max_height = (int)Template::getSetting('nearme_image_height');

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $folderName = Template::getNearMeImageDir() . $id . '/';
            $picture = str_random(10) . '.' . $extension;

            $destinationPath = public_path() . $folderName;
            $full_path = $destinationPath.$picture;

            $request->file('image')->move($destinationPath, $picture);

            list($width, $height) = getimagesize($full_path);
            if($width > $max_width || $height > $max_height) {
                Image::open($full_path)
                    ->cropResize($max_width, $max_height)
                    ->save($full_path);
            }

            return $picture;
        }

        return '';
    }

    public function assignTempImage($image, $id)
    {
        $max_width = (int)Template::getSetting('nearme_image_width');
        $max_height = (int)Template::getSetting('nearme_image_height');

        $folderName = Template::getTempImageDir();
        $path = realpath(public_path() . $folderName . $image);

        if (File::exists($path)) {
            $folderName = Template::getNearMeImageDir();
            $destinationPath = public_path() . $folderName . $id;

            if(!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath);
            }

            $full_path = $destinationPath . DIRECTORY_SEPARATOR . $image;
            rename($path, $full_path);

            list($width, $height) = getimagesize($full_path);
            if($width > $max_width || $height > $max_height) {
                Image::open($full_path)
                    ->cropResize($max_width, $max_height)
                    ->save($full_path);
            }

            return $image;
        }

        return false;
    }

    public function import()
    {
        $nearmecategories = NearmeCategory::all()->lists('title', 'id');

        return View('admin.nearme.import', compact('nearmecategories'));
    }

    public function postImport(\Illuminate\Http\Request $request)
    {
        $rules = array(
            'file' => 'required|file',
            'category_id' => 'required|exists:nearme_categories,id',
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Redirect::back()->withInput()->with('error', trans('nearme/message.import.file_type_notsupported'));
        }

        $path = \Illuminate\Support\Facades\Input::file('file')->getRealPath();
        $data = Excel::load($path, function($reader) {
        })->get();

        if(!empty($data) && $data->count()){
            foreach ($data as $key => $row) {
                if($row->name && $row->email) {
                    $credentials = [
                        'email' => $row->email,
                    ];

                    $user = Sentinel::findByCredentials($credentials);
                    $uniquePassword = '';

                    if(!$user) {
                        $uniquePassword = str_random(8);
                        $credentials = [
                            'email'    => $row->email,
                            'password' => $uniquePassword,
                            'published' => 1
                        ];

                        $user = Sentinel::registerAndActivate($credentials);
                        $user->imported = 1;
                        $user->save();
                    }

                    if($user) {
                        $resource_access = array('create.nearme', 'update.nearme');
                        $has_access = $user->hasAccess($resource_access);
                        if(!$has_access) {
                            $role_id = Template::getSetting('nearme_access_group');
                            $role = Sentinel::findRoleById($role_id);

                            if($role) {
                                if(!$user->inRole($role->slug)) $role->users()->attach($user);
                            }
                        }
                    } else return Redirect::back()->with('error', trans('nearme/message.error.import'));

                    $full_address = array();

                    $nearme = new Nearme();
                    $nearme->user_id = $user->id;
                    $nearme->category_id = (int)$request->get('category_id');

                    if($row->address) {
                        $nearme->address1 = $row->address;
                        $full_address[] = $row->address;
                    }

                    if($row->city) {
                        $nearme->city = $row->city;
                        $full_address[] = $row->city;
                    }

                    if($row->state) {
                        $state = Template::matchState($row->state);
                        $nearme->state = $state;
                        $full_address[] = $state;
                    }

                    if($row->zip) {
                        $nearme->zip = $row->zip;
                        $full_address[] = $row->zip;
                    }

                    if($row->country) {
                        $nearme->country = $row->country;
                        $full_address[] = $row->country;
                    } else {
                        $nearme->country = 'US';
                        $full_address[] = 'US';
                    }

                    $formated_address = implode(' ', $full_address);
                    $response = Geocode::make()->address($formated_address);
                    if ($response) {
                        $nearme->lattitude = $response->latitude();
                        $nearme->longitude = $response->longitude();
                    }

                    if($row->phone) {
                        $nearme->phone = $row->phone;
                    }

                    if($row->display_other_info) {
                        $nearme->other_address = 1;
                        $nearme->full_address = $row->display_other_info;
                    }

                    $response = Geocode::make()->address($formated_address);

                    $nearme->published = 1;
                    $nearme->paid = 1;
                    $nearme->approved = 1;
                    $nearme->title = $row->name;
                    $nearme->email = $row->email;
                    $nearme->last_updated = Carbon::now()->addMonths(2); //free for 3 months

                    $nearme->save();
                    if($nearme->id && $user->email) {
                        $this->sendImportEmail($user, $uniquePassword, $nearme);
                    }
                }

            }

            return Redirect::back()->with('success', trans('nearme/message.success.import'));
        }

        return Redirect::back()->with('error', trans('nearme/message.error.import'));
    }

    public function sendImportEmail($user, $password, $nearme)
    {
        $to = $user->email;
        $remove_email = Template::getSetting('contactform_email');
        $subject = trans('emails/general.import.email_subject');

        $mail = Mail::send('emails.import-nearme', compact('user', 'password', 'nearme', 'remove_email'), function ($m) use ($to, $subject) {
            $m->from(env('MAIL_USERNAME', ''), env('MAIL_SENDER', ''));
            $m->to($to, @trans('general.site_name'));
            $m->subject($subject);
        });

        return $mail;
    }

    public function getNearmeAllowedUsers()
    {
        /*$role = Sentinel::findRoleById(1);
        $users = $role->users()->with('roles')->get();*/
        $all_users = User::where('published', '=', 1)->orderBy('email')->get();

        $users = array();
        foreach($all_users as $user) {
            $resource_access = array('create.nearme', 'update.nearme');
            $has_access = $user->hasAccess($resource_access);

            if($has_access) {
                $users[$user->id] = $user->email;
            }
        }

        return $users;
    }

    public function getFrontModalUnpublish(Nearme $nearme)
    {
        $model = 'nearme';
        $confirm_route = $error = null;

        try {
            $confirm_route = route('unpublish/nearme/nearme', ['id' => $nearme->id]);
            return View('layouts.modal_confirmation_unpublish', compact('error', 'model', 'confirm_route'));
        } catch (GroupNotFoundException $e) {

            $error = trans('nearme/message.error.unpublish', compact('id'));
            return View('layouts.modal_confirmation_unpublish', compact('error', 'model', 'confirm_route'));
        }
    }

    public function frontUnpublish(Nearme $nearme)
    {
        $nearme->active = 0;
        if ($nearme->save()) {
            return redirect('mynearme')->with('success', trans('nearme/message.success.unpublished'));
        } else {
            return redirect('mynearme')->withInput()->with('error', trans('nearme/message.error.unpublished'));
        }
    }

    public function dataList()
    {
        if(Request::ajax()) {
            $nearmes = Nearme::all();

            return Datatables::of($nearmes)
                ->edit_column('author', function($nearme){
                    return Form::Author($nearme->user_id);
                })
                ->edit_column('title', function($nearme){
                    return $nearme->title;
                })
                ->edit_column('category_id', function($nearme){
                    return '<div class="catid" name="'.$nearme->category_id.'">'.$nearme->category->title.'</div>';
                })
                ->edit_column('active_to', function($nearme){
                    return Carbon::parse($nearme->active_to)->format(Template::getDisplayedDateFormat());
                })
                ->edit_column('created_at', function($nearme){
                    return Carbon::parse($nearme->created_at)->format('m/d/Y');
                })
                ->edit_column('paid',function($nearme){
                    return Form::Published($nearme->paid);
                })
                ->edit_column('published',function($nearme){
                    return Form::Published($nearme->published);
                })
                ->edit_column('approved',function($nearme){
                    return Form::Published($nearme->approved);
                })

                ->add_column('actions', function($nearme) {
                    $actions = '<a href="'. URL::to('admin/nearme/' . $nearme->id . '/edit' ) .'">
                                    <i class="livicon" data-name="edit" data-size="18" data-loop="true" data-c="#428BCA" data-hc="#428BCA" title="'.Lang::get('nearme/table.update-nearme').'"></i>
                                </a>
                                <a href="'. route('confirm-delete/nearme', $nearme->id) .'" data-toggle="modal" data-target="#delete_confirm">
                                    <i class="livicon" data-name="remove-alt" data-size="18" data-loop="true" data-c="#f56954" data-hc="#f56954" title="'.Lang::get('nearme/table.delete-nearme').'"></i>
                                </a>';

                    return $actions;
                })
                ->make(true);
        }
    }
}
