<?php

namespace App\Http\Controllers;

use View;
use Redirect;
use App\AdRenewOption;
use App\Http\Requests\AdRenewOptionRequest;

class AdRenewOptionController extends WeedController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $adrenewoptions = AdRenewOption::all();

        return View('admin.adrenewoption.index', compact('adrenewoptions'));
    }

    /**
     * @return View
     */
    public function create()
    {
        return View('admin.adrenewoption.create');
    }

    /**
     * @param AdRenewOptionRequest $request
     * @return Redirect
     */
    public function store(AdRenewOptionRequest $request)
    {
        $adrenewoption = new AdRenewOption($request->all());
        $adrenewoption->save();

        if($adrenewoption->id) {
            return redirect('admin/adrenewoption')->with('success', trans('adrenewoption/message.success.create'));
        }
        return redirect('admin/adrenewoption')->withInput()->with('error', trans('adrenewoption/message.error.create'));
    }

    /**
     * @param AdRenewOption $adrenewoption
     * @return View
     */
    public function show(AdRenewOption $adrenewoption)
    {
        return View('admin.adrenewoption.show', compact('adrenewoption'));
    }

    /**
     * @param AdRenewOption $adrenewoption
     * @return View
     */
    public function edit(AdRenewOption $adrenewoption)
    {
        return View('admin.adrenewoption.edit', compact('adrenewoption'));
    }

    /**
     * @param AdRenewOptionRequest $request
     * @param AdRenewOption $discount
     * @return Redirect
     */
    public function update(AdRenewOptionRequest $request, AdRenewOption $adrenewoption)
    {
        if($adrenewoption->update($request->all())) {
            return redirect('admin/adrenewoption')->with('success', trans('adrenewoption/message.success.update'));
        }
        return redirect('admin/adrenewoption')->withInput()->with('error', trans('adrenewoption/message.error.update'));
    }

    /**
     * @param AdRenewOption $adrenewoption
     * @return View
     */
    public function getModalDelete(AdRenewOption $adrenewoption)
    {
        $model = 'adrenewoption';
        $confirm_route = $error = null;
        try {
            $confirm_route = route('delete/adrenewoption', ['id' => $adrenewoption->id]);
            return View('admin/layouts/modal_confirmation', compact('error', 'model', 'confirm_route'));
        } catch (GroupNotFoundException $e) {
            $error = trans('page/message.error.delete', compact('id'));
            return View('admin/layouts/modal_confirmation', compact('error', 'model', 'confirm_route'));
        }
    }

    /**
     * @param AdRenewOption $adrenewoption
     * @return Redirect
     */
    public function destroy(AdRenewOption $adrenewoption)
    {
        if($discount->delete()) {
            return redirect('admin/adrenewoption')->with('success', trans('adrenewoption/message.success.delete'));
        }
        return redirect('admin/adrenewoption')->withInput()->with('error', trans('adrenewoption/message.error.delete'));
    }

}
