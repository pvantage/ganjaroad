<?php

namespace App\Http\Controllers;

use App\Classified;
use App\ClassifiedCategory;
use App\ClassifiedSchema;
use App\ClassifiedCategoryValues;
use App\TierPrices;
use App\Http\Requests;
use App\Http\Requests\ClassifiedCategoryRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Helpers\CategoryTree;
use Datatables;
use DB;
use Input;
use Request;
use Response;
use Redirect;
use Sentinel;
use View;

use App\Helpers\Template;
use Geocode;

class ClassifiedCategoryController extends WeedController
{
    private $quick_create_state = 1;

    public function changeCategoryCountry()
    {

        $country = Request::query('country');
        if($country) {
            $countries = Template::getCountriesList(false);
            if(array_key_exists($country, $countries)) {
                session(['classifieds_location' => $country]);
                return redirect('classifieds')->with('success', trans('front/general.classifieds_location_cahnged'));
            }
        }

        return redirect('classifieds')->withInput()->with('error', trans('front/general.classifieds_location_cahnge_fail'));

    }

    public function getIndexFrontend()
    {
        /*$country = session('classifieds_location');

        if(!$country) {
            $location = Template::getCurrentLocation();
            $country = $location['iso_code'];
        }

        try {
            $category = ClassifiedCategory::where('country', 'LIKE', $country)->where('published', '=', 1)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            $category = ClassifiedCategory::where('country', 'LIKE', 'US')->firstOrFail(); //default
        }*/

        $location = Template::getCurrentLocation();
        try {
            $result = ClassifiedCategory::selectRaw(
                DB::raw('*, ( 3959 * acos( cos( radians(' . $location['lat'] . ') ) * cos( radians( lattitude ) ) * cos( radians( longitude ) - radians(' . $location['lon'] . ') ) + sin( radians(' . $location['lat'] .') ) * sin( radians(lattitude) ) ) ) AS distance')
            )->where('published', '=', 1)->groupBy('id')->orderBy('distance')->firstOrFail();

            $result_id = $result->parent;
        } catch (ModelNotFoundException $e) {
            $result_id = 0;
        }

        $categories = ClassifiedCategory::getChildrens($result_id);

        return View('frontend.classifieds.category', compact('categories'));
    }

    public function getFrontendCategory($category = '')
    {
        if($category == '') return redirect('classifieds');

        try {
            $category = ClassifiedCategory::where('slug', 'LIKE', $category)->where('published', '=', 1)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return redirect('classifieds');
        }

        if(ClassifiedCategory::haveChildrens($category->id)) {
            $categories = ClassifiedCategory::getChildrens($category->id);
            return View('frontend.classifieds.category', compact('categories', 'category'));
        } else {
            $limit = (int)Template::getSetting('listview_classifieds_limit');

            $classifieds = Classified::getActive()->whereHas('categories', function($query) use ($category){
                $query->where('category_id', '=', $category->id);
            })->orderBy('last_updated', 'DESC')->orderBy('updated_at', 'DESC')->paginate($limit);

            session(['last_classifieds_category' => $category->id]);
            return View('frontend.classifieds.items', compact('classifieds', 'category'));
        }

    }

    public function index()
    {
        $allcategories = ClassifiedCategory::all()->sortBy('position');
        $classifiedscategories = CategoryTree::generateListTree($allcategories);
        $user = Sentinel::getUser();

        return View('admin.classifiedcategory.index', compact('classifiedscategories', 'user'));
    }

    public function create()
    {
		$classifiedscategories = ClassifiedCategory::all()->sortBy('position');

        return view('admin.classifiedcategory.create')->with('classifiedscategories', $classifiedscategories);

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

    public function move()
    {
        if(Request::ajax()) {
            $id = Request::get('id');
            $parent = Request::get('parent');
            $position = Request::get('position');

            $this->changePositions($parent, $position);

            $model = ClassifiedCategory::findOrFail($id);
            $model->parent = $parent;
            $model->position = $position;
            $model->save();

            ClassifiedCategory::updatePositions($parent);

            return Response::make($model);
        }

    }

    public function getChildrensForSelect()
    {
        if(Request::ajax()) {
            $parent = Request::get('parent');
            $categories = ClassifiedCategory::where('published', '=', 1)->where('parent', $parent)->orderBy('position', 'asc')->get(['id', 'title']);

            return Datatables::of($categories)->make(true);
        }
    }
    public function canHaveMuliticity()
    {
        $result = array();
        $last_category = Request::get('category');

        if(Request::ajax() && $last_category) {
            $selected_category = ClassifiedCategory::find($last_category);
            $max = $selected_category->getCategoryMaxTier();

            if($max) {
                $result['success'] = 1;
                $result['max'] = $max;
                $result['tier'] = $selected_category->getCategoryTierPricing();
                //temporary math for tier pricing every 2 weeks, because of hardcoding it in the form
                //$tiers = $selected_category->getCategoryTierPricing();
                //$tiers->map(function($tier) {
                    //compute price for every 2 weeks
                    //$price = ($tier['price']/26);
                    //$tier['formated_price'] = Template::convertPrice($price);
                    //return $tier;
                //});
                //$result['tier'] = $tiers;
                //endtemporary
            }
        }

        return Response::make($result);
    }

    public function getMulticityDropdown()
    {
        $result = array();
        if(Request::ajax()) {
            $selected = Request::get('selected', array());
            $selected_main = Request::get('selected_main', array());
            $last_category = Request::get('category');
            $selected_category = ClassifiedCategory::find($last_category);

            if($selected_category) {
                $result['success'] = 1;
                if(Template::isAdminRoute()) {
                    $max = 10000;
                } else {
                    $max = $selected_category->getCategoryMaxTier();
                }
                $result['max'] = $max;
                if($max) {
                    $skip = array_merge($selected, $selected_main);
                    $result['cities'] = Template::getClassifiedCitiesList($selected_category->id, $skip, false, false);
                }
            }
        }

        return Response::make($result);
    }

    public function changePositions($parent, $new_position)
    {
        ClassifiedCategory::where('parent', '=', $parent)
                ->where('position', '>=', $new_position)
                ->increment('position', 1);
    }

    public function quickcreate()
    {
        if(Request::ajax() && $text = Request::get('text')) {
            $parent = Request::get('parent');
            $position = Request::get('position');

            $this->changePositions($parent, $position);

            $model = new ClassifiedCategory;
            $model->title = $text;
            $model->parent = $parent;
            $model->position = $position;
            $model->published = $this->quick_create_state;
            $model->save();

            ClassifiedCategory::updatePositions($parent);

            return Response::make($model);
        }

    }

    public function quickrename()
    {
        if(Request::ajax() && $text = Request::get('text')) {
            $id = Request::get('id');

            $model = ClassifiedCategory::findOrFail($id);
            $model->title = $text;
            $model->save();

            return Response::make($model);
        }

    }

    public function quickremove()
    {
        if(Request::ajax() && $id = Request::get('id')) {
            ClassifiedCategory::where('parent', '=', $id)->delete();

            $model = ClassifiedCategory::findOrFail($id);
            $model->delete();

            return Response::make($model);
        }

    }

    public function store(ClassifiedCategoryRequest $request)
    {
        if($request->get('home') && $request->get('address')) {
            $response = Geocode::make()->address($request->get('address'));

            if ($response) {
               $request->request->add(['lattitude' => $response->latitude()]);
               $request->request->add(['longitude' => $response->longitude()]);
            } else {
                return redirect('admin/classifiedcategory')->withInput()->with('error', trans('classifiedcategory/message.error.address'));
            }
        }

        $classifiedcategory = new ClassifiedCategory($request->except('use_schema'));
		$classifiedcategory->schema_id = 1;
        $classifiedcategory->save();

        if ($classifiedcategory->save()) {
			if($request->get('use_schema')) {
				$this->createCategoriesFromSchema($classifiedcategory->id);
			}
            return redirect('admin/classifiedcategory')->with('success', trans('classifiedcategory/message.success.create'));
        } else {
            return redirect('admin/classifiedcategory')->withInput()->with('error', trans('classifiedcategory/message.error.create'));
        }
    }

	public function createCategoriesFromSchema($root_id, $schema_parent = 0)
	{
		$schema = ClassifiedSchema::where('parent', $schema_parent)->orderBy('position')->get();

		foreach($schema as $category) {
            if($category->parent == 0) $this->createCategoriesFromSchema($root_id, $category->id); //do not copy root element
            else {
                $model = new ClassifiedCategory;
                $model->title = $category->title;
                $model->parent = $root_id;
                $model->schema_id = $category->id;
                $model->position = $category->position;
                $model->published = $this->quick_create_state;
                $model->amount = $category->amount;
                $model->save();

                $this->createTierPricesFromSchema($model->id, $category->id);
                $this->createCategoriesFromSchema($model->id, $category->id);
            }
		}
	}

    public function createTierPricesFromSchema($category_id, $schema_id)
    {
        $tiers = TierPrices::where('category_id', '=', $schema_id)->where('type', '=', 'schema');
        if($tiers->count()) {
            TierPrices::insertClassifiedsTierPrices($category_id, 'classifieds', $tiers->get()->toArray());
        }
    }

    public function edit(ClassifiedCategory $classifiedcategory)
    {
        $classifiedscategories = ClassifiedCategory::all()->sortBy('position');

        return view('admin.classifiedcategory.edit', compact('classifiedcategory'))->with('classifiedscategories', $classifiedscategories);
    }

    public function update(ClassifiedCategoryRequest $request, ClassifiedCategory $classifiedcategory)
    {
        if($request->get('home') && $request->get('address')) {
            $response = Geocode::make()->address($request->get('address'));

            if ($response) {
               $request->request->add(['lattitude' => $response->latitude()]);
               $request->request->add(['longitude' => $response->longitude()]);
            } else {
                return redirect('admin/classifiedcategory')->withInput()->with('error', trans('classifiedcategory/message.error.address'));
            }
        }

        if ($classifiedcategory->update($request->all())) {
            return redirect('admin/classifiedcategory')->with('success', trans('classifiedcategory/message.success.update'));
        } else {
            return redirect('admin/classifiedcategory')->withInput()->with('error', trans('classifiedcategory/message.error.update'));
        }
    }

    public function getModalDelete()
    {
		$model = 'classifiedcategory';

        return View('admin/layouts/tree_modal_confirmation', compact('model'));
    }

    public function destroy(ClassifiedCategory $classifiedcategory)
    {
        if ($classifiedcategory->delete()) {
            return redirect('admin/classifiedcategory')->with('success', trans('classifiedcategory/message.success.delete'));
        } else {
            return redirect('admin/classifiedcategory')->withInput()->with('error', trans('classifiedcategory/message.error.delete'));
        }
    }

}
