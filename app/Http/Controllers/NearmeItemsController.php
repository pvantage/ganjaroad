<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Requests\NearmeItemsRequest;

use App\Helpers\Template;

class NearmeItemsController extends WeedController
{

    public function index()
    {
        $nearmecategories = NearmeItmes::all();

        return View('admin.nearmecategory.index', compact('nearmecategories'));
    }

    public function create()
    {
        return view('admin.nearmecategory.create');
    }

    public function store(NearmeCategoryRequest $request)
    {
        $nearmecategory = new NearmeCategory($request->except('image'));
        
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension() ?: 'png';

            $folderName = Template::getNearMeCategoryImageDir();
            $picture = str_random(10) . '.' . $extension;
            $nearmecategory->image = $picture;
        }

        $nearmecategory->save();

        if ($request->hasFile('image')) {
            $destinationPath = public_path() . $folderName;
            $request->file('image')->move($destinationPath, $picture);
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
        }
        
        if ($request->hasFile('image')) {
            $destinationPath = public_path() . $folderName;
            $request->file('image')->move($destinationPath, $picture);
        }
       
        if ($nearmecategory->update($request->except('image', '_method'))) {
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
