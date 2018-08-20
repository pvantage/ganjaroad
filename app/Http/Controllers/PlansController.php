<?php

namespace App\Http\Controllers;

use App\Plans;
use App\PlansCategory;
use App\ClassifiedCategory;
use App\NearmeCategory;
use App\AdsPositions;

use App\Http\Requests;
use App\Http\Requests\PlansRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Response;
use Sentinel;
use App\Helpers\Template;
use Datatables;
use DB;
use Request;

class PlansController extends WeedController
{

    private $plan_fields = array('title', 'slug', 'description', 'amount', 'priority', 'published');
    
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {

        $plans = Plans::all();

        return View('admin.plans.index', compact('plans'));
    }
    
    public function classifiedcategories()
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
                    
                    if($plan_id = Request::get('plan')) {
                        $plan = Plans::findOrFail($plan_id);
                        $categories = $plan->classifiedcategories();

                        if(in_array($category->id, $categories)) $state['selected'] = true;
                        else $state['selected'] = false;
                    }
                    return $state;
                })
                ->make(true);
        }
    }
    
    public function nearmecategories()
    {
        if(Request::ajax()) {
            $categories = NearmeCategory::get(['id', 'title', 'published'])->sortBy('id');

            return Datatables::of($categories)
                ->add_column('text', function($category){
                    return $category->title;

                })
                ->add_column('type', function($category){
                    $type = 'root';
                    if($category->published == 0) $type = 'notpublished';
                        
                    return $type;

                })
                ->add_column('state', function($category) {
                    $state = array();
                    
                    if($plan_id = Request::get('plan')) {
                        $plan = Plans::findOrFail($plan_id);
                        $categories = $plan->nearmecategories();

                        if(in_array($category->id, $categories)) $state['selected'] = true;
                        else $state['selected'] = false;
                    }
                    return $state;
                })
                ->make(true);
        }
    }
    
    public function adspositions()
    {
        if(Request::ajax()) {
            $positions = AdsPositions::get(['id', 'title', 'published'])->sortBy('position');

            return Datatables::of($positions)
                ->add_column('text', function($position){
                    return $position->title;

                })
                ->add_column('type', function($position){
                    $type = 'root';
                    if($position->published == 0) $type = 'notpublished';
                        
                    return $type;

                })
                ->add_column('state', function($position) {
                    $state = array();
                    
                    if($plan_id = Request::get('plan')) {
                        $plan = Plans::findOrFail($plan_id);
                        $positions = $plan->adspositions();

                        if(in_array($position->id, $positions)) $state['selected'] = true;
                        else $state['selected'] = false;
                    }
                    return $state;
                })
                ->make(true);
        }
    }

    public function create()
    {
        return view('admin.plans.create');
    }

    public function store(PlansRequest $request)
    {
        $plan = new Plans($request->only($this->plan_fields));
        $plan->user_id = Sentinel::getUser()->id;
        $plan->save();

        if ($plan->id) {
            if($request->get('classifieds_categories')) {
                $categories = $request->get('classifieds_categories');
                PlansCategory::createCategories($categories, 'classifieds', $plan->id);
            }
            if($request->get('nearme_categories')) {
                $categories = $request->get('nearme_categories');
                PlansCategory::createCategories($categories, 'nearme', $plan->id);
            }
            if($request->get('ads_positions')) {
                $categories = $request->get('ads_positions');
                PlansCategory::createCategories($categories, 'ads', $plan->id);
            }
            
            return redirect('admin/plans')->with('success', trans('plans/message.success.create'));
        } else {
            return redirect('admin/plans')->withInput()->with('error', trans('plans/message.error.create'));
        }

    }    

    public function edit(Plans $plan)
    {
        return view('admin.plans.edit', compact('plan'));
    }

    public function update(PlansRequest $request, Plans $plan)
    {
        if ($plan->update($request->only($this->plan_fields))) {
            if($categories = $request->get('classifieds_categories') || empty($request->get('classifieds_categories'))) {
                $categories = $request->get('classifieds_categories');
                PlansCategory::updateCategories($categories, 'classifieds', $plan->id);
            }
            if($categories = $request->get('nearme_categories') || empty($request->get('nearme_categories'))) {
                $categories = $request->get('nearme_categories');
                PlansCategory::updateCategories($categories, 'nearme', $plan->id);
            }
            if($request->get('ads_positions') || empty($request->get('ads_positions'))) {
                $categories = $request->get('ads_positions');
                PlansCategory::updateCategories($categories, 'ads', $plan->id);
            }
            
            return redirect('admin/plans')->with('success', trans('plans/message.success.update'));
        } else {
            return redirect('admin/plans')->withInput()->with('error', trans('plans/message.error.update'));
        }
    }

    public function getModalDelete(Plans $plan)
    {
        $model = 'plans';
        $confirm_route = $error = null;
        try {
            $confirm_route = route('delete/plan', ['id' => $plan->id]);
            return View('admin/layouts/modal_confirmation', compact('error', 'model', 'confirm_route'));
        } catch (GroupNotFoundException $e) {

            $error = trans('plans/message.error.delete', compact('id'));
            return View('admin/layouts/modal_confirmation', compact('error', 'model', 'confirm_route'));
        }
    }

    public function destroy(Plans $plan)
    {
        if ($plan->delete()) {
            return redirect('admin/plans')->with('success', trans('plans/message.success.delete'));
        } else {
            return redirect('admin/plans')->withInput()->with('error', trans('plans/message.error.delete'));
        }
    }
}
