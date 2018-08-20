<?php

namespace App\Http\Controllers;

use App\ReportedItems;
use App\Http\Requests;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Response;
use Redirect;
use Sentinel;
use DB;

class ReportedItemsController extends WeedController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        
        $reported = ReportedItems::getAllReportedItems()->get();

        return View('admin.reporteditems.index', compact('reported'));
    }
	
	public function getModalConfirm($item_id)
    {
        $model = 'reporteditems';
        $confirm_route = $error = null;
		
        try {
            $confirm_route = route('approve/reporteditems', ['id' => $item_id]);
            return View('admin/layouts/modal_confirmation', compact('error', 'model', 'confirm_route'));
        } catch (GroupNotFoundException $e) {

            $error = trans('reported/message.error.approve', compact('id'));
            return View('admin/layouts/modal_confirmation', compact('error', 'model', 'confirm_route'));
        }
    }
    
    public function approve($item_id)
    {
        $result = DB::table('reported_items')
            ->where('item_id', $item_id)
            ->update(['status' => 1]);

        if ($result) {
            return Redirect::back()->with('success', trans('reporteditems/message.success.approve'));
        } else {
            return Redirect::back()->withInput()->with('error', trans('reporteditems/message.error.approve'));
        }
    }
}
