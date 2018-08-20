<?php

namespace App\Http\Controllers;

use App\Menu;
use App\Http\Requests;
use App\Http\Requests\MenuRequest;
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

class MenuController extends WeedController
{
    public function index()
    {
        $allcategories = Menu::all()->sortBy('position');
        $menus = CategoryTree::generateListTree($allcategories);
        $user = Sentinel::getUser();
        
        return View('admin.menu.index', compact('menus', 'user'));
    }
    
    public function create()
    {
        $menus = Menu::all()->sortBy('position');
        
        return view('admin.menu.create', compact('menus'));
    }
    
    public function store(MenuRequest $request)
    {
        $menu = new Menu($request->all());
		
        if ($menu->save()) {
            return redirect('admin/menu')->with('success', trans('menu/message.success.create'));
        } else {
            return redirect('admin/menu')->withInput()->with('error', trans('menu/message.error.create'));
        }
    }
    
    public function edit(Menu $menu)
    {
        $menus = Menu::all()->sortBy('position');
        
        return view('admin.menu.edit', compact('menu', 'menus'));
    }
    
    public function update(MenuRequest $request, Menu $menu)
    {
        if ($menu->update($request->all())) {
            return redirect('admin/menu')->with('success', trans('menu/message.success.update'));
        } else {
            return redirect('admin/menu')->withInput()->with('error', trans('menu/message.error.update'));
        }
    }
    
    public function data()
    {
        if(Request::ajax()) {
            $id = Request::get('id');
            if($id == '#') $parent = 0;
            else $parent = (int)$id;

            $menu = Menu::where('parent', $parent)->orderBy('position', 'asc')->get(['id', 'title']);

            return Datatables::of($menu)
                ->add_column('text', function($menu){
                    return $menu->title;

                })
                ->add_column('type', function($menu){
                    $types = array();
                    if($menu->id == 1) return 'root';

                    return 'default';

                })
                ->add_column('children', function($menu) {
                    if(Menu::where('parent', $menu->id)->count()) $count = true;
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
           
            $model = Menu::findOrFail($id);
            $model->parent = $parent;
            $model->position = $position;
            $model->save();
            
            Menu::updatePositions($parent);

            return Response::make($model);
        }
        
    }
    
    public function changePositions($parent, $new_position)
    {
        Menu::where('parent', '=', $parent)
                ->where('position', '>=', $new_position)
                ->increment('position', 1);
    }
        
    public function quickCreate()
    {
        if(Request::ajax() && $text = Request::get('text')) {
            $parent = Request::get('parent');
            $position = Request::get('position');

            $this->changePositions($parent, $position);
           
            $model = new Menu;
            $model->title = $text;
            $model->parent = $parent;
            $model->position = $position;
            $model->save();
            
            Menu::updatePositions($parent);

            return Response::make($model);
        }
        
    }
    
    public function quickRename()
    {
        if(Request::ajax() && $text = Request::get('text')) {
            $id = Request::get('id');
           
            $model = Menu::findOrFail($id);
            $model->title = $text;
            $model->save();

            return Response::make($model);
        }
        
    }
    
    public function quickRemove()
    {
        if(Request::ajax() && $id = Request::get('id')) {
            Menu::where('parent', '=', $id)->delete();
            
            $model = Menu::findOrFail($id);
            $model->delete();
            
            return Response::make($model);
        }
        
    }
	
	public function getModalDelete()
    {
		$model = 'menu';
		
        return View('admin/layouts/tree_modal_confirmation', compact('model'));
    }
    
    public function removeCategoriesByParent($menu, $classified_parent)
    {
        $menu_ids = $menu->lists('id')->toArray();
        $affectedRows = ClassifiedCategory::where('parent', '=', $classified_parent->id)->whereNotIn('schema_id', $menu_ids)->delete();
    
        if($affectedRows) {
            ClassifiedCategory::updatePositions($classified_parent->id);
            session(['warning' => trans('menu/message.success.removed')]);
        }
    }
    
    public function createIfNotExist($category, $classified_parent)
    {
        try {
            $classified_parent = ClassifiedCategory::where('parent', '=', $classified_parent->id)->where('schema_id', '=', $category->id)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            //category does not exist lets create it
            $classified_parent = $this->createMenuByParent($category, $classified_parent);
        }
        
        $menu = Menu::where('parent', '=', $category->id)->orderBy('position');
        if($menu->count()) {
            foreach($menu->get() as $category) {
                $this->createIfNotExist($category, $classified_parent);
            }
        }
    }
    
    public function createMenuByParent($menu, $classified_parent)
    {        
        $model = new ClassifiedCategory;
        $model->title = $menu->title;
        $model->parent = $classified_parent->id;
        $model->position = $menu->position;
        $model->schema_id = $menu->id;
        $model->published = 1;
        $model->save();
        
        if($model->id) {
            ClassifiedCategory::updatePositions($classified_parent->id);
            session(['success' => trans('menu/message.success.recreated')]);
        }
        
        return $model;
    }

}
