<?php

namespace App\Http\Controllers;

use App\ClassifiedSchema;
use App\TierPrices;
use App\Http\Requests;
use App\Http\Requests\ClassifiedSchemaRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Helpers\CategoryTree;
use Datatables;
use DB;
use Input;
use Request;
use Response;
use Sentinel;
use Redirect;
use Validator;

use App\ClassifiedCategory;

class ClassifiedSchemaController extends WeedController
{
    public function index()
    {
        $allcategories = ClassifiedSchema::all()->sortBy('position');
        $classifiedscategories = CategoryTree::generateListTree($allcategories);
        $user = Sentinel::getUser();
        
        return View('admin.classifiedschema.index', compact('classifiedscategories', 'user'));
    }
    
    public function create()
    {		
        return view('admin.classifiedschema.create');
    }
    
    public function store(ClassifiedSchemaRequest $request)
    {
        $classifiedschema = new ClassifiedSchema($request->only('title', 'amount'));
		
        if ($classifiedschema->save()) {
            return redirect('admin/classifiedschema')->with('success', trans('classifiedschema/message.success.create'));
        } else {
            return redirect('admin/classifiedschema')->withInput()->with('error', trans('classifiedschema/message.error.create'));
        }
    }
    
    public function edit(ClassifiedSchema $classifiedschema)
    {		
        return view('admin.classifiedschema.edit', compact('classifiedschema'));
    }
    
    public function update(ClassifiedSchemaRequest $request, ClassifiedSchema $classifiedschema)
    {
        if ($classifiedschema->update($request->only('title', 'amount'))) {
            return redirect('admin/classifiedschema')->with('success', trans('classifiedschema/message.success.update'));
        } else {
            return redirect('admin/classifiedschema')->withInput()->with('error', trans('classifiedschema/message.error.update'));
        }
    }
    
    public function data()
    {
        if(Request::ajax()) {
            $id = Request::get('id');
            if($id == '#') $parent = 0;
            else $parent = (int)$id;

            $schema = ClassifiedSchema::where('parent', $parent)->orderBy('position', 'asc')->get(['id', 'title']);

            return Datatables::of($schema)
                ->add_column('text', function($schema){
                    return $schema->title;

                })
                ->add_column('type', function($schema){
                    $types = array();
                    if($schema->id == 1) return 'root';

                    return 'default';

                })
                ->add_column('children', function($schema) {
                    if(ClassifiedSchema::where('parent', $schema->id)->count()) $count = true;
                    else $count = false;
                    return $count;
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
           
            $model = ClassifiedSchema::findOrFail($id);
            $model->parent = $parent;
            $model->position = $position;
            $model->save();
            
            ClassifiedSchema::updatePositions($parent);

            return Response::make($model);
        }
        
    }
    
    public function changePositions($parent, $new_position)
    {
        ClassifiedSchema::where('parent', '=', $parent)
                ->where('position', '>=', $new_position)
                ->increment('position', 1);
    }
        
    public function quickCreate()
    {
        if(Request::ajax() && $text = Request::get('text')) {
            $parent = Request::get('parent');
            $position = Request::get('position');

            $this->changePositions($parent, $position);
           
            $model = new ClassifiedSchema;
            $model->title = $text;
            $model->parent = $parent;
            $model->position = $position;
            $model->save();
            
            ClassifiedSchema::updatePositions($parent);

            return Response::make($model);
        }
        
    }
    
    public function quickRename()
    {
        if(Request::ajax() && $text = Request::get('text')) {
            $id = Request::get('id');
           
            $model = ClassifiedSchema::findOrFail($id);
            $model->title = $text;
            $model->save();

            return Response::make($model);
        }
        
    }
    
    public function quickRemove()
    {
        if(Request::ajax() && $id = Request::get('id')) {
            ClassifiedSchema::where('parent', '=', $id)->delete();
            
            $model = ClassifiedSchema::findOrFail($id);
            $model->delete();
            
            return Response::make($model);
        }
        
    }
	
	public function getModalDelete()
    {
		$model = 'classifiedschema';
		
        return View('admin/layouts/tree_modal_confirmation', compact('model'));
    }
    
    public function recreateCategories()
    {
        //$allcategories = ClassifiedCategory::all()->sortBy('position');
        //$classifiedscategories = CategoryTree::generateListTree($allcategories);
        $classifiedscategories = ClassifiedCategory::where('schema_id', '=', 1)->orderBy('position')->lists('title', 'id');
        
        return View('admin.classifiedschema.recreate', compact('classifiedscategories'));
    }
    
     
    public function postRecreateCategories()
    {
        $request = Request();
        $category_id = $request->get('category_id');
        $remove = $request->get('remove');
        $recreate_price = $request->get('price');
        
        $options = $request->except('_token');
        
        //recreate all
        if($category_id == '') {
            $schemas = ClassifiedSchema::where('parent', '=', 1)->orderBy('position')->get();
            $classified_parents = ClassifiedCategory::where('schema_id', '=', 1)->get();
            
            foreach($schemas as $schema) {
                foreach($classified_parents as $classified_parent) {
                    $this->createIfNotExist($schema, $classified_parent, $options);

                    if($remove) {
                        $this->removeNotSchemaCategories($schema, $classified_parent);
                    }
                }
            }
            
        } else {
            $rules = array(
                'category_id' => 'required|exists:classified_categories,id',
            );

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return Redirect::back()->withInput()->withErrors($validator);
            }

            $classified_parent = ClassifiedCategory::where('id', '=', $category_id)->firstOrFail();

            try {
                $parent_schema = ClassifiedSchema::where('id', '=', $classified_parent->schema_id)->firstOrFail();
            } catch (ModelNotFoundException $e) {
                return Redirect::back()->withInput()->with('error', trans('classifiedschema/message.error.recreate'));
            }
        
        
            $schema = ClassifiedSchema::where('parent', '=', $parent_schema->id)->orderBy('position');
            if($schema->count()) {
                $schema_categories = $schema->get();
                foreach($schema_categories as $category) {
                    $this->createIfNotExist($category, $classified_parent, $options);
                    
                    if($remove) {
                        $this->removeNotSchemaCategories($category, $classified_parent);
                    }
                }
            }
        }

        return Redirect::back();
    }
    
    public function updateCategoryBySchema($classified_category, $options = array())
    {
        if($classified_category->schema == 1) return false;
        
        $update = false;
        
        try {
            $schema_category = $classified_parent = ClassifiedSchema::where('id', '=', $classified_category->schema_id)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return false; //schema does not exist
        }

        if($classified_category->title != $schema_category->title) {
            $classified_category->title = $schema_category->title;
            //$classified_category->slug = NULL;
            
            $update = true;
        }
        
        if($classified_category->position != $schema_category->position) {
            $classified_category->position = $schema_category->position;
            
            $update = true;
        }
        
        if(isset($options['price']) && $options['price']) {
            if($classified_category->amount != $schema_category->amount) {
                $classified_category->amount = $schema_category->amount;
                
                session(['warning' => trans('classifiedschema/message.success.changed_pricing')]);
                
                $update = true;
            }
        }

        if(isset($options['tier']) && $options['tier']) {
            $tiers = TierPrices::where('category_id', '=', $schema_category->id)->where('type', '=', 'schema');
            if($tiers->count()) {
                TierPrices::insertClassifiedsTierPrices($classified_category->id, 'classifieds', $tiers->get()->toArray());
            }
        }
        
        if($update) $classified_category->save();
        
        return true;
    }
    
    public function removeNotSchemaCategories($category, $classified_parent)
    {
        try {
            $this->removeCategoriesByParent($category, $classified_parent);
            $classified_parent = ClassifiedCategory::where('parent', '=', $classified_parent->id)->where('schema_id', '=', $category->id)->firstOrFail();
        } catch (ModelNotFoundException $e) {

        }
        
        $schema = ClassifiedSchema::where('parent', '=', $category->id)->orderBy('position');
        if($schema->count()) {
            foreach($schema->get() as $category) {
                $this->removeNotSchemaCategories($category, $classified_parent);
            }
        }
        
    }
    
    public function removeCategoriesByParent($schema, $classified_parent)
    {
        $schema_ids = $schema->lists('id')->toArray();
        $affectedRows = ClassifiedCategory::where('parent', '=', $classified_parent->id)->whereNotIn('schema_id', $schema_ids)->delete();
    
        if($affectedRows) {
            ClassifiedCategory::updatePositions($classified_parent->id);
            session(['warning' => trans('classifiedschema/message.success.removed')]);
        }
    }
    
    public function createIfNotExist($category, $classified_parent, $options = array())
    {
        try {
            $classified_parent = ClassifiedCategory::where('parent', '=', $classified_parent->id)->where('schema_id', '=', $category->id)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            //category does not exist lets create it
            $classified_parent = $this->createCategoryBySchemaParent($category, $classified_parent);
        }
       
        $this->updateCategoryBySchema($classified_parent, $options);

        $schema = ClassifiedSchema::where('parent', '=', $category->id)->orderBy('position');
        if($schema->count()) {
            foreach($schema->get() as $category) {
                $this->createIfNotExist($category, $classified_parent, $options);
            }
        }
    }
    
    public function createCategoryBySchemaParent($schema, $classified_parent)
    {
        
        $model = new ClassifiedCategory;
        $model->title = $schema->title;
        $model->parent = $classified_parent->id;
        $model->position = $schema->position;
        $model->schema_id = $schema->id;
        $model->published = 1;
        $model->save();

        if($model->id) {
            ClassifiedCategory::updatePositions($classified_parent->id);
            session(['success' => trans('classifiedschema/message.success.recreated')]);
        }
        
        return $model;
    }

}
