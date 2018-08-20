<?php

namespace App\Http\Controllers;

use App\AdCoupon;
use App\Http\Requests;
use App\Http\Requests\CouponsRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Redirect;
use Request;
use Sentinel;
use Lang;
use App\Helpers\Template;
use Carbon\Carbon;
use Datatables;
use URL;
use Form;

use App\Coupons;
use App\Cart;

class CouponsController extends WeedController
{   
    public function create()
    {
        $types = Coupons::getTypes();
        
        return view('admin.coupons.create', compact('types'));
    }
    
    public function store(CouponsRequest $request)
    {
        $request = Template::replaceDates($request);
        
        $coupon = new Coupons($request->all());
        $coupon->author = Sentinel::getUser()->id;
        $coupon->save();

        if ($coupon->id) {
            return redirect('admin/sales/coupons')->with('success', trans('coupons/message.success.create'));
        } else {
            return redirect('admin/sales/coupons')->withInput()->with('error', trans('page/message.error.create'));
        }
    }

    /**
     * Edit.
     *
     * @param Coupons $coupons
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Coupons $coupons)
    {
        $types = Coupons::getTypes();
        $adCoupon = AdCoupon::where('coupon_id', '=', $coupons->id)->get();

        foreach ($adCoupon as $value) {
            $value->ad = Template::loadItemByType($adCoupon[0]->ad_type, $adCoupon[0]->ad_id);
        }

        return view(
            'admin.coupons.edit',
            [
                'coupons' => $coupons,
                'types' => $types,
                'ad_coupon' => $adCoupon,
            ]
        );
    }

    public function update(CouponsRequest $request, Coupons $coupons)
    {
        $request = Template::replaceDates($request);
        
        if ($coupons->update($request->all())) {
            return redirect('admin/sales/coupons')->with('success', trans('coupons/message.success.update'));
        } else {
            return redirect('admin/sales/coupons')->withInput()->with('error', trans('coupons/message.error.update'));
        }
    }
    
    public function index()
    {
		$coupons = Coupons::all();
        
        return View('admin.coupons.index', compact('coupons'));
    }
    
    public function dataList()
    {
        if(Request::ajax()) {
            $coupons = Coupons::all();

            foreach ($coupons as $coupon) {
                $coupon->times_used = sprintf(
                    '%d (%d)',
                    $coupon->times_used,
                    AdCoupon::where('coupon_id', '=', $coupon->id)->count()
                );
            }

            return Datatables::of($coupons)
                ->edit_column('discount', function($coupon){
                    return $coupon->getType();
                })
                ->edit_column('published_from', function($coupon){
                    return Carbon::parse($coupon->published_from)->format(Template::getDisplayedDateTimeFormat());
                })
                ->edit_column('published_to', function($coupon){
                    return Carbon::parse($coupon->published_to)->format(Template::getDisplayedDateTimeFormat());
                })
                ->edit_column('active', function($coupon){
                    return Form::Published($coupon->active);
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
    
    public function getModalDelete(Coupons $coupons)
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
    
    public function destroy(Coupons $coupons)
    {
        if ($coupons->delete()) {
            return redirect('admin/sales/coupons')->with('success', trans('coupons/message.success.delete'));
        } else {
            return redirect('admin/sales/coupons')->withInput()->with('error', trans('coupons/message.error.delete'));
        }
    }
    
    public function submitCoupon(\Illuminate\Http\Request $request)
    {
        if($code = $request->get('coupon')) {
            $coupon = Coupons::isValid($code);
            if($coupon) {
                $applied = Cart::applyCoupon($coupon);
                if($applied) return Redirect::back()->with('success', trans('coupons/message.success.applied'));
                else return Redirect::back()->with('error', trans('coupons/message.error.applied_error'));
            } else return Redirect::back()->with('error', trans('coupons/message.error.invalid_code'));
        } else {
            return Redirect::back()->with('error', trans('coupons/message.error.code_required'));
        }
    }
}
