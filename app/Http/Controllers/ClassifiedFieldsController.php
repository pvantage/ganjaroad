<?php

namespace App\Http\Controllers;

use App\ClassifiedFields;
use App\Http\Requests;
use App\Http\Requests\ClassifiedFieldsRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ClassifiedFieldsController extends WeedController
{
    public function index()
    {
        $classifiedsfields = ClassifiedFields::all();

        return View('admin.classifiedfields.index', compact('classifiedsfields'));
    }

    public function create()
    {
        $classifiedfieldsvalues = ClassifiedFields::all();
        
        return view('admin.classifiedfields.create');
    }

    public function store(ClassifiedFieldsRequest $request)
    {
        $classifiedfields = new ClassifiedFields($request->all());

        if ($classifiedfields->save()) {
            return redirect('admin/classifiedfields')->with('success', trans('classifiedfields/message.success.create'));
        } else {
            return redirect('admin/classifiedfields')->withInput()->with('error', trans('classifiedfields/message.error.create'));
        }
    }

    public function edit(ClassifiedFields $classifiedfields)
    {
        return view('admin.classifiedfields.edit', compact('classifiedfields'));
    }

    public function update(ClassifiedFieldsRequest $request, ClassifiedFields $classifiedfields)
    {
        if ($classifiedfields->update($request->all())) {
            return redirect('admin/classifiedfields')->with('success', trans('classifiedfields/message.success.update'));
        } else {
            return redirect('admin/classifiedfields')->withInput()->with('error', trans('classifiedfields/message.error.update'));
        }
    }

    public function getModalDelete(ClassifiedFields $classifiedfields)
    {
        $model = 'classifiedfields';
        $confirm_route = $error = null;
        try {
            $confirm_route = route('delete/classifiedfields', ['id' => $classifiedfields->id]);
            return View('admin/layouts/modal_confirmation', compact('error', 'model', 'confirm_route'));
        } catch (GroupNotFoundException $e) {

            $error = trans('classifiedfields/message.error.delete', compact('id'));
            return View('admin/layouts/modal_confirmation', compact('error', 'model', 'confirm_route'));
        }
    }

    public function destroy(ClassifiedFields $classifiedfields)
    {
        if ($classifiedfields->delete()) {
            return redirect('admin/classifiedfields')->with('success', trans('classifiedfields/message.success.delete'));
        } else {
            return redirect('admin/classifiedfields')->withInput()->with('error', trans('classifiedfields/message.error.delete'));
        }
    }

}
