<?php

namespace App\Http\Controllers;

use App\Page;
use App\Http\Requests;
use App\Http\Requests\PageRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Response;
use Sentinel;
/*use Datatables;
use Lang;*/

class PageController extends WeedController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {

        $pages = Page::all();

        return View('admin.page.index', compact('pages'));
    }


    public function create()
    {
        return view('admin.page.create');
    }

    public function store(PageRequest $request)
    {
        $page = new Page($request->except('image'));
        $page->user_id = Sentinel::getUser()->id;
        $page->save();

        if ($page->id) {
            return redirect('admin/page')->with('success', trans('page/message.success.create'));
        } else {
            return redirect('admin/page')->withInput()->with('error', trans('page/message.error.create'));
        }

    }

    public function show(Page $page)
    {
        return view('admin.page.show', compact('page'));
    }

    public function edit(Page $page)
    {
        return view('admin.page.edit', compact('page'));
    }

    public function update(PageRequest $request, Page $page)
    {
        if ($page->update($request->except('image'))) {
            return redirect('admin/page')->with('success', trans('page/message.success.update'));
        } else {
            return redirect('admin/page')->withInput()->with('error', trans('page/message.error.update'));
        }
    }

    public function getModalDelete(Page $page)
    {
        $model = 'page';
        $confirm_route = $error = null;
        try {
            $confirm_route = route('delete/page', ['id' => $page->id]);
            return View('admin/layouts/modal_confirmation', compact('error', 'model', 'confirm_route'));
        } catch (GroupNotFoundException $e) {

            $error = trans('page/message.error.delete', compact('id'));
            return View('admin/layouts/modal_confirmation', compact('error', 'model', 'confirm_route'));
        }
    }

    public function destroy(Page $page)
    {
        if ($page->delete()) {
            return redirect('admin/page')->with('success', trans('page/message.success.delete'));
        } else {
            return redirect('admin/page')->withInput()->with('error', trans('page/message.error.delete'));
        }
    }
}
