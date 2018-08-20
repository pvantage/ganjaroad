<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Requests\ClaimRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Response;
use Sentinel;
use Redirect;
use URL;

use App\User;
use App\Claim;

class ClaimController extends WeedController
{

	public function getModalClaim(User $user)
    {
        $model = 'claim';
        $confirm_route = $error = null;
        try {
            $confirm_route = route('claim.user', ['id' => $user->id]);
            return View('admin/layouts/modal_confirmation', compact('error', 'model', 'confirm_route'));
        } catch (GroupNotFoundException $e) {
            $error = trans('claim/message.error.claim', compact('id'));
			
            return View('admin/layouts/modal_confirmation', compact('error', 'model', 'confirm_route'));
        }
    }
	
    public function claimUser(User $user)
    {
		$url = URL::to('admin/users');
		if($user->canBeClaimed()) {
			$claim = new Claim();
			$claim->user_id = $user->id;
			$claim->admin_id = Sentinel::getUser()->id;
			
			if ($claim->save()) {
				return Redirect::to($url)->with('success', trans('claim/message.success.save'));
			} else {
				return Redirect::to($url)->withInput()->with('error', trans('claim/message.error.save'));
			}
		}
		
		return Redirect::to($url)->withInput()->with('error', trans('claim/message.error.cheat'));
    }
	
	public function getModalRemoveClaim(User $user)
    {
        $model = 'claim';
        $confirm_route = $error = null;
        try {
            $confirm_route = route('unclaim.user', ['id' => $user->id]);
            
			return View('admin/layouts/modal_confirmation2', compact('error', 'model', 'confirm_route'));
        } catch (GroupNotFoundException $e) {
            $error = trans('claim/message.error.claim', compact('id'));
			
            return View('admin/layouts/modal_confirmation2', compact('error', 'model', 'confirm_route'));
        }
    }
	
	public function unClaimUser(User $user)
    {
		$current_user = Sentinel::getUser();
		$url = URL::to('admin/users');

        if($user->claimedByCurrentUser() || $current_user->hasAccess(['unclaim'])) {
            if (Claim::where('user_id', '=', $user->id)->update(['approved' => 0, 'reviewed' => 1])) {
                return Redirect::to($url)->with('success', trans('claim/message.success.delete'));
            } else {
                return Redirect::to($url)->withInput()->with('error', trans('claim/message.error.delete'));
            }
		}
		
		return Redirect::to($url)->withInput()->with('error', trans('claim/message.error.cheat'));
    }
}
