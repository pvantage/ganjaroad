<?php

namespace App\Http\Controllers;

use App\NearmeCategory;
use App\Http\Requests;
use App\Http\Requests\NearmeCategoryRequest;

use App\Helpers\Template;

class NearmeCategoryController extends WeedController
{

    public function index()
    {
        $nearmecategories = NearmeCategory::all();

        return View('admin.nearmecategory.index', compact('nearmecategories'));
    }

    public function create()
    {
        return view('admin.nearmecategory.create');
    }

    public function store(NearmeCategoryRequest $request)
    {
        $nearmecategory = new NearmeCategory($request->except('image', 'icon'));
        
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension() ?: 'png';

            $folderName = Template::getNearMeCategoryImageDir();
            $picture = str_random(10) . '.' . $extension;
            
            $destinationPath = public_path() . $folderName;
            $request->file('image')->move($destinationPath, $picture);
            
            $nearmecategory->image = $picture;
            $nearmecategory->save();
        }
        
        if ($request->hasFile('icon')) {
            $file = $request->file('icon');
            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension() ?: 'png';

            $folderName = Template::getNearMeCategoryImageDir();
            $picture = str_random(10) . '.' . $extension;
            
            $destinationPath = public_path() . $folderName;
            $request->file('icon')->move($destinationPath, $picture);
            
            $nearmecategory->icon = $picture;
            $nearmecategory->save();
        }

        if ($request->hasFile('image')) {
            
        }
        
        if ($nearmecategory->id) {
            return redirect('admin/nearmecategory')->with('success', trans('nearmecategory/message.success.create'));
        } else {
            return redirect('admin/nearmecategory')->withInput()->with('error', trans('nearmecategory/message.error.create'));
        }
    }

    public function edit(NearmeCategory $nearmecategory)
    {
        return view('admin.nearmecategory.edit', compact('nearmecategory'));
    }

    public function update(NearmeCategoryRequest $request, NearmeCategory $nearmecategory)
    {
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $folderName = Template::getNearMeCategoryImageDir();
            $picture = str_random(10) . '.' . $extension;
            $nearmecategory->image = $picture;
            
            $destinationPath = public_path() . $folderName;
            $request->file('image')->move($destinationPath, $picture);
            
            $nearmecategory->save();
        }

        if ($request->hasFile('icon')) {
            $file = $request->file('icon');
            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension() ?: 'png';

            $folderName = Template::getNearMeCategoryImageDir();
            $picture = str_random(10) . '.' . $extension;
            $nearmecategory->icon = $picture;
            
            $destinationPath = public_path() . $folderName;
            $request->file('icon')->move($destinationPath, $picture);
            
            $nearmecategory->save();
        }
       
        if ($nearmecategory->update($request->except('image', 'icon', '_method'))) {
            return redirect('admin/nearmecategory')->with('success', trans('nearmecategory/message.success.update'));
        } else {
            return redirect('admin/nearmecategory')->withInput()->with('error', trans('nearmecategory/message.error.update'));
        }
    }

    public function getModalDelete(NearmeCategory $nearmecategory)
    {
        $model = 'nearmecategory';
        $confirm_route = $error = null;
        try {
            $confirm_route = route('delete/nearmecategory', ['id' => $nearmecategory->id]);
            return View('admin/layouts/modal_confirmation', compact('error', 'model', 'confirm_route'));
        } catch (GroupNotFoundException $e) {

            $error = trans('nearmecategory/message.error.delete', compact('id'));
            return View('admin/layouts/modal_confirmation', compact('error', 'model', 'confirm_route'));
        }
    }


    public function destroy(NearmeCategory $nearmecategory)
    {
        if ($nearmecategory->delete()) {
            return redirect('admin/nearmecategory')->with('success', trans('nearmecategory/message.success.delete'));
        } else {
            return redirect('admin/nearmecategory')->withInput()->with('error', trans('nearmecategory/message.error.delete'));
        }
    }

}
