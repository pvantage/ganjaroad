<?php

namespace App\Helpers;

use View;
use Session;
use Carbon\Carbon;
use File;
use Lang;

use Sentinel;
use HelloSign\Client as HelloSignClient;
use HelloSign\EmbeddedSignatureRequest as EmbeddedSignatureRequest;
use HelloSign\TemplateSignatureRequest as TemplateSignatureRequest;

use App\Cart;
use App\HelloSign as HelloSignModel;
use App\HelloSignItems;

class HelloSign
{
    private $storage_dir = 'hellosign';
    
    public static function getStoreageDir($user_id)
	{
        $hellosign = new HelloSign();
        
        return storage_path() . DIRECTORY_SEPARATOR . $hellosign->storage_dir . DIRECTORY_SEPARATOR . $user_id;
    }
    
	public static function getForm()
	{
		$apikey = env('HELLOSIGN_API_KEY');
		$template_id = env('HELLOSIGN_TEMPLATE_ID');
		$client = new HelloSignClient(env('HELLOSIGN_API_KEY'));
		$request = new TemplateSignatureRequest();
		$request->setTemplateId(env('HELLOSIGN_TEMPLATE_ID'));

        if (env('HELLOSIGN_TEST_MODE') == 1) {
            $request->enableTestMode();
		}
        
        $user = Sentinel::getUser();
        Carbon::setLocale('en');
        $now = Carbon::now();
        if(Session::has('coupon_code')) {
            $code = Session::get('coupon_code');
        } else $code = '';
        
        $ad_name = Cart::getProjectName();
        $ads_pricing = Cart::getAdsPricing();
        $ads_recurring = Cart::getAdsRecurring();

        $request->setSigner('Client', $user->email, $user->getFullName());
        $request->setCustomFieldValue('agreement_date', $now->format('F d, Y'));
        $request->setCustomFieldValue('commencement_date', $now->format('F d, Y'));
		$request->setCustomFieldValue('client_name', $user->getFullName());
        $request->setCustomFieldValue('client_email', $user->email);
        //$request->setCustomFieldValue('client_name2', $user->getFullName());
		$request->setCustomFieldValue('ad_name', $ad_name);
		$request->setCustomFieldValue('ads_pricing', $ads_pricing);
		$request->setCustomFieldValue('ads_recurring', $ads_recurring);
		$request->setCustomFieldValue('coupon_code', $code);
		
        $cart = Cart::getCart();
        $session_signature = Session::get('signature_items', array());
        if($session_signature && isset($session_signature['signature'])) {
            $signature_request_id = $session_signature['signature']['request_id'];
            $signature_id = $session_signature['signature']['signature_id'];
            unset($session_signature['signature']);
        }
        
        if($cart == $session_signature) {
            
        } else {
            $embedded_request = new EmbeddedSignatureRequest($request, env('HELLOSIGN_CLIENT_KEY'));
            $response = $client->createEmbeddedSignatureRequest($embedded_request);
            $signature_request_id = $response->getId();
            $signatures = $response->getSignatures();
            $signature_id = $signatures[0]->getId();
            
            $signature_array = $cart;
            $signature_array['signature'] = array(
                'request_id' => $signature_request_id,
                'signature_id' => $signature_id,
                
            );
            Session::set('signature_items', $signature_array);
            
            $signature_request = $client->getSignatureRequest($signature_request_id);
            if ($signature_request->isComplete()) {
                if(Cart::getCartSignatureId() == $signature_id) {
                    HelloSign::setSessionSignature($signature_id);

                    return '';
                }
            } else HelloSign::forgetSessionSignature();
		}        
		 
		$response = $client->getEmbeddedSignUrl($signature_id);
		$sign_url = $response->getSignUrl();
		
        HelloSign::saveSignature($signature_id, $signature_request_id, 0, $user->id);
        
		return $sign_url;
	}
    
    public static function setSessionSignature($signature_id)
    {
        if($signature_id) {
            HelloSign::saveSignature($signature_id, false, 1);
            Session::set('signed_signature', $signature_id);
        }
    }
    
    public static function saveSignature($signature_id, $signature_request_id = false, $signed = 0, $user_id = 0)
    {
        return HelloSignModel::saveSignature($signature_id, $signature_request_id, $signed, $user_id);
    }
    
    public static function getSessionSignature()
    {
        if(Session::has('signed_signature')) {
            return Session::get('signed_signature');
        }
        
        return false;
    }
    
    public static function forgetSessionSignature()
    {
        Session::forget('signed_signature');
    }
    
    public static function saveFile($signature_request_id)
    {
        try {
            $signature = HelloSignModel::where('signature_request_id', '=', $signature_request_id)->firstOrFail();
            $user_id = $signature->user_id;

        } catch (ModelNotFoundException $e) {
            return false;
        }

        $client = new HelloSignClient(getenv('HELLOSIGN_API_KEY'));

        $destinationPath = HelloSign::getStoreageDir($user_id);
        if(!File::exists($destinationPath)) {
            File::makeDirectory($destinationPath, 0775, true);
        }

        $file_path = $destinationPath . DIRECTORY_SEPARATOR . $signature_request_id . '.pdf';
        $result = $client->getFiles($signature_request_id, $file_path, 'pdf');

        if($result) return true;
        
        return false;
    }
    
    public static function getSignatureFromDb($signature_id, $user_id = 0)
    {
        try {
			$signature = HelloSignModel::where('signature_id', '=', $signature_id);
            if($user_id) $signature->where('user_id', '=', $user_id);
            $signature->firstOrFail();
		} catch (ModelNotFoundException $e) {
            
        }
        
        return false;
    }
    
    public static function getAdminAgreementTxt($item_id, $item_type)
    {
        $agreements = HelloSignModel::whereHas('items', function($query) use($item_id, $item_type) {
                $query->where('item_id', '=', $item_id);
                $query->where('item_type', '=', $item_type);
            });
            
        $txt = ''; 
        if($agreements->count()) {
            $txt .= Lang::get('payments/title.user_created_following_agreements_for_this_ad');
            $txt .= '<ul class="agreements">';
            foreach($agreements->get() as $agreement) {
                if($agreement->signed) {
                    $txt .= '<li class="agreement-signed"><a href="'.HelloSign::generateAreementDownloadLink($agreement->id).'">'.$agreement->signature_id.'</a>';
                } else {
                    $txt .= '<li class="agreement-notsigned"><a href="javascript:void(0)">'.$agreement->signature_id.'</a>';
                }
            }
            $txt .= '</ul>';
        } else {
            $txt .= Lang::get('payments/title.user_have_not_signed_agreement_for_this_ad');
        }
        
        return $txt;
    }
    
    public static function generateAreementDownloadLink($agreement_id)
    {
        return route('downloadagreement', ['id' => $agreement_id]);
    }
    
    public static function downloadAgreement($agreement_id)
    {
        try {
            $agreement = HelloSignModel::where('id', '=', $agreement_id)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return redirect(route('dashboard'))->with('error', Lang::get('general.file_does_not_exist'));
        }
        
        $path = HelloSign::getStoreageDir($agreement->user_id);
        $file_name = $agreement->signature_request_id . '.pdf';
        $full_path = $path. DIRECTORY_SEPARATOR . $file_name;

        if(File::exists($full_path)) {
            return $full_path;
        } else {
            return false;
        }
    }
}
