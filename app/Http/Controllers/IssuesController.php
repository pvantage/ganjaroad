<?php

namespace App\Http\Controllers;

use App\Issues;
use App\Http\Requests;
use App\Http\IssuesRequests;
use App\Helpers\CacheHelper;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Response;
use Redirect;
use Sentinel;
use DB;

class IssuesController extends WeedController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $issues = Issues::getAllIssuedItems()->get();

        return View('admin.issues.index', compact('issues'));
    }
	
	public function getModalConfirm($item_id)
    {
        $model = 'issues';
        $confirm_route = $error = null;
		
        try {
            $confirm_route = route('approve/issues', ['id' => $item_id]);
            return View('admin/layouts/modal_confirmation', compact('error', 'model', 'confirm_route'));
        } catch (GroupNotFoundException $e) {

            $error = trans('reported/message.error.approve', compact('id'));
            return View('admin/layouts/modal_confirmation', compact('error', 'model', 'confirm_route'));
        }
    }
    
    public function approve($item_id)
    {
        $result = DB::table('issues')
            ->where('item_id', $item_id)
            ->update(['reviewed' => 1]);

        if ($result) {
            CacheHelper::clearAllCache();
            return Redirect::back()->with('success', trans('issues/message.success.approve'));
        } else {
            return Redirect::back()->withInput()->with('error', trans('issues/message.error.approve'));
        }
    }
}
