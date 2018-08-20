<?php

namespace App\Http\Controllers;

use App\AdsCompanies;
use App\Http\Requests;
use App\Http\Requests\AdsCompaniesRequest;

class AdsCompaniesController extends WeedController
{

    public function index()
    {
        // Grab all the blog category
        $adscompanies = AdsCompanies::all();
        // Show the page
        return View('admin.adscompanies.index', compact('adscompanies'));
    }

    public function create()
    {
        return view('admin.adscompanies.create');
    }


    public function store(AdsCompaniesRequest $request)
    {
        $adscompanies = new AdsCompanies($request->all());

        if ($adscompanies->save()) {
            return redirect('admin/adscompanies')->with('success', trans('adscompanies/message.success.create'));
        } else {
            return redirect('admin/adscompanies')->withInput()->with('error', trans('adscompanies/message.error.create'));
        }
    }


    public function edit(AdsCompanies $adscompanies)
    {
        return view('admin.adscompanies.edit', compact('adscompanies'));
    }


    public function update(AdsCompaniesRequest $request, AdsCompanies $adscompanies)
    {
        if ($adscompanies->update($request->all())) {
            return redirect('admin/adscompanies')->with('success', trans('adscompanies/message.success.update'));
        } else {
            return redirect('admin/adscompanies')->withInput()->with('error', trans('adscompanies/message.error.update'));
        }
    }


    public function getModalDelete(AdsCompanies $adscompanies)
    {
        $model = 'adscompanies';
        $confirm_route = $error = null;
        try {
            $confirm_route = route('delete/adscompanies', ['id' => $adscompanies->id]);
            return View('admin/layouts/modal_confirmation', compact('error', 'model', 'confirm_route'));
        } catch (GroupNotFoundException $e) {

            $error = trans('adscompanies/message.error.delete', compact('id'));
            return View('admin/layouts/modal_confirmation', compact('error', 'model', 'confirm_route'));
        }
    }

    public function destroy(AdsCompanies $adscompanies)
    {
        if ($adscompanies->delete()) {
            return redirect('admin/adscompanies')->with('success', trans('adscompanies/message.success.delete'));
        } else {
            return redirect('admin/adscompanies')->withInput()->with('error', trans('adscompanies/message.error.delete'));
        }
    }

}
