<?php

namespace App\Http\Controllers;

use Lang;
use Redirect;
use Mail;
use Carbon\Carbon;

use Activation;
use Laravel\Socialite\Contracts\Factory as Socialite;
use Sentinel;
use GoogleMaps;

use Illuminate\Http\Request;

use App\Helpers\Template;

class SocialController extends WeedController
{

    public function __construct(Socialite $socialite)
    {
        $this->socialite = $socialite;
    }

    public function getSocialAuth($provider = null)
    {
        if (Sentinel::guest()) {
            if(!config("services.$provider")) abort('404'); //just to handle providers that doesn't exist
            if($provider == 'facebook') {
                return $this->socialite->with($provider)->fields([
                    'first_name', 'last_name', 'email'
                ])->scopes([
                    'email'
                ])->redirect();

            } else {
                return $this->socialite->with($provider)->redirect();
            }
        } else {
            return Redirect::route('home');
        }
    }

    public function getSocialAuthCallback(Request $request, $provider = null)
    {
        if (!$request->has('code') || $request->has('denied')) {
            return redirect('/login');
        }
        if (Sentinel::guest()) {
            if($provider == 'facebook') {
                if($user = $this->socialite->with($provider)->fields([
                    'first_name', 'last_name', 'email'
                ])->user()) {
                    return $this->handleFacebook($user);
                }
            }  elseif($provider == 'twitter') {
                if($user = $this->socialite->with($provider)->user()){
                    return $this->handleTwitter($user);
                }
            }
        } else {
            return Redirect::route('home');
        }
    }

    public function userExist($email)
    {
        $credentials = [
            'email' => $email,
        ];

        $user = Sentinel::findByCredentials($credentials);

        if($user) {
            Sentinel::login($user);

            return $user;
        }

        return false;
    }

    public function handleFacebook($fb_user)
    {
        if(isset($fb_user->user)) {
            $login = $this->userExist($fb_user->user['email']);
            if($login) {

            } else {
                $this->facebookRegister($fb_user);
            }

            return $this->afterSocial();
        }
    }

    public function handleTwitter($tw_user)
    {
        if(isset($tw_user->user)) {
            $login = $this->userExist($tw_user->email);

            if($login) {

            } else {
                $this->twitterRegister($tw_user);
            }

            return $this->afterSocial();
        }
    }

    public function facebookRegister($user)
    {
        $users = Sentinel::getUserRepository();
        $model = $users->getModel();
        $uniquePassword = str_random(8);

        $fname = '';
        $lname = '';
        if(isset($user->user['name'])) {
            $full_name = explode(' ', $user->user['name']);
            if(isset($full_name[0])) $fname = $full_name[0];
            if(isset($full_name[1])) $lname = $full_name[1];
        }

        if($user->user['first_name']) $fname = $user->user['first_name'];
        if($user->user['last_name']) $lname = $user->user['last_name'];

        $fields = array(
            'email' => $user->user['email'],
            'password' => $uniquePassword,
            'published' => 1,
            'first_name' => $fname,
            'last_name' => $lname,
            'pic' => $user->avatar_original,
        );

        if(isset($user->user['location']) && isset($user->user['location']['name'])) {
            $response = json_decode(GoogleMaps::load('geocoding')
                ->setParam (['address' => $user->user['location']['name']])
                ->get());
            if($response->results && isset($response->results[0])) {
                $google_location = Template::getNewLocationArray($response->results[0]);
                $fields['country'] = $google_location['iso_code'];
                $fields['state'] = $google_location['state'];
                $fields['city'] = $google_location['city'];
                $fields['postal'] = $google_location['postal_code'];
            }
        }

        if(isset($user->user['birthday']) && $birthday = $user->user['birthday']) {
            $fields['dob'] = Carbon::parse($birthday);
        }

        $user = Sentinel::register($fields, true); // no need to activate

        $role = Sentinel::findRoleByName('User');
        $role->users()->attach($user);

        Sentinel::login($user);

        $this->sendEmail($fields);
    }

    public function twitterRegister($user)
    {
        $users = Sentinel::getUserRepository();
        $model = $users->getModel();
        $uniquePasswords = str_random(8);

        $full_name = explode(' ', $user->name);
        if(isset($full_name[0])) $fname = $full_name[0];
        else $fname = '';

        if(isset($full_name[1])) $lname = $full_name[1];
        else $lname = '';

        $fields = array(
            'email' => $user->email,
            'password' => $uniquePasswords,
            'published' => 1,
            'first_name' => $fname,
            'last_name' => $lname,
            'pic' => $user->avatar_original,
        );

        $user = Sentinel::register($fields, true); // no need to activate

        $role = Sentinel::findRoleByName('User');
        $role->users()->attach($user);

        Sentinel::login($user);

        $this->sendEmail($fields);
    }

    public function sendEmail($data)
    {
        $user = Sentinel::getUser();
        Mail::send('emails.register-social', $data, function ($m) use ($user) {
            $m->to($user->email, $user->first_name . ' ' . $user->last_name);
            $m->subject('Welcome ' . $user->first_name);
        });
    }

    public function afterSocial()
    {
        if(Sentinel::getUser()) {
            return Redirect::route('my-account')->with('success', Lang::get('auth/message.login.success'));
        } else {
            return Redirect::route('login')->with('error', Lang::get('auth/message.signup.error'));
        }
    }
}
