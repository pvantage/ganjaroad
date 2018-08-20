<?php

namespace App\Http\Controllers;

use App\AdRenewOption;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Response;
use Redirect;
use Sentinel;
use Request;
use Illuminate\Http\Request as IlluminateRequest;
use GeoIP;
use Validator;
use Lang;
use Carbon\Carbon;
use Datatables;
use Form;
use URL;
//use Illuminate\Support\Facades\Input as Input;
use DB;

use App\Helpers\Template;
use App\AddRenewOption;
use App\Classified;
use App\ClassifiedCategory;
use App\ClassifiedFields;
use App\ClassifiedFieldsValues;
use App\ClassifiedCategoryValues;
use App\Images;
use App\Issues;
use App\Cart;
use App\ReportedItems;
use App\Http\Requests;
use App\Http\Requests\ClassifiedRequest;

class ClassifiedController extends WeedController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getClassifiedFrontend($slug = '')
    {
        if ($slug == '') {
            $classified = Classified::first();
        }
        try {
            $classified = Classified::findBySlugOrIdOrFail($slug);
            $classified->increment('views');
        } catch (ModelNotFoundException $e) {
            return Response::view('404', array(), 404);
        }

        if($classified->isActive()) return View('frontend.classifieds.item', compact('classified'));
        else return Response::view('404', array(), 404);
    }

    public function index()
    {
        $classifieds = Classified::all();

        return View('admin.classified.index', compact('classifieds'));
    }

    public function create()
    {
        $classifiedcategory = ClassifiedCategory::where('parent', '=', 0)->orderBy('position')->lists('title', 'id');
        $classifiedfields = ClassifiedFields::where('published', '=', 1)->orderBy('position')->get();

        return view('admin.classified.create', compact('classifiedcategory', 'classifiedfields'));
        //return view('admin.classified.create', compact('classifiedcategory', 'classifiedfields', 'current_user', 'users'));
    }

    public function store(ClassifiedRequest $request)
    {
        $request = Template::replaceDates($request);
        $this->validateExtraFields($request);

        $classified = new Classified($request->only('title', 'content', 'map_address', 'hide_map', 'lattitude', 'longitude', 'published', 'active', 'recurring', 'approved', 'paid', 'active_to'/*, 'user_id'*/));

        $classified->user_id = Sentinel::getUser()->id;
        $classified->save();

		ClassifiedCategoryValues::updateCategoriesValues($request->get('categories'), $classified->id, $request->get('multicategory'));
        ClassifiedFieldsValues::createFields($request, $classified->id);

        if ($classified->id) {
            Log::info('Classified ad adminStore: ' . $classified->toJson());
            return redirect('admin/classified')->with('success', trans('classified/message.success.create'));
        } else {
            return redirect('admin/classified')->withInput()->with('error', trans('classified/message.error.create'));
        }

    }

    public function show(Classified $classified)
    {
        return view('admin.classified.show', compact('classified'));
    }


    public function edit(Classified $classified)
    {
        $classifiedcategory = ClassifiedCategory::all()->sortBy('position');
        $classifiedfields = ClassifiedFields::where('published', '=', 1)->orderBy('position')->get();

        $extrafields = ClassifiedFieldsValues::all();
        $collection = $extrafields->filter(function($item) use ($classified) {
            return $item->classified_id == $classified->id;
        });

        $classifiedfieldsvalues = $collection->toArray();
        $issues = Issues::where('item_id', '=', $classified->id)->where('reviewed', '=', 0)->groupBy('code')->get();
        $last_transaction = $classified->getItemLastPaidTransaction();

        return view('admin.classified.edit', compact('classified', 'classifiedcategory', 'classifiedfields', 'classifiedfieldsvalues', 'issues', 'last_transaction', 'active_to'));
    }

    public function frontendUpdate(ClassifiedRequest $request, Classified $classified)
    {
        $this->validateExtraFields($request);

		ClassifiedCategoryValues::updateCategoriesValues($request->get('categories'), $classified->id, $request->get('multicategory'));
        ClassifiedFieldsValues::updateFields($request, $classified->id);

        if ($classified->update($request->only('title', 'content', 'active', 'recurring', 'map_address', 'hide_map', 'lattitude', 'longitude'))) {

            if($request->get('renewal')) {
                $adrenewoption = AdRenewOption::where('id', $request->get('renewal'))->first();
                if($adrenewoption) {
                    $classified->recurring_period = $adrenewoption->renewal_time;
                    $classified->recurring_period_type = $adrenewoption->renewal_time_period;
                    $classified->recurring_discount = $adrenewoption->renewal_discount;
                }
            }

            $classified->save();

            $approval = (int)Template::getSetting('classified_approval');
            if($approval) {
                $classified->approved = 0;
                $classified->save();
                $msg = trans('front/general.classified_updated_need_approval');
            } else $msg = trans('front/general.classified_updated');

            $item = Cart::insertToCart('classifieds', $classified);
            $classified = Classified::find($classified->id);

            Log::info('Classified ad frontendUpdate: ' . $classified->toJson());

            if(!$item || $classified->paid) {
                return redirect('myclassifieds')->with('success', $msg);
            } else {
                return redirect('review');
            }
        } else {
            return Redirect::back()->withInput()->with('error', trans('classified/message.error.update'));
        }
    }

    public function update(ClassifiedRequest $request, Classified $classified)
    {
        $request = Template::replaceDates($request);
        $this->validateExtraFields($request);

		ClassifiedCategoryValues::updateCategoriesValues($request->get('categories'), $classified->id, $request->get('multicategory'));
        ClassifiedFieldsValues::updateFields($request, $classified->id);

        if ($classified->update($request->only('title', 'content', 'map_address', 'hide_map', 'lattitude', 'longitude', 'published', 'active', 'approved', 'paid', 'recurring', 'active_to'))) {
            Log::info('Classified ad adminUpdate: ' . $classified->toJson());
            return redirect('admin/classified')->with('success', trans('classified/message.success.update'));
        } else {
            return redirect('admin/classified')->withInput()->with('error', trans('classified/message.error.update'));
        }
    }

    public function frontendEdit($slug = '')
    {
        try {
            $user_id = Sentinel::getUser()->id;
            $classified = Classified::where('slug', 'LIKE', $slug)->where('user_id', '=', $user_id)->firstOrFail();

            if(!$classified->id) {
                return redirect('my-account');
            }

        } catch (ModelNotFoundException $e) {
            return Response::view('404', array(), 404);
        }

        $classifiedcategory = ClassifiedCategory::where('parent', '=', 0)->orderBy('position')->lists('title', 'id');
        $classifiedfields = ClassifiedFields::where('published', '=', 1)->orderBy('position')->get();

        $extrafields = ClassifiedFieldsValues::all();
        $collection = $extrafields->filter(function($item) use ($classified) {
            return $item->classified_id == $classified->id;
        });

        $adrenewoptions = AdRenewOption::where('renewal_type', 'classified')->get();

        $classifiedfieldsvalues = $collection->toArray();
        $issues = Issues::where('item_id', '=', $classified->id)->where('reviewed', '=', 0);
        if($issues->count()) {
            foreach($issues->get() as $issue) {
                session(['bad_word_warning' => Lang::get('front/general.classified_'.$issue->code.'_issue_without_link')]);
            }
        }

        if($classified->isReported()) session(['error' => Lang::get('front/general.classified_is_reported')]);
        return view('frontend.classifieds.edit', compact('classified', 'classifiedcategory', 'classifiedfields', 'classifiedfieldsvalues', 'adrenewoptions'));

    }

    private function validateExtraFields(ClassifiedRequest $request)
    {
        $classifiedfields = ClassifiedFields::all()->sortBy('position');
        foreach($classifiedfields as $field) {
            $this->validate($request, [
                $field->code => $field->rules,
            ]);
        }
    }

    public function getModalDelete(Classified $classified)
    {
        $model = 'classified';
        $confirm_route = $error = null;
        try {
            $confirm_route = route('delete/classified', ['id' => $classified->id]);
            return View('admin/layouts/modal_confirmation', compact('error', 'model', 'confirm_route'));
        } catch (GroupNotFoundException $e) {

            $error = trans('classified/message.error.delete', compact('id'));
            return View('admin/layouts/modal_confirmation', compact('error', 'model', 'confirm_route'));
        }
    }

    public function destroy(Classified $classified)
    {
        ClassifiedFieldsValues::removeFields($classified->id);

        if ($classified->delete()) {
            return redirect('admin/classified')->with('success', trans('classified/message.success.delete'));
        } else {
            return redirect('admin/classified')->withInput()->with('error', trans('classified/message.error.delete'));
        }
    }

	public function data()
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
                /*->add_column('state', function($category) {
                    $state = array();
                    if($category->published == 0) $state['disabled'] = true;

                    return $state;
                })*/
                ->make(true);
        }
    }

    public function frontendCreate()
    {
        $classifiedcategory = ClassifiedCategory::where('published', '=', 1)->where('parent', '=', 0)->orderBy('position')->lists('title', 'id');
        $classifiedfields = ClassifiedFields::where('published', '=', 1)->orderBy('position')->get();
        $adrenewoptions = AdRenewOption::where('renewal_type', 'classified')->get();

        return view('frontend.classifieds.create', compact('classifiedcategory', 'classifiedfields', 'all_categories', 'adrenewoptions'));
    }

    public function frontendStore(ClassifiedRequest $request)
    {
        $this->validateExtraFields($request);
        $classified = new Classified($request->only('title', 'content', 'map_address', 'hide_map', 'lattitude', 'longitude', 'recurring'));
        $classified->user_id = Sentinel::getUser()->id;
        $classified->published = 1;
        $classified->active = 1;

        if($request->get('renewal')) {
            $adrenewoption = AdRenewOption::where('id', $request->get('renewal'))->first();
            if($adrenewoption) {
                $classified->recurring_period = $adrenewoption->renewal_time;
                $classified->recurring_period_type = $adrenewoption->renewal_time_period;
                $classified->recurring_discount = $adrenewoption->renewal_discount;
            }
        }

        $classified->save();

		ClassifiedCategoryValues::updateCategoriesValues($request->get('categories'), $classified->id, $request->get('multicategory'));
        ClassifiedFieldsValues::createFields($request, $classified->id);

        if ($classified->id) {

            $item = Cart::insertToCart('classifieds', $classified);
            $classified = Classified::find($classified->id);

            Log::info('Classified ad frontendStore: ' . $classified->toJson());

            if($classified->paid) {
                $approval = (int)Template::getSetting('classified_approval');
                if($approval) $msg = trans('front/general.classified_created_need_approval');
                else $msg = trans('front/general.classified_created');
                return redirect('myclassifieds')->with('success', $msg);
            } else {
                return redirect('review');
            }
        } else {
            return Redirect::back()->withInput()->with('error', trans('front/general.classified_created_faild'));
        }
    }

    public function checkIfLastCategory($categories)
    {
        $classified_categories = ClassifiedFieldsValues::whereIn('id', $categories)->count();
        if(count($categories) > $classified_categories) return true;

        return false;
    }

    public function reportItem(Classified $classified)
    {
        $request = Request();

        $rules = array(
            'reason' => 'required|min:3',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Redirect::back()->withInput()->withInput()->withErrors($validator);
        }

        $location = GeoIP::getLocation();
        $user = Sentinel::getUser();

        $reported = ReportedItems::where(function ($query) use ($user, $location) {
            if($user) $query->where('user_id', 'LIKE', $user->id)->orWhere('user_ip', 'LIKE', $location['ip']);
        })->where('type', 'LIKE', 'classified')->where('item_id', '=', $classified->id)->count();

        $url = Template::classifiedsLink($classified->id, $classified->slug);
        if($reported) {
            return Redirect::to($url)->withInput()->with('error', trans('front/general.classifieds_already_reported'));
        } else {
            $report = new ReportedItems();
            $report->type = 'classified';
            $report->reason = $request->get('reason');
            $report->item_id = $classified->id;
            if($user) $report->user_id = $user->id;
            $report->user_ip = $location['ip'];

            $report->save();

            if($classified->isReported()) return Redirect::route('classifieds')->with('success', trans('front/general.classifieds_reported_max'));
            else return Redirect::to($url)->with('success', trans('front/general.classifieds_reported'));
        }
    }

    public function userList()
    {
        $user = Sentinel::getUser();
        $classifieds = Classified::where('user_id', $user->id)->get();
        $require_approval = (int)Template::getSetting('classified_approval');

        return View('frontend.classifieds.user', compact('classifieds', 'require_approval'));
    }

    public function getFrontModalDelete(Classified $classified)
    {
        $model = 'classified';
        $confirm_route = $error = null;
        try {
            $confirm_route = route('classifieditem/delete/classified', ['id' => $classified->id]);
            return View('layouts/modal_confirmation', compact('error', 'model', 'confirm_route'));
        } catch (GroupNotFoundException $e) {

            $error = trans('classified/message.error.delete', compact('id'));
            return View('layouts/modal_confirmation', compact('error', 'model', 'confirm_route'));
        }
    }

    public function getReportModal( $classified = '')
    {
        $model = 'classified';
        $confirm_route = $error = null;

        try {
            return View('layouts/modal_report', compact('error', 'model', 'classified'));
        } catch (GroupNotFoundException $e) {

            $error = trans('classified/message.error.report', compact('id'));
            return View('layouts/modal_confirmation', compact('error', 'model', 'confirm_route'));
        }
    }

    public function frontDestroy(Classified $classified)
    {
        ClassifiedFieldsValues::removeFields($classified->id);

        if ($classified->delete()) {
            return redirect('myclassifieds')->with('success', trans('classified/message.success.delete'));
        } else {
            return redirect('myclassifieds')->withInput()->with('error', trans('classified/message.error.delete'));
        }
    }

    public function getFrontModalUnpublish(Classified $classified)
    {
        $model = 'classified';
        $confirm_route = $error = null;
        try {
            $confirm_route = route('classifieditem/unpublish/classified', ['id' => $classified->id]);
            return View('layouts/modal_confirmation_unpublish', compact('error', 'model', 'confirm_route'));
        } catch (GroupNotFoundException $e) {

            $error = trans('classified/message.error.delete', compact('id'));
            return View('layouts/modal_confirmation_unpublish', compact('error', 'model', 'confirm_route'));
        }
    }

    public function frontUnpublish(Classified $classified)
    {
        $classified->active = 0;
        if ($classified->save()) {
            return redirect('myclassifieds')->with('success', trans('classified/message.success.unpublished'));
        } else {
            return redirect('myclassifieds')->withInput()->with('error', trans('classified/message.error.unpublished'));
        }
    }

    public function dataList(IlluminateRequest $request)
    {
        if(Request::ajax()) {
            $published = $request->get('filter_published', null);
            if($published == '') {
                $classifieds = Classified::groupBy('classifieds.id');
            } else {
                $ids = $classifieds = Classified::getActive()->pluck('id')->toArray();
                if($published) {
                    $classifieds = Classified::whereIn('classifieds.id', $ids)->groupBy('classifieds.id');
                } else {
                    $classifieds = Classified::whereNotIn('classifieds.id', $ids)->groupBy('classifieds.id');
                }
            }

            $classifieds->leftJoin('users', function($join) {
                $join->on('classifieds.user_id', '=', 'users.id');
            })->select(['classifieds.*', DB::raw("CONCAT(users.last_name,' ',users.first_name) as fullname"), DB::raw('0 AS reported')]);

            return Datatables::of($classifieds->get())
                ->edit_column('fullname', function($classified){
                    return Form::Author($classified->user_id);
                })
                ->edit_column('paid',function($classified){
                    return Form::Published($classified->paid);
                })
                ->edit_column('published',function($classified){
                    //return Form::Published($classified->published);
                    return Form::Published($classified->isActive());
                })
                ->edit_column('active',function($classified){
                    return Form::Published($classified->active);
                })
                ->edit_column('reported',function($classified){
                    $classified = Classified::findOrFail($classified->id);
                    return Form::Published($classified->isReported());
                })
                ->add_column('is_visible', function($classified) {
                    return Form::Published($classified->isActive());
                })
                ->edit_column('created_at', function($classified){
                    return Carbon::parse($classified->created_at)->format('m/d/Y');
                })
                ->add_column('actions', function($classified) {
                    $actions = '<a href="'. URL::to('admin/classified/' . $classified->id . '/edit' ) .'">
                                    <i class="livicon" data-name="edit" data-size="18" data-loop="true" data-c="#428BCA" data-hc="#428BCA" title="'.Lang::get('classified/table.update-classified').'"></i>
                                </a>
                                <a href="'. route('confirm-delete/classified', $classified->id) .'" data-toggle="modal" data-target="#delete_confirm">
                                    <i class="livicon" data-name="remove-alt" data-size="18" data-loop="true" data-c="#f56954" data-hc="#f56954" title="'.Lang::get('classified/table.delete-classified').'"></i>
                                </a>';

                    return $actions;
                })
                ->make(true);
        }
    }
}
