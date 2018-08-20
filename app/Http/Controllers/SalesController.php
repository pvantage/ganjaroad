<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Response;
use Redirect;
use Request;
use Lang;
use Storage;
use Validator;
use File;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;
use DateTime;
use DB;
use URL;
use Form;

use Excel;
use Sentinel;
use PDF;
use Datatables;

use App\Helpers\Template;
use App\User;
use App\Claim;
use App\Payments;
use App\Coupons;

class SalesController extends WeedController
{
    public function salesRepsReport()
    {
		$users = Claim::getReportGrid()->get();

        return View('admin.sales.reps_sales', compact('users'));
    }
    
    public function salesUsersReport()
    {
        $filter_range = Input::get('filter_range');
        $filter_sdate = Input::get('date_from');
        $filter_edate = Input::get('date_to');
        $filter_type = Input::get('filter_type');
        $ranges = Template::getReporstFilterRanges();
        
        $users = User::getReportGrid($filter_type, $filter_range, $filter_sdate, $filter_edate)->get();        
        
        return View('admin.sales.users_sales', compact('users', 'ranges', 'filter_range', 'filter_user', 'filter_sdate', 'filter_edate', 'filter_type'));
    }
	    
	public function salesApproval()
    {
        $users = Claim::where('approved', '=', '0')->where('reviewed', '=', '0')->get();

        return View('admin.sales.approve', compact('users'));
    }
    
    public function getApproveModal(Claim $claim)
    {
        $model = 'sales';
        $confirm_route = $error = null;
        try {
            $confirm_route = route('sales/approve/claim', ['id' => $claim->id]);
            
            return View('admin/layouts/modal_confirmation', compact('error', 'model', 'confirm_route'));
        } catch (GroupNotFoundException $e) {
            $error = trans('sales/message.error.approve', compact('id'));
			
            return View('admin/layouts/modal_confirmation', compact('error', 'model', 'confirm_route'));
        }
    }
    
    public function getDisapproveModal(Claim $claim)
    {
        $model = 'sales';
        $confirm_route = $error = null;
        try {
            $confirm_route = route('sales/disapprove/claim', ['id' => $claim->id]);
            
			return View('admin/layouts/modal_confirmation2', compact('error', 'model', 'confirm_route'));
        } catch (GroupNotFoundException $e) {
            $error = trans('claim/message.error.claim', compact('id'));
			
            return View('admin/layouts/modal_confirmation2', compact('error', 'model', 'confirm_route'));
        }
    }

    public function approveClaim(Claim $claim)
    {
        try {
            $claim = Claim::where('id', '=', $claim->id)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return Redirect::route('sales.approve')->withInput()->with('error', trans('sales/message.error.approve'));
        }
        
        if ($claim->approved) {
            return Redirect::route('sales.approve')->with('success', trans('sales/message.success.already_approved'));
        } elseif($claim->reviewed) {
            return Redirect::route('sales.approve')->with('success', trans('sales/message.success.already_reviewed'));
        } else {
            $claim->approved = 1;
            $claim->reviewed = 1;
            $claim->save();
            
            return Redirect::route('sales.approve')->with('success', trans('sales/message.success.approved'));
        }
        
    }
    
    public function disapproveClaim(Claim $claim)
    {
        try {
            $claim = Claim::where('id', '=', $claim->id)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return Redirect::route('sales.approve')->withInput()->with('error', trans('sales/message.error.approve'));
        }
        
        $claim->reviewed = 1;
        $claim->approved = 0;
        $claim->save();
            
        return Redirect::route('sales.approve')->with('success', trans('sales/message.success.disapproved'));
    }
    
    public function repsInfo(User $user, $type = false)
    {
        $filter_user = Input::get('filter_user', 0);
        $filter_range = Input::get('filter_range');
        $filter_sdate = Input::get('date_from');
        $filter_edate = Input::get('date_to');
        
        $ranges = Template::getReporstFilterRanges();
        
        $claimed = Claim::where('admin_id', '=', $user->id)->where('approved', '=', '1')->lists('user_id')->toArray();
        $payments = $this->getDetailRepsPayment($user);        
        $users_ids = User::whereIn('id', $claimed)->lists('id')->toArray();
        
        $users = array();
        foreach($users_ids as $id) {
            $temp_user = Sentinel::findById($id);
            $users[$id] = $temp_user->getFullName();
        }
        
        asort($users);
        $users[0] = Lang::get('sales/title.choose_user');
        
        return View('admin.sales.info', compact('user', 'users', 'file', 'payments', 'ranges', 'filter_range', 'filter_user', 'filter_sdate', 'filter_edate'));
    }
    
    public function usersInfo(User $user, $type = false)
    {
        $filter_range = Input::get('filter_range');
        $filter_sdate = Input::get('date_from');
        $filter_edate = Input::get('date_to');
        $ranges = Template::getReporstFilterRanges();
        
        $transactions = $this->getDetailUsersPayment($user);
        
        return View('admin.sales.info_users', compact('user', 'file', 'transactions', 'ranges', 'filter_range', 'filter_sdate', 'filter_edate'));
    }
    
    public function printRepsDetailReport(User $user)
    {
        if($type = Request::get('type')) {
            $payments = $this->getDetailRepsPayment($user);
            $title = Lang::get('sales/title.user_export', ['id' => $user->id]);
            $file_name = $user->id.'_transactions';
            $inline_separator = '|';
            
            if($type == 'export') {
                $file_name .= '.csv';
                $data = array(); //$payments->select(['id', 'amount', 'transaction_id', 'paid', 'created_at'])->get()->toArray();
                $i = 0;
                foreach($payments as $payment) {
                    $data[$i] = array(
                        'id' => $payment->id,
                        'amount' => Template::getPaymentTotal($payment->id),
                        'transaction_id' => $payment->transaction_id,
                        'paid' => $payment->paid,
                        'created_at' => $payment->created_at,
                    );
                    
                    $output = '';
                    foreach($payment->items as $item) {
                        $item_data = array(
                            Lang::get('sales/table.ad_id') => $item->item_id,
                            Lang::get('sales/table.ad_name') => $item->getItemTitle(),
                            Lang::get('sales/table.ad_type') => $item->type,
                            Lang::get('sales/table.ad_cost') => Template::convertPrice($item->paid),
                        );
                         
                        $output = implode($inline_separator, array_map(
                            function ($v, $k) {
                                if(is_array($v)){
                                    return $k.'[]='.implode('&'.$k.'[]=', $v);
                                }else{
                                    return $k.'='.$v;
                                }
                            }, 
                            $item_data, 
                            array_keys($item_data)
                        ));
                    }
                    $data[$i]['items'] = $output;
                    ++$i;
                }
                $content = Excel::create($file_name, function($excel) use($data, $title) {
                    $excel->sheet($title, function($sheet) use($data) {
                        $sheet->fromArray($data);
                    });
                })->string('csv');
            } else {
                $file_name .= '.pdf';
                $print = true;
                $pdf = PDF::loadView('admin.sales.print_info', compact('payments', 'print'));
            
                $content = $pdf->download($file_name);
            }
            
            $file = Storage::disk('local')->put($file_name, $content);
            if($file) {
                $link = route('downloadfile', $file_name);
                $msg = Lang::get('sales/message.success.report_created', ['file' => $file_name, 'link' => $link]);
                
                return response()->json(['success' => true, 'msg' => $msg]);
            } else {
                return response()->json(['success' => false, 'msg' => Lang::get('sales/message.error.file_cant_be_created')]);
            }
        }
        
        return response()->json(['success' => false]);
    }
    
    public function printUsersDetailReport(User $user)
    {
        if($type = Request::get('type')) {
            $transactions = $this->getDetailUsersPayment($user);
            $title = Lang::get('sales/title.user_export', ['id' => $user->id]);
            $file_name = $user->id.'_transactions';
            $inline_separator = '|';
            
            if($type == 'export') {
                $file_name .= '.csv';
                $data = array();
                foreach($transactions as $transaction) {
                    foreach($transaction->items as $item) {
                        if($item_object = $item->getItem()) {
                            $title = $item_object->title;
                            $type = $item_object->getType();
                        } else {
                            $title = '';
                            $type = '';
                        }
                        $data[] = array(
                            'transaction_id' => $transaction->transaction_id,
                            'tranasction_type' => $transaction->tranasction_type,
                            'item_name' => $title,
                            'item_type' => $type,
                            'amount' => Template::convertPrice($transaction->amount),
                            'paid' => ($transaction->paid) ? 1 : 0,
                            'created_at' => $transaction->created_at,
                        );
                    }
                }

                $content = Excel::create($file_name, function($excel) use($data, $title) {
                    $excel->sheet($title, function($sheet) use($data) {
                        $sheet->fromArray($data);
                    });
                })->string('csv');
            } else {
                $file_name .= '.pdf';
                $print = true;
                $pdf = PDF::loadView('admin.sales.print_users_info', compact('transactions', 'user', 'print'));
            
                $content = $pdf->download($file_name);
            }
            
            $file = Storage::disk('local')->put($file_name, $content);
            if($file) {
                $link = route('downloadfile', $file_name);
                $msg = Lang::get('sales/message.success.report_created', ['file' => $file_name, 'link' => $link]);
                
                return response()->json(['success' => true, 'msg' => $msg]);
            } else {
                return response()->json(['success' => false, 'msg' => Lang::get('sales/message.error.file_cant_be_created')]);
            }
        }
        
        return response()->json(['success' => false]);
    }
    
    public function getDetailRepsPayment(User $user)
    {
        $filter_user = Input::get('filter_user', 0);
        $filter_sdate = Input::get('date_from');
        $filter_edate = Input::get('date_to');
        $range = Input::get('filter_range');

        $claimed = Claim::where('admin_id', '=', $user->id)->where('approved', '=', '1')->lists('user_id')->toArray();
        $payments = Payments::whereIn('user_id', $claimed);
        if($filter_user) {
            $payments->where('user_id', '=', $filter_user);
        }
        
        $now = Carbon::now();
        switch($range) {
            case('current_month'):
                $payments->where(DB::raw('MONTH(created_at)'), '=', $now->month)->where(DB::raw('YEAR(created_at)'), '=', $now->year); break;
            case('last_month'):
                $payments->where(DB::raw('MONTH(created_at)'), '=', $now->subMonth()->month)->where(DB::raw('YEAR(created_at)'), '=', $now->subMonth()->year); break;
            case('current_year'):
                $payments->where(DB::raw('YEAR(created_at)'), '=', $now->year); break;
            case('last_year'):
                $payments->where(DB::raw('YEAR(created_at)'), '=', $now->subYear()->year); break;
            case('custom'):
                if($filter_sdate) {
                    $filter_date = DateTime::createFromFormat(Template::getDisplayedDateTimeFormat(), $filter_sdate);
                    $payments->where('created_at', '>', $filter_date);
                }
                if($filter_edate) {
                    $filter_date = DateTime::createFromFormat(Template::getDisplayedDateTimeFormat(), $filter_edate);
                    $payments->where('created_at', '<', $filter_date);
                }
                break;
        }

        return $payments->get();
    }
    
    public function getDetailUsersPayment(User $user)
    {
        $now = Carbon::now();
        $filter_user = Input::get('filter_user', 0);
        $filter_sdate = Input::get('date_from');
        $filter_edate = Input::get('date_to');
        $range = Input::get('filter_range');
        
        $transactions = $user->payments()->where('paid', '=', 1);
        switch($range) {
            case('current_month'):
                $transactions->where(DB::raw('MONTH(created_at)'), '=', $now->month)->where(DB::raw('YEAR(created_at)'), '=', $now->year); break;
            case('last_month'):
                $transactions->where(DB::raw('MONTH(created_at)'), '=', $now->subMonth()->month)->where(DB::raw('YEAR(created_at)'), '=', $now->subMonth()->year); break;
            case('current_year'):
                $transactions->where(DB::raw('YEAR(created_at)'), '=', $now->year); break;
            case('last_year'):
                $transactions->where(DB::raw('YEAR(created_at)'), '=', $now->subYear()->year); break;
            case('custom'):
                if($filter_sdate) {
                    $filter_date = DateTime::createFromFormat(Template::getDisplayedDateTimeFormat(), $filter_sdate);
                    $transactions->where('created_at', '>', $filter_date);
                }
                if($filter_edate) {
                    $filter_date = DateTime::createFromFormat(Template::getDisplayedDateTimeFormat(), $filter_edate);
                    $transactions->where('created_at', '<', $filter_date);
                }
                break;
        }
        
        return $transactions->orderBy('created_at', 'desc')->get();
    }
    
    public function printRepsReport()
    {
        /*$rules = array(
            'ids' => 'required|array',
        );
        $validator = Validator::make(Request::all(), $rules);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'msg' => Lang::get('sales/message.error.ids_required')]);
        }*/
            
        if($type = Request::get('type')) {
            $ids = Request::get('ids');
            $users = Claim::getReportGrid();
            if($ids) $users->whereIn('admin_id', $ids);
            $file_name = 'reps_sales';
            
            if($type == 'export') {
                $users_array = $users->get();
                foreach($users_array as $user) {
                    $data[] = array(
                        'rep' => $user->admin->getFullName(),
                        'email' => $user->admin->email,
                        'location' => $user->admin->getFormatedAddress(),
                        'sales' => Template::convertPrice($user->admin->getSalesAmount())
                    );
                }
                
                $title = Lang::get('sales/title.users_export');
                $file_name .= '.csv';

                $content = Excel::create($file_name, function($excel) use($data, $title) {
                    $excel->sheet($title, function($sheet) use($data) {
                        $sheet->fromArray($data);
                    });
                })->string('csv');
            } else {
                $file_name .= '.pdf';
                $pdf = PDF::loadView('admin.sales.print_sales', ['users' => $users->get(), 'print' => true]);
            
                $content = $pdf->download($file_name);
            }
            
            $file = Storage::disk('local')->put($file_name, $content);
            if($file) {
                $link = route('downloadfile', $file_name);
                $msg = Lang::get('sales/message.success.report_created', ['file' => $file_name, 'link' => $link]);
                
                return response()->json(['success' => true, 'msg' => $msg]);
            } else {
                return response()->json(['success' => false, 'msg' => Lang::get('sales/message.error.file_cant_be_created')]);
            }
        }
        
        return response()->json(['success' => false]);
    }
    
    public function printUsersReport()
    {
        /*$rules = array(
            'ids' => 'required|array',
        );
        $validator = Validator::make(Request::all(), $rules);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'msg' => Lang::get('sales/message.error.ids_required')]);
        }*/
        
        $filter_range = Input::get('filter_range');
        $filter_sdate = Input::get('date_from');
        $filter_edate = Input::get('date_to');
        $filter_type = Input::get('filter_type');
        
        if($type = Request::get('type')) {
            $ids = Request::get('ids');
            $users = User::getReportGrid($filter_type, $filter_range, $filter_sdate, $filter_edate);
            if($ids) $users->whereIn('users.id', $ids);
            $file_name = 'users_sales';

            if($type == 'export') {
                $users_array = $users->get();
                foreach($users_array as $user) {
                    $data[] = array(
                        'user_id' => $user->id,
                        'user_name' => $user->getFullName(),
                        'email' => $user->email,
                        'location' => $user->getFormatedAddress(),
                        'banner_ads' => $user->ads->count(),
                        'classifieds' => $user->classifieds->count(),
                        'provider_ads' => $user->nearme->count(),
                        'spent' => Template::convertPrice($user->getAmountSpent()),
                        'last_payment_at' => ($user->payments()->count()) ? $user->payments()->latest()->first()->created_at : '',
                    );
                }
                
                $title = Lang::get('sales/title.users_export');
                $file_name .= '.csv';

                $content = Excel::create($file_name, function($excel) use($data, $title) {
                    $excel->sheet($title, function($sheet) use($data) {
                        $sheet->fromArray($data);
                    });
                })->string('csv');
            } else {
                $file_name .= '.pdf';
                $pdf = PDF::loadView('admin.sales.print_users_sales', ['users' => $users->get(), 'print' => true]);
            
                $content = $pdf->download($file_name);
            }
            
            $file = Storage::disk('local')->put($file_name, $content);
            if($file) {
                $link = route('downloadfile', $file_name);
                $msg = Lang::get('sales/message.success.report_created', ['file' => $file_name, 'link' => $link]);
                
                return response()->json(['success' => true, 'msg' => $msg]);
            } else {
                return response()->json(['success' => false, 'msg' => Lang::get('sales/message.error.file_cant_be_created')]);
            }
        }
        
        return response()->json(['success' => false]);
    }
    
    public function downloadReport($file_name)
    {
        $path = storage_path('app'.DIRECTORY_SEPARATOR.$file_name);
        if(File::exists($path)) {
            return response()->download($path, $file_name);
        } else {
            return redirect(route('dashboard'))->with('error', Lang::get('general.file_does_not_exist'));
        }
    }
    
    public function createCoupon()
    {        
        return view('admin.coupons.index.create');
    }
    
    public function storeCoupon(CouponsRequest $request)
    {
        $request = Template::replaceDates($request);
        
        $coupon = new Page($request->all());
        $coupon->author = Sentinel::getUser()->id;
        $coupon->save();

        if ($coupon->id) {
            return redirect('admin.coupons.index')->with('success', trans('coupons/message.success.create'));
        } else {
            return redirect('admin.coupons.index')->withInput()->with('error', trans('page/message.error.create'));
        }
    }
    
    public function coupons()
    {
		$coupons = Coupons::all();
        return View('admin.coupons.index', compact('coupons'));
    }
    
    public function couponsDataList()
    {
        if(Request::ajax()) {
            $coupons = Coupons::all();

            return Datatables::of($coupons)
                ->edit_column('discount', function($coupon){
                    return $coupon->getType();
                })
                ->edit_column('start_date', function($coupon){
                    return Carbon::parse($coupon->start_date)->format(Template::getDisplayedDateTimeFormat());
                })
                ->edit_column('end_date', function($coupon){
                    return Carbon::parse($coupon->start_date)->format(Template::getDisplayedDateTimeFormat());
                })
                ->edit_column('created_at', function($coupon){
                    return Carbon::parse($coupon->created_at)->diffForHumans();
                })
                ->add_column('actions', function($coupon) {
                    $actions = '<a href="'. URL::to('admin/sales/coupons/' . $coupon->id . '/edit' ) .'">
                                    <i class="livicon" data-name="edit" data-size="18" data-loop="true" data-c="#428BCA" data-hc="#428BCA" title="'.Lang::get('ads/table.update-ads').'"></i>
                                </a>
                                <a href="'. route('confirm-delete/coupons', $coupon->id) .'" data-toggle="modal" data-target="#delete_confirm">
                                    <i class="livicon" data-name="remove-alt" data-size="18" data-loop="true" data-c="#f56954" data-hc="#f56954" title="'.Lang::get('ads/table.delete-ads').'"></i>
                                </a>';

                    return $actions;
                })
                ->make(true);
        }
    }
    
    public function getCouponsModalDelete(Coupons $coupons)
    {
        $model = 'coupons';
        $confirm_route = $error = null;

        try {
            $confirm_route = route('delete/coupons', ['coupons' => $coupons->id]);

            return View('admin/layouts/modal_confirmation', compact('error', 'model', 'confirm_route'));
        } catch (GroupNotFoundException $e) {

            $error = trans('coupons/message.error.delete', compact('id'));
            return View('admin/layouts/modal_confirmation', compact('error', 'model', 'confirm_route'));
        }
    }
    
    public function destroyCoupon(Coupons $coupons)
    {
        if ($coupons->delete()) {
            return redirect('admin/sales/coupons')->with('success', trans('coupons/message.success.delete'));
        } else {
            return redirect('admin/sales/coupons')->withInput()->with('error', trans('coupons/message.error.delete'));
        }
    }
    
    public function usersDataList()
    {
        $filter_range = Input::get('filter_range');
        $filter_sdate = Input::get('date_from');
        $filter_edate = Input::get('date_to');
        $filter_type = Input::get('filter_type');
        
        $users = User::getReportGrid($filter_type, $filter_range, $filter_sdate, $filter_edate)->get();

        return Datatables::of($users)
            ->add_column('checkobx', function($user){
                return '<input type="checkbox" name="ids[]" value="'.$user->id.'" />';
            })
            ->add_column('author', function($user){
                return Form::Author($user->id);
            })
            ->add_column('location', function($user){
                return $user->getFormatedAddress();
            })
            ->add_column('amount', function($user){
                return Template::convertPrice($user->getAmountSpent());
            })
            ->add_column('ads_count', function($user){
                return ($user->ads_count) ? '<span class="green">Y</span>' : '<span class="black">N</span>';
            })
            ->add_column('classified_count', function($user){
                return ($user->classified_count) ? '<span class="green">Y</span>' : '<span class="black">N</span>';
            })
            ->add_column('nearme_count', function($user){
                return ($user->nearme_count) ? '<span class="green">Y</span>' : '<span class="black">N</span>';
            })
            ->add_column('actions', function($user) {
                $actions = '<a href="'.route('sales/users/info', $user->id).'">
								<i class="livicon" data-name="info" data-size="18" data-loop="true" data-c="#428BCA" data-hc="#428BCA" title="'.Lang::get('sales/table.sales_info').'"></i>
							</a>';

                return $actions;
            })
            ->add_column('latest_payment_created_at', function ($user) {
                $latestPayment = \DB::table('payments')->where('user_id', '=', $user->id)->latest()->first();

                if (!$latestPayment) {
                    return '-';
                }

                return $latestPayment->created_at;
            })
            ->make(true);
        
    }
}
