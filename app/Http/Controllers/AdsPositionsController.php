<?php

namespace App\Http\Controllers;

use App\AdsPositions;
use App\Http\Requests;
use App\Http\Requests\AdsPositionsRequest;

class AdsPositionsController extends WeedController
{

    public function index()
    {
        $adspositions = AdsPositions::all();

        return View('admin.adspositions.index', compact('adspositions'));
    }


    public function create()
    {
        return view('admin.adspositions.create');
    }


    public function store(AdsPositionsRequest $request)
    {
        $adspositions = new AdsPositions($request->all());

        if ($adspositions->save()) {
            return redirect('admin/adspositions')->with('success', trans('adspositions/message.success.create'));
        } else {
            return redirect('admin/adspositions')->withInput()->with('error', trans('adspositions/message.error.create'));
        }
    }


    public function edit(AdsPositions $adspositions)
    {
        return view('admin.adspositions.edit', compact('adspositions'));
    }


    public function update(AdsPositionsRequest $request, AdsPositions $adspositions)
    {
        if ($adspositions->update($request->all())) {
            return redirect('admin/adspositions')->with('success', trans('adspositions/message.success.update'));
        } else {
            return redirect('admin/adspositions')->withInput()->with('error', trans('adspositions/message.error.update'));
        }
    }


    public function getModalDelete(AdsPositions $adspositions)
    {
        $model = 'adspositions';
        $confirm_route = $error = null;
        try {
            $confirm_route = route('delete/adspositions', ['id' => $adspositions->id]);
            return View('admin/layouts/modal_confirmation', compact('error', 'model', 'confirm_route'));
        } catch (GroupNotFoundException $e) {

            $error = trans('adspositions/message.error.delete', compact('id'));
            return View('admin/layouts/modal_confirmation', compact('error', 'model', 'confirm_route'));
        }
    }


    public function destroy(AdsPositions $adspositions)
    {
        if ($adspositions->delete()) {
            return redirect('admin/adspositions')->with('success', trans('adspositions/message.success.delete'));
        } else {
            return redirect('admin/adspositions')->withInput()->with('error', trans('adspositions/message.error.delete'));
        }
    }

}
