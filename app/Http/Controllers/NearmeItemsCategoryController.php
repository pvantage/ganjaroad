<?php

namespace App\Http\Controllers;

use App\NearmeItemsCategory;
use App\Http\Requests;
use App\Http\Requests\NearmeItemsCategoryRequest;

class NearmeItemsCategoryController extends WeedController
{

    public function index()
    {
        $nearmeitemscategories = NearmeItemsCategory::all();

        return View('admin.nearmeitemscategories.index', compact('nearmeitemscategories'));
    }

    public function create()
    {
        return view('admin.nearmeitemscategories.create');
    }

    public function store(NearmeItemsCategoryRequest $request)
    {
        $nearmeitemscategory = new NearmeItemsCategory($request->all());
        $nearmeitemscategory->save();

        if ($nearmeitemscategory->id) {
            return redirect('admin/nearmeitemscategory')->with('success', trans('nearmeitemscategory/message.success.create'));
        } else {
            return redirect('admin/nearmeitemscategory')->withInput()->with('error', trans('nearmeitemscategory/message.error.create'));
        }
    }

    public function edit(NearmeItemsCategory $nearmeitemscategory)
    {
        return view('admin.nearmeitemscategories.edit', compact('nearmeitemscategory'));
    }

    public function update(NearmeItemsCategoryRequest $request, NearmeItemsCategory $nearmeitemscategory)
    {
        if ($nearmeitemscategory->update($request->all())) {
            return redirect('admin/nearmeitemscategory')->with('success', trans('nearmeitemscategory/message.success.update'));
        } else {
            return redirect('admin/nearmeitemscategory')->withInput()->with('error', trans('nearmeitemscategory/message.error.update'));
        }
    }

    public function getModalDelete(NearmeItemsCategory $nearmeitemscategory)
    {
        $model = 'nearmeitemscategory';
        $confirm_route = $error = null;
        try {
            $confirm_route = route('delete/nearmeitemscategory', ['id' => $nearmeitemscategory->id]);
            return View('admin/layouts/modal_confirmation', compact('error', 'model', 'confirm_route'));
        } catch (GroupNotFoundException $e) {

            $error = trans('nearmeitemscategory/message.error.delete', compact('id'));
            return View('admin/layouts/modal_confirmation', compact('error', 'model', 'confirm_route'));
        }
    }


    public function destroy(NearmeItemsCategory $nearmeitemscategory)
    {
        if ($nearmeitemscategory->delete()) {
            return redirect('admin/nearmeitemscategory')->with('success', trans('nearmeitemscategory/message.success.delete'));
        } else {
            return redirect('admin/nearmeitemscategory')->withInput()->with('error', trans('nearmeitemscategory/message.error.delete'));
        }
    }

}
