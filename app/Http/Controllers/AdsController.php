<?php

namespace App\Http\Controllers;

use App\Ads;
use App\Cart;
use App\AdRenewOption;
use App\AdsPositions;
use App\AdsCompanies;
use App\Http\Requests;
use App\Http\Requests\AdsRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Response;
use Sentinel;
use File;
use Redirect;
use Validator;
use Carbon\Carbon;
use DateTime;

use GeoIP;
use App\Helpers\Template;
use Gregwar\Image\Image;

use Request;
use Lang;
use Datatables;
use Form;
use URL;

class AdsController extends WeedController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $ads = Ads::all();

        return View('admin.ads.index', compact('ads'));
    }

    public function pending()
    {
        $ads = Ads::getPendingAds()->get();

        return View('admin.ads.pending', compact('ads'));
    }


    public function create()
    {
        $adspositions = AdsPositions::where('published', '=', 1)->lists('title','id');
        $companies = AdsCompanies::lists('title','id');

        return view('admin.ads.create', compact('adspositions', 'companies'));
    }


    public function store(AdsRequest $request)
    {
        $request = Template::replaceDates($request);

        if (!$request->hasFile('image')) {
            return Redirect::back()->withInput()->with('error', trans('ads/message.image_is_required'));
        }

        $ads = new Ads($request->except('image'));

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $folderName = Template::getAdsImageDir();
            $picture = str_random(10) . '.' . $extension;
            $ads->image = $picture;
        }

        $ads->user_id = Sentinel::getUser()->id;
        $ads->save();

        if ($request->hasFile('image')) {
            $destinationPath = public_path() . $folderName;
            $request->file('image')->move($destinationPath, $picture);
        }

        if ($ads->id) {
            Log::info('Ads ad adminStore: ' . $ads->toJson());
            return redirect('admin/ads')->with('success', trans('ads/message.success.create'));
        } else {
            return redirect('admin/ads')->withInput()->with('error', trans('ads/message.error.create'));
        }

    }

    public function show(Ads $ads)
    {
        return view('admin.ads.show', compact('ads'));
    }


    public function edit(Ads $ads)
    {
        $adspositions = AdsPositions::where('published', '=', 1)->lists('title','id');
        $companies = AdsCompanies::lists('title','id');
        $last_transaction = $ads->getItemLastPaidTransaction();

        return view('admin.ads.edit', compact('ads', 'adspositions', 'companies', 'last_transaction'));
    }


    public function update(AdsRequest $request, Ads $ads)
    {
        $request = Template::replaceDates($request);

        if(!$request->hasFile('image') && !$ads->image) {
            return Redirect::back()->withInput()->with('error', trans('front/general.ads_image_required'));
        }

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $folderName = Template::getAdsImageDir();
            $picture = str_random(10) . '.' . $extension;
            $ads->image = $picture;
        }

        if ($request->hasFile('image')) {
            $destinationPath = public_path() . $folderName;
            $request->file('image')->move($destinationPath, $picture);
        }

        if ($ads->update($request->except('image', '_method', 'tags'))) {
            Log::info('Ads ad adminUpdate: ' . $ads->toJson());
            return redirect('admin/ads')->with('success', trans('ads/message.success.update'));
        } else {
            return redirect('admin/ads')->withInput()->with('error', trans('ads/message.error.update'));
        }
    }


    public function getModalDelete(Ads $ads)
    {
        $model = 'ads';
        $confirm_route = $error = null;
        try {
            $confirm_route = route('delete/ads', ['id' => $ads->id]);
            return View('admin/layouts/modal_confirmation', compact('error', 'model', 'confirm_route'));
        } catch (GroupNotFoundException $e) {

            $error = trans('ads/message.error.delete', compact('id'));
            return View('admin/layouts/modal_confirmation', compact('error', 'model', 'confirm_route'));
        }
    }


    public function destroy(Ads $ads)
    {

        if ($ads->delete()) {
            return redirect('admin/ads')->with('success', trans('ads/message.success.delete'));
        } else {
            return redirect('admin/ads')->withInput()->with('error', trans('ads/message.error.delete'));
        }
    }

    public function adLink($id = 0)
    {
        $location = GeoIP::getLocation();
        $user_ip = $location['ip'];
        $skip = explode(',',Template::getSetting('ads_ignore_ip'));

        try {
            $ad = Ads::findOrFail($id);

            if(!in_array($user_ip, $skip)) {
				$ad->increment('clicks');
			}

        } catch (ModelNotFoundException $e) {
            return Redirect::to('/');
        }

        return Redirect::to($ad->url);

    }

    public function userList()
    {
        $user = Sentinel::getUser();
        $ads = Ads::where('user_id', $user->id)->get();
        $require_approval = (int)Template::getSetting('ads_approval');

        return View('frontend.ads.user', compact('ads', 'require_approval'));
    }

    public function frontendCreate()
    {
        $adspositionsForSelet = AdsPositions::where('published', '=', 1)->lists('title','id');
        $adspositions = AdsPositions::where('published', '=', 1)->get();

        $companies = AdsCompanies::lists('title','id');

        $adrenewoptions = AdRenewOption::where('renewal_type', 'banner_ad')->get();

        return view('frontend.ads.create', compact('adspositionsForSelet', 'adspositions', 'companies', 'adrenewoptions'));
    }

    public function frontendStore(AdsRequest $request)
    {
        $request = Template::replaceDates($request);
        $postion = AdsPositions::where('id', '=', $request->get('position_id'))->firstOrFail();
        $max_width = $postion->width;
        $max_height = $postion->height;

        if ($request->hasFile('image')) {
            $rules = array(
                'image' => 'image|mimes:jpg,jpeg,bmp,png',
            );

            $validator = Validator::make($request->only('image'), $rules);

            if ($validator->fails()) {
                return Redirect::back()->withInput()->with('error', trans('front/general.file_type_notsupported'));
            }
        } else return Redirect::back()->withInput()->with('error', trans('front/general.image_is_required'));

        $msg = $this->validateDates($request);
        if($msg) return Redirect::back()->withInput()->with('error', $msg);

        $now = Carbon::now();
        $active_to = Carbon::parse($request->get('published_from'));
        $ads_expire = Template::getSetting('ads_expire');
        $days = $ads_expire->diffInDays($now);
        $active_to->addDays($days);

        $ads = new Ads($request->only('position_id', 'title', 'url', 'phone', 'published_from', 'recurring'));
        $ads->published_to = $active_to;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $folderName = Template::getAdsImageDir();
            $picture = str_random(10) . '.' . $extension;
            $ads->image = $picture;
        }

        $ads->user_id = Sentinel::getUser()->id;
        $ads->published = 1;

        if($request->get('renewal')) {
            $adrenewoption = AdRenewOption::where('id', $request->get('renewal'))->first();

            if($adrenewoption) {
                $ads->recurring_period = $adrenewoption->renewal_time;
                $ads->recurring_period_type = $adrenewoption->renewal_time_period;
                $ads->recurring_discount = $adrenewoption->renewal_discount;
            }
        }

        $ads->save();

        if ($request->hasFile('image')) {
            $destinationPath = public_path() . $folderName;
            $request->file('image')->move($destinationPath, $picture);

            Image::open($destinationPath.$picture)
                ->cropResize($max_width, $max_height)
                ->save($destinationPath.$picture);
        }

        if ($ads->id) {
            $approval = (int)Template::getSetting('ads_approval');
            if($approval) {
                $msg = trans('front/general.ad_created_need_approval');
            } else $msg = trans('front/general.ad_created');

            $item = Cart::insertToCart('ads', $ads);
            $ads = Ads::find($ads->id);

            Log::info('Ads ad frontendStore: ' . $ads->toJson());

            if($ads->paid) {
                return redirect('myads')->with('success', $msg);
            } else {
                return redirect('review');
            }
        } else {
            return Redirect::back()->withInput()->with('error', $msg);
        }
    }

    public function frontendEdit($slug = '')
    {
        try {
            $user_id = Sentinel::getUser()->id;
            $ad = Ads::where('slug', 'LIKE', $slug)->where('user_id', '=', $user_id)->firstOrFail();

            if(!$ad->id) {
                return redirect('my-account');
            }

        } catch (ModelNotFoundException $e) {
            return Response::view('404', array(), 404);
        }

        $adspositionsForSelet = AdsPositions::where('published', '=', 1)->lists('title','id');
        $adspositions = AdsPositions::where('published', '=', 1)->get();
        $companies = AdsCompanies::lists('title','id');
        $adrenewoptions = AdRenewOption::where('renewal_type', 'banner_ad')->get();

        return view('frontend.ads.edit', compact('ad', 'adspositionsForSelet', 'adspositions', 'companies', 'adrenewoptions'));
    }

    public function frontendUpdate(AdsRequest $request, Ads $ads)
    {
        $request = Template::replaceDates($request);

        $postion = AdsPositions::where('id', '=', $request->get('position_id'))->firstOrFail();
        $max_width = $postion->width;
        $max_height = $postion->height;

        if ($request->hasFile('image')) {
            $rules = array(
                'image' => 'image|mimes:jpg,jpeg,bmp,png',
            );

            $validator = Validator::make($request->only('image'), $rules);

            if ($validator->fails()) {
                return Redirect::back()->withInput()->with('error', trans('front/general.file_type_notsupported'));
            }
        }

        $msg = $this->validateDates($request, false);
        if($msg) return Redirect::back()->withInput()->with('error', $msg);

        if(!$request->hasFile('image') && !$ads->image) {
            return Redirect::back()->withInput()->with('error', trans('front/general.ads_image_required'));
        }

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $folderName = Template::getAdsImageDir();
            $picture = str_random(10) . '.' . $extension;
            $ads->image = $picture;
        }

        if ($request->hasFile('image')) {
            $destinationPath = public_path() . $folderName;
            $request->file('image')->move($destinationPath, $picture);

            Image::open($destinationPath.$picture)
                ->cropResize($max_width, $max_height)
                ->save($destinationPath.$picture);
        }

        // There was a bug when you can save this field as null and this is a fallback to prevent an error related to searching
        // for the related payment, if this field was overridden to null there is no way to find what the oryginal date was so
        // we use the current date instead.
        if ($ads->published_from === null || $ads->published_to === null) {
            $now = Carbon::now();
            $active_to = Carbon::now();
            $ads_expire = Template::getSetting('ads_expire');
            $days = $ads_expire->diffInDays($now);
            $active_to->addDays($days);
            $ads->published_from = $now;
            $ads->published_to = $active_to;
        }

        if ($ads->update($request->only('position_id', 'title', 'url', 'phone', 'recurring'))) {

            if($request->get('renewal')) {
                $adrenewoption = AdRenewOption::where('id', $request->get('renewal'))->first();

                if($adrenewoption) {
                    $ads->recurring_period = $adrenewoption->renewal_time;
                    $ads->recurring_period_type = $adrenewoption->renewal_time_period;
                    $ads->recurring_discount = $adrenewoption->renewal_discount;
                }
            }

            $ads->save();

            $approval = (int)Template::getSetting('ads_approval');
            if($approval) {
                $ads->approved = 0;
                $ads->save();
                $msg = trans('front/general.ad_edited_need_approval');
            } else $msg = trans('front/general.ad_edited');

            $item = Cart::insertToCart('ads', $ads);
            $ads = Ads::find($ads->id);

            Log::info('Ads ad frontendUpdate: ' . $ads->toJson());

            if($ads->paid) {
                return redirect('myads')->with('success', $msg);
            } else {
                return redirect('review');
            }
        } else {
            return Redirect::back()->withInput()->with('error', trans('ads/message.error.update'));
        }
    }

    public function validateDates($request, $new = true)
    {
        $publish_from = new DateTime($request->get('published_from'));
        //$publish_to = new DateTime($request->get('published_to'));
        $current_date = new DateTime();
        $msg = false;

        if ($new && $current_date > $publish_from) {
            $msg = trans('front/general.ads_start_date_after_today');
        } /*elseif($current_date > $publish_to) {
            $msg = trans('front/general.ads_end_date_after_today');
        } elseif($publish_from > $publish_to) {
            $msg = trans('front/general.ads_end_date_after_to_date');
        }*/

        return $msg;
    }

    public function getFrontModalDelete(Ads $ads)
    {
        $model = 'ads';
        $confirm_route = $error = null;

        try {
            $confirm_route = route('delete/ad/ad', ['id' => $ads->id]);
            return View('layouts.modal_confirmation', compact('error', 'model', 'confirm_route'));
        } catch (GroupNotFoundException $e) {

            $error = trans('ads/message.error.delete', compact('id'));
            return View('layouts.modal_confirmation', compact('error', 'model', 'confirm_route'));
        }
    }

    public function frontDestroy(Ads $ads)
    {

        if ($ads->delete()) {
            return redirect('myads')->with('success', trans('ads/message.success.delete'));
        } else {
            return redirect('myads')->withInput()->with('error', trans('ads/message.error.delete'));
        }
    }

    public function dataList()
    {
        if(Request::ajax()) {
            $ads = Ads::with('companydetails')->get(['id', 'user_id', 'title', 'phone', 'company', 'position_id', 'paid', 'published', 'approved', 'views', 'clicks', 'created_at']);

            return Datatables::of($ads)
                ->edit_column('author', function($ad){
                    return Form::Author($ad->user_id);
                })
                ->edit_column('title', function($ad){
                    return $ad->title;
                })
                ->edit_column('companydetails.title', function($ad){
                    if($ad->company)
                        return '<div class="catid" name="'.$ad->company.'">'.$ad->companydetails->title.'</div>';
                    else return '';
                })
                ->edit_column('position_id', function($ad){
                    return '<div class="catid" name="'.$ad->position_id.'">'.$ad->positions->title.'</div>';
                })
                ->edit_column('views',function($ad){
                    return $ad->views;
                })
                ->edit_column('clicks',function($ad){
                    return $ad->clicks;
                })
                ->edit_column('created_at', function($ad){
                    return Carbon::parse($ad->created_at)->diffForHumans();
                })
                ->edit_column('paid',function($ad){
                    return Form::Published($ad->paid);
                })
                ->edit_column('published',function($ad){
                    return Form::Published($ad->published);
                })
                ->edit_column('approved',function($ad){
                    return Form::Published($ad->approved);
                })

                ->add_column('actions', function($ad) {
                    $actions = '<a href="'. URL::to('admin/ads/' . $ad->id . '/show' ) .'">
                                    <i class="livicon" data-name="info" data-size="18" data-loop="true" data-c="#428BCA" data-hc="#428BCA" title="'.Lang::get('ads/table.preview').'"></i>
                                </a>
                                <a href="'. URL::to('admin/ads/' . $ad->id . '/edit' ) .'">
                                    <i class="livicon" data-name="edit" data-size="18" data-loop="true" data-c="#428BCA" data-hc="#428BCA" title="'.Lang::get('ads/table.update-ads').'"></i>
                                </a>
                                <a href="'. route('confirm-delete/ads', $ad->id) .'" data-toggle="modal" data-target="#delete_confirm">
                                    <i class="livicon" data-name="remove-alt" data-size="18" data-loop="true" data-c="#f56954" data-hc="#f56954" title="'.Lang::get('ads/table.delete-ads').'"></i>
                                </a>';

                    return $actions;
                })
                ->make(true);
        }
    }
}
