<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use File;
use Hash;
use Illuminate\Http\Request;
use Lang;
use Mail;
use Redirect;
use Reminder;
use URL;
use View;
use Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use Activation;
use Sentinel;
use Cartalyst\Sentinel\Checkpoints\NotActivatedException;
use Cartalyst\Sentinel\Checkpoints\ThrottlingException;
use GoogleMaps;

use App\Http\Requests\UserRequest;
use App\Http\Requests\FrontendRequest;
use Oneweb\Extensions\NotPublishedException;
use App\User;
use App\Helpers\Template;
use App\ClassifiedCategory;
use App\Nearme;
use App\Page;
use GeoIP;

class FrontEndController extends WeedController
{

    private $user_activation = false;
    //private $user_approval = false;

    public function __construct()
    {
        parent::__construct();
        $this->user_activation = (bool)Template::getSetting('user_activation');
        //$this->user_approval = (bool)Template::getSetting('user_approval');
    }

    public function getLogin()
    {
        // Is the user logged in?
        if (User::check()) {
            return redirect('my-account');
        }

        // Show the login page
        return View::make('login');
    }

    public function getActivation()
    {
        // Is the user logged in?
        if (User::check()) {
            return redirect('my-account');
        }

        // Show the login page
        return View::make('activation');
    }

    public function resendActivation(Request $request)
    {
        // Is the user logged in?
        if (User::check()) {
            return redirect('my-account');
        }

        $user = Sentinel::findByCredentials(['email' => $request->get('email')]);
        if($user) {
            $activation = Activation::completed($user);
            if(!$activation || !$activation->completed) {
                $data = array(
                    'user' => $user,
                    'activationUrl' => URL::route('activate', [$user->id, Activation::create($user)->code]),
                );

                Mail::send('emails.register-activate', $data, function ($m) use ($user) {
                    $m->to($user->email, $user->first_name . ' ' . $user->last_name);
                    $m->subject('Welcome ' . $user->first_name . '! ACTIVATION REQUIRED');
                });
            } else {
                return redirect('login')->with('success', Lang::get('front/general.already_activated'));
            }
        }

        // Show the login page
        return redirect('activation')->with('success', Lang::get('front/general.activation_sent'));
    }


    public function postLogin(Request $request)
    {
        try {
            // Try to log the user in
            if (User::authenticate($request->only('email', 'password'), $request->get('remember-me', 0))) {
                if($url = $request->get('redirect')) {
                    $url = base64_decode($url);
                    return Redirect::to($url)->with('success', Lang::get('auth/message.login.success'));
                } else return redirect("my-account")->with('success', Lang::get('auth/message.login.success'));
            } else {
                return Redirect::to('login')->with('error', Lang::get('auth/message.not_valid_user_or_password'));
                //return Redirect::back()->withInput()->withErrors($validator);
            }

        } catch (NotPublishedException $e) {
            $this->messageBag->add('email', Lang::get('auth/message.account_unpublished'));
        }catch (UserNotFoundException $e) {
            $this->messageBag->add('email', Lang::get('auth/message.account_not_found'));
        } catch (NotActivatedException $e) {
            $this->messageBag->add('email', Lang::get('auth/message.account_not_activated'));
        } catch (UserSuspendedException $e) {
            $this->messageBag->add('email', Lang::get('auth/message.account_suspended'));
        } catch (UserBannedException $e) {
            $this->messageBag->add('email', Lang::get('auth/message.account_banned'));
        } catch (ThrottlingException $e) {
            $delay = $e->getDelay();
            $this->messageBag->add('email', Lang::get('auth/message.account_suspended', compact('delay')));
        }

        // Ooops.. something went wrong
        return Redirect::back()->withInput()->withErrors($this->messageBag);
    }

    public function myAccount(User $user)
    {
        $user = Sentinel::getUser();
        $countries = Template::getCountriesList();
        $text = false;

        try {
            $page = Page::where('url', 'LIKE', 'placead')->where('published', '=', 1)->firstOrFail();
            $text = $page->content;
        } catch (ModelNotFoundException $e) {

        }

        return View::make('frontend.user.user_account', compact('user', 'countries', 'text'));
    }

    public function update(UserRequest $request)
    {
        $user = Sentinel::getUser();
        $request = Template::replaceDates($request);

        //update values
        $user->first_name = $request->get('first_name');
        $user->last_name = $request->get('last_name');
        $user->email = $request->get('email');
        $user->dob = $request->get('dob');
        $user->bio = $request->get('bio');
        $user->gender = $request->get('gender');
        $user->country = $request->get('country');
        $user->state = $request->get('state');
        $user->city = $request->get('city');
        $user->address = $request->get('address');
        $user->postal = $request->get('postal');

        if ($password = $request->get('password')) {
            $user->password = Hash::make($password);
        }
        // is new image uploaded?
        if ($file = $request->file('pic')) {
            $fileName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $folderName = '/uploads/users/';
            $destinationPath = public_path() . $folderName;
            $safeName = str_random(10) . '.' . $extension;
            $file->move($destinationPath, $safeName);

            //delete old pic if exists
            if (File::exists(public_path() . $folderName . $user->pic)) {
                File::delete(public_path() . $folderName . $user->pic);
            }

            //save new file path into db
            $user->pic = $safeName;

        }

        // Was the user updated?
        if ($user->save()) {
            // Prepare the success message
            $success = Lang::get('users/message.success.update');

            // Redirect to the user page
            return redirect('my-account')->with('success', $success);
        }

        // Prepare the error message
        $error = Lang::get('users/message.error.update');


        // Redirect to the user page
        return redirect('my-account')->withInput()->with('error', $error);
    }

    public function getRegister()
    {
        // Show the page
        return View::make('register');
    }

    public function postRegister(UserRequest $request)
    {
        $rules = array(
            'first_name' => 'required|min:3',
            'last_name' => 'required|min:3',
            'email'=> 'required|email|unique:users,email,NULL,id,deleted_at,NULL',
            'phone' => 'numeric',
            //'email_confirm' => 'required|email|same:email',
            'password' => 'required|between:3,32',
            'password_confirm' => 'required|same:password',
            'terms' => 'accepted',
            'country' => 'required',
            'city' => 'required',
        );

        if($request->get('country') == 'US') {
            $rules += array('state' => 'required');
        }

        if(!$request->has('newsletter')) {
           $request->request->add(['newsletter' => 0]);
           $request->request->add(['news' => 0]);
        } else {
            Template::newsletterSignup($request);
            $request->request->add(['news' => 1]);
        }

        // Create a new validator instance from our validation rules
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Redirect::back()->withInput()->withInput()->withErrors($validator);
        }

        $activate = $this->user_activation;
        //$approval = $this->user_approval;
        if(Sentinel::createModel()->withTrashed()->where('email', $request->email)->first()) {
            return redirect('forgot-password')->with('email', $request->get('email'));
        }

        try {
            $request->request->add(['published' => 1]);
            // Register the user
            $user = Sentinel::register($request->only(['first_name', 'last_name', 'email', 'country', 'state', 'city', 'password', 'newsletter', 'news', 'spammer', 'published']), false);

            //add user to 'User' group
            $role = Sentinel::findRoleByName('User');
            $role->users()->attach($user);

            //add user to 'User Near Me' group
            $role = Sentinel::findRoleByName('User Near Me');
            $role->users()->attach($user);

            //add user to 'User Display Ads' group
            $role = Sentinel::findRoleByName('User Display Ads');
            $role->users()->attach($user);

            if ($activate) {
                // Data to be used on the email view
                $data = array(
                    'user' => $user,
                    'activationUrl' => URL::route('activate', [$user->id, Activation::create($user)->code]),
                );

                // Send the activation code through email
                Mail::send('emails.register-activate', $data, function ($m) use ($user) {
                    $m->to($user->email, $user->first_name . ' ' . $user->last_name);
                    $m->subject('Welcome ' . $user->first_name . '! ACTIVATION REQUIRED');
                });

                //Redirect to login page
                return Redirect::to('login')->with('success', Lang::get('auth/message.signup.activate'));
            } /*elseif($approval) {
                return Redirect::to('login')->with('success', Lang::get('auth/message.signup.approve'));
            }*/
            // login user automatically
            User::login($user, false);

            // Redirect to the home page with success menu
            return redirect('my-account')->with('success', Lang::get('auth/message.signup.success'));

        } catch (UserExistsException $e) {
            $this->messageBag->add('email', Lang::get('auth/message.account_already_exists'));
        }

        // Ooops.. something went wrong
        return Redirect::back()->withInput()->withErrors($this->messageBag);
    }

    public function getActivate($userId, $activationCode)
    {
        // Is the user logged in?
        if (User::check()) {
            return redirect('my-account');
        }

        $user = Sentinel::findById($userId);

        if (Activation::complete($user, $activationCode)) {
            // Activation was successfull
            return redirect('login')->with('success', Lang::get('auth/message.activate.success'));
        } else {
            // Activation not found or not completed.
            $error = Lang::get('auth/message.activate.error');
            return redirect('login')->with('error', $error);
        }
    }

    public function getForgotPassword()
    {
        // Show the page
        return View::make('forgotpwd');

    }

    public function postForgotPassword(Request $request)
    {
        try {
            // Get the user password recovery code
            //$user = Sentinel::FindByLogin($request->get('email'));
            $user = Sentinel::createModel()->withTrashed()->where('email', $request->email)->first();
            if (!$user) {
                return redirect('forgot-password')->with('error', Lang::get('auth/message.account_email_not_found'));
            }

            if($user->deleted_at) {
                $user->restore();
            }

            $activation = Activation::completed($user);
            if (!$activation) {
                return redirect('forgot-password')->with('error', Lang::get('auth/message.account_not_activated'));
            }

            $reminder = Reminder::exists($user) ?: Reminder::create($user);
            // Data to be used on the email view
            $data = array(
                'user' => $user,
                //'forgotPasswordUrl' => URL::route('forgot-password-confirm', $user->getResetPasswordCode()),
                'forgotPasswordUrl' => URL::route('forgot-password-confirm', [$user->id, $reminder->code]),
            );

            // Send the activation code through email
            Mail::send('emails.forgot-password', $data, function ($m) use ($user) {
                $m->to($user->email, $user->first_name . ' ' . $user->last_name);
                $m->subject('Account Password Recovery');
            });
        } catch (UserNotFoundException $e) {
            // Even though the email was not found, we will pretend
            // we have sent the password reset code through email,
            // this is a security measure against hackers.
        }

        //  Redirect to the forgot password
        return Redirect::to(URL::previous())->with('success', Lang::get('auth/message.forgot-password.success'));
    }

    public function getForgotPasswordConfirm($userId, $passwordResetCode = null)
    {
        if (!$user = Sentinel::findById($userId)) {
            // Redirect to the forgot password page
            return redirect('forgot-password')->with('error', Lang::get('auth/message.account_not_found'));
        }

        if($reminder = Reminder::exists($user))
        {
            if($passwordResetCode == $reminder->code)
            {
                return View::make('forgotpwd-confirm', compact(['userId', 'passwordResetCode']));
            }
            else{
                return 'code does not match';
            }
        }
        else
        {
            return 'does not exists';
        }

    }

    public function postForgotPasswordConfirm(Request $request, $userId, $passwordResetCode = null)
    {

        $user = Sentinel::findById($userId);
        if (!$reminder = Reminder::complete($user, $passwordResetCode, $request->get('password'))) {
            // Ooops.. something went wrong
            return redirect('login')->with('error', Lang::get('auth/message.forgot-password-confirm.error'));
        }

        // Password successfully reseted
        return redirect('login')->with('success', Lang::get('auth/message.forgot-password-confirm.success'));
    }

    public function postContact(Request $request)
    {
        $rules = array(
            'name' => 'required|min:3',
            'email' => 'required|email',
            'message' => 'required|min:3',
            'g-recaptcha-response' => 'required|recaptcha',
        );

        // Create a new validator instance from our dynamic rules
        $validator = Validator::make($request->all(), $rules);

        // If validation fails, we'll exit the operation now.
        if ($validator->fails()) {
            // Ooops.. something went wrong
            return Redirect::back()->withInput()->withErrors($validator);
        }

        // Data to be used on the email view
        $data = array(
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'message' => $request->get('message'),
        );

        $to = Template::getSetting('contactform_email');
        $subject = Template::getSetting('contactform_subject');

        Mail::send('emails.contact', compact('data'), function ($m) use ($data, $to, $subject) {
            $m->from($data['email'], $data['name']);
            $m->to($to, @trans('general.site_name'));
            $m->subject($subject);

        });

        $send_to_user = (int)Template::getSetting('contactform_send_to_user');
        if($send_to_user) {
            Mail::send('emails.contact_to_user', compact('data'), function ($m) use ($data, $to, $subject) {
                $m->from(env('MAIL_USERNAME', ''), env('MAIL_SENDER', ''));
                $m->to($data['email'], $data['name']);
                $m->subject($subject);
            });
        }

        //Redirect to contact page
        return Redirect::to('contact')->with('success', Lang::get('auth/message.contact.success'));
    }

    public function getLogout()
    {
        // Log the user out
        User::logout();

        // Redirect to the users page
        return Redirect::to('/')->with('success', 'You have successfully logged out!');
    }


    public function index()
    {
        if(!session('current_city')) {
            $location = Template::getCurrentLocation();

            $country = $location['iso_code'];
            $city = trim(strtolower(str_replace(' ', '-', $location['city'])));

            try {
                $category = ClassifiedCategory::where('slug', 'LIKE', $city)->where('published', '=', 1)->firstOrFail();
                session(['current_city' => (int)$category->id]);
            } catch (ModelNotFoundException $e) {

            }
        }

        return View('index');
    }

    public function changeLocation(Request $request)
    {
        $location = $request->get('changelocation');

        if($location) {
            $response = json_decode(GoogleMaps::load('geocoding')
                ->setParam (['address' => $location])
                ->get());
            if($response->results && isset($response->results[0])) {
                $new_location = Template::getNewLocationArray($response->results[0]);
                session(['current_location' => $new_location]);
            }
        }

        return Redirect::to('/');
    }

    public function changenearmelocation(Request $request)
    {
        $location = $request->get('nearmelocation');
        if($location) {
            $response = json_decode(GoogleMaps::load('geocoding')
                ->setParam (['address' => $location])
                ->get());
            if($response->results && isset($response->results[0])) {
                $new_location = Template::getNewLocationArray($response->results[0]);
                //session(['current_location' => $new_location]);
                session(['nearme_search' => $new_location]);

                return Redirect::to('nearme');
            } else {
                return Redirect::route('searchnearme', ['query' => $location]);
            }
        }

        return Redirect::to('/');
    }

    public function changeclassifiedslocation(Request $request)
    {
        $location = $request->get('search_classifieds');
        if($location) {
            session(['current_city' => (int)$location]);
        }

        return Redirect::to('/');
    }

    public function changeClassifiedsCategory(Request $request)
    {
        $category = $request->get('city');
        if($category) {
            session(['current_city' => (int)$category]);
        }

        return View::make('classifieds_categories');
    }

}
