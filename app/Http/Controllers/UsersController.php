<?php namespace App\Http\Controllers;

use File;
use Hash;
use Illuminate\Support\Facades\Request;
use Lang;
use Mail;
use Redirect;
use URL;
use View;
use Validator;
use Form;
use Carbon\Carbon;
use DB;

use Sentinel;
use Cartalyst\Sentinel\Laravel\Facades\Activation;
use Datatables;
use Excel;

use App\Http\Requests\UserRequest;
use App\User;
use App\Payments;
use App\Helpers\Template;

class UsersController extends WeedController
{

    protected $countries = '';

    public function __construct()
    {
        parent::__construct();
        $this->countries = Template::getCountriesList();
    }

    /**
     * Show a list of all the users.
     *
     * @return View
     */
    public function index()
    {
        // Grab all the users
        $users = User::All();

        // Show the page
        return View('admin.users.index', compact('users'));
    }

    /*
     * Pass data through ajax call
     */
    public function data()
    {
        if(true || Request::ajax()) {
            //$users = User::all();
            $users = User::leftJoin('activations', function($join) {
                $join->on('activations.user_id', '=', 'users.id');
            })->select(['users.*', DB::raw('COUNT(activations.completed) as activation')])->groupBy('users.id')->get();

            return Datatables::of($users)
                //->edit_column('created_at','{!! $created_at->diffForHumans() !!}')
                ->edit_column('spammer', function($user){
                    return Form::Spammer($user->id, $user->spammer);
                })
                ->edit_column('created_at', function($user){
                    return Carbon::parse($user->created_at)->diffForHumans();
                })
                ->edit_column('published',function($user){
                    return Form::Published($user->published);
                })
                /*->add_column('active', function($user){
                    return Form::Published($user->isActive());
                })*/
                ->add_column('activation', function($user){
                    return Form::Published($user->activation);
                })
                ->edit_column('location', function($user){
                    return $user->getFormatedAddress();
                })
                ->add_column('status', function($user){
                    if($activation = Activation::completed($user)) return Form::Published(1);
                    else return Form::Published(0);
                })
                ->add_column('actions', function($user) {
                    $current_user = Sentinel::getUser();
                    $actions = '';

                    if($current_user->hasAccess(['admin.users.edit'])) {
                        $actions .= '<a href="'.route('admin.users.edit', $user->id).'">
                                   <i class="livicon" data-name="info" data-size="18" data-loop="true" data-c="#428BCA" data-hc="#428BCA" title="view user"></i>
                                </a>';
                    }

                    if($current_user->hasAccess(['admin.users.edit'])) {
                        $actions .= '<a href="'.route('admin.users.edit', $user->id).'">
                                   <i class="livicon" data-name="edit" data-size="18" data-loop="true" data-c="#428BCA" data-hc="#428BCA" title="update user"></i>
                                </a>';
                    }

                    if ($current_user->hasAccess(['delete.user']) && ($current_user->id != $user->id) && ($user->id != 1)) {
                        $actions .= '<a href='. route('confirm-delete/user', $user->id) .' data-toggle="modal" data-target="#delete_confirm"><i class="livicon" data-name="user-remove" data-size="18" data-loop="true" data-c="#f56954" data-hc="#f56954" title="delete user"></i></a>';
                    }

                     $actions .= Template::getClaimUserLink($user->id);

                    return $actions;
                })
                ->make(true);
        }
    }

    /**
     * Create new user
     *
     * @return View
     */
    public function create()
    {
        // Get all the available groups
        $groups = Sentinel::getRoleRepository()->all();

        $countries = $this->countries;
        // Show the page
        return View('admin/users/create', compact('groups', 'countries'));
    }

    /**
     * User create form processing.
     *
     * @return Redirect
     */
    public function store(UserRequest $request)
    {
        $request = Template::replaceDates($request);

        $rules = array(
            'pic_file' => 'image|mimes:jpg,jpeg,bmp,png',
        );

        $validator = Validator::make($request->only('pic_file'), $rules);

        if ($validator->fails()) {
            return Redirect::back()
                ->with('error', Lang::get('users/message.error.file_type_error'))
                ->withInput();
        }

        if(!$request->has('newsletter')) {
           $request->request->add(['newsletter' => 0]);
        } else {
            Template::newsletterSignup($request);
        }

        //upload image
        if ($file = $request->file('pic_file')) {
            $fileName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $folderName = '/uploads/users/';
            $destinationPath = public_path() . $folderName;
            $safeName = str_random(10) . '.' . $extension;
            $file->move($destinationPath, $safeName);
            $request['pic'] = $safeName;
        }
        //check whether use should be activated by default or not
        $activate = $request->get('activate') ? true : false;

        try {
            // Register the user
            $user = Sentinel::register($request->except('_token', 'password_confirm', 'groups', 'activate', 'pic_file', 'permissions'), $activate);

            $roles = Sentinel::findRoleById($request->get('groups'));
            if($roles) {
                foreach($roles as $role) {
                    if ($role) {
                        $role->users()->attach($user);
                    }
                }
            }

            //individual permissions
            if($permissions = $request->get('permissions')) {
                foreach($permissions as $key => $value){
                    $user->addPermission($key);
                }
            }

            $user->save();

            //check for activation and send activation mail if not activated by default
            if (!$request->get('activate')) {
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
            }

            // Redirect to the home page with success menu
            return Redirect::route('admin.users.index')->with('success', Lang::get('users/message.success.create'));

        } catch (LoginRequiredException $e) {
            $error = Lang::get('admin/users/message.user_login_required');
        } catch (PasswordRequiredException $e) {
            $error = Lang::get('admin/users/message.user_password_required');
        } catch (UserExistsException $e) {
            $error = Lang::get('admin/users/message.user_exists');
        }

        // Redirect to the user creation page
        return Redirect::back()->withInput()->with('error', $error);
    }

    /**
     * User update.
     *
     * @param  int $id
     * @return View
     */
    public function edit($user = null)
    {
        $current_user = Sentinel::getUser();
        // Get this user groups
        $userRoles = $user->getRoles()->lists('name', 'id')->all();

        // Get a list of all the available groups
        $roles = Sentinel::getRoleRepository()->all();

        $status = Activation::completed($user);

        // Show the page
        return View('admin/users/edit', compact('current_user', 'user', 'roles', 'userRoles', 'status'));
    }

    /**
     * User update form processing page.
     *
     * @param  User $user
     * @param UserRequest $request
     * @return Redirect
     */
    public function update(User $user, UserRequest $request)
    {
        $request = Template::replaceDates($request);

        $rules = array(
            'pic_file' => 'image|mimes:jpg,jpeg,bmp,png',
        );

        $validator = Validator::make($request->only('pic_file'), $rules);

        if ($validator->fails()) {
            return Redirect::back()
                ->with('error',Lang::get('users/message.error.file_type_error'))
                ->withInput();
        }

        try {
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
            $user->published = $request->get('published');
            /*if($request->get('newsletter')) $user->newsletter = $request->get('newsletter');
            if($request->get('news')) $user->news = $request->get('news');
            else $user->news = 0;*/
            $user->spammer = $request->get('spammer', 0);

            if ($password = $request->has('password')) {
                $user->password = Hash::make($request->password);
            }

            // is new image uploaded?
            if ($file = $request->file('pic_file')) {
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

            //save record
            $user->save();

            //individual permissions
            $user->permissions = [];
            if($permissions = $request->get('permissions')) {
                foreach($permissions as $key => $value){
                    $user->addPermission($key);
                }
            }

            // Get the current user groups
            $userRoles = $user->roles()->lists('id')->all();

            // Get the selected groups
            $selectedRoles = $request->get('groups', array());

            // Groups comparison between the groups the user currently
            // have and the groups the user wish to have.
            $rolesToAdd = array_diff($selectedRoles, $userRoles);
            $rolesToRemove = array_diff($userRoles, $selectedRoles);

            // Assign the user to groups
            foreach ($rolesToAdd as $roleId) {
                $role = Sentinel::findRoleById($roleId);

                $role->users()->attach($user);
            }

            // Remove the user from groups
            foreach ($rolesToRemove as $roleId) {
                $role = Sentinel::findRoleById($roleId);

                $role->users()->detach($user);
            }

            // Activate / De-activate user
            $status = $activation = Activation::completed($user);
            if ($request->get('activate') != $status) {
                if ($request->get('activate')) {
                    $activation = Activation::exists($user);
                    if ($activation) {
                        Activation::complete($user, $activation->code);
                    }
                } else {
                    //remove existing activation record
                    Activation::remove($user);
                    //add new record
                    //Activation::create($user);

                    //send activation mail
                    $data = array(
                        'user' => $user,
                        'activationUrl' => URL::route('activate', [$user->id, Activation::create($user)->code]),
                    );

                    // Send the activation code through email
                    Mail::send('emails.register-activate', $data, function ($m) use ($user) {
                        $m->to($user->email, $user->first_name . ' ' . $user->last_name);
                        $m->subject('Welcome ' . $user->first_name . '! ACTIVATION REQUIRED');
                    });

                }
            }

            // Was the user updated?
            if ($user->save()) {
                // Prepare the success message
                $success = Lang::get('users/message.success.update');

                // Redirect to the user page
                return Redirect::route('admin.users.edit', $user)->with('success', $success);
            }

            // Prepare the error message
            $error = Lang::get('users/message.error.update');
        } catch (UserNotFoundException $e) {
            // Prepare the error message
            $error = Lang::get('users/message.user_not_found', compact('user'));
            // Redirect to the user management page
            return Redirect::route('admin.users.index')->with('error', $error);
        }

        // Redirect to the user page
        return Redirect::route('admin.users.edit', $user)->withInput()->with('error', $error);
    }

    /**
     * Show a list of all the deleted users.
     *
     * @return View
     */
    public function getDeletedUsers()
    {
        // Grab deleted users
        $users = User::onlyTrashed()->get();

        // Show the page
        return View('admin/deleted_users', compact('users'));
    }


    /**
     * Delete Confirm
     *
     * @param   int $id
     * @return  View
     */
    public function getModalDelete($id = null)
    {
        $model = 'users';
        $confirm_route = $error = null;
        try {
            // Get user information
            $user = Sentinel::findById($id);

            // Check if we are not trying to delete ourselves
            if ($user->id === Sentinel::getUser()->id) {
                // Prepare the error message
                $error = Lang::get('users/message.error.delete');

                return View('admin/layouts/modal_confirmation', compact('error', 'model', 'confirm_route'));
            }
        } catch (UserNotFoundException $e) {
            // Prepare the error message
            $error = Lang::get('users/message.user_not_found', compact('id'));
            return View('admin/layouts/modal_confirmation', compact('error', 'model', 'confirm_route'));
        }
        $confirm_route = route('delete/user', ['id' => $user->id]);
        return View('admin/layouts/modal_confirmation', compact('error', 'model', 'confirm_route'));
    }

    /**
     * Delete the given user.
     *
     * @param  int $id
     * @return Redirect
     */
    public function destroy($id = null)
    {
        try {
            // Get user information
            $user = Sentinel::findById($id);

            // Check if we are not trying to delete ourselves
            if ($user->id === Sentinel::getUser()->id) {
                // Prepare the error message
                $error = Lang::get('admin/users/message.error.delete');

                // Redirect to the user management page
                return Redirect::route('admin.users.index')->with('error', $error);
            }

            // Delete the user
            //to allow soft deleted, we are performing query on users model instead of Sentinel model
            //$user->delete();
            User::destroy($id);

            // Prepare the success message
            $success = Lang::get('users/message.success.delete');

            // Redirect to the user management page
            return Redirect::route('admin.users.index')->with('success', $success);
        } catch (UserNotFoundException $e) {
            // Prepare the error message
            $error = Lang::get('admin/users/message.user_not_found', compact('id'));

            // Redirect to the user management page
            return Redirect::route('admin.users.index')->with('error', $error);
        }
    }

    /**
     * Restore a deleted user.
     *
     * @param  int $id
     * @return Redirect
     */
    public function getRestore($id = null)
    {
        try {
            // Get user information
            $user = User::withTrashed()->find($id);

            // Restore the user
            $user->restore();

            // create activation record for user and send mail with activation link
            $data = array(
                'user' => $user,
                'activationUrl' => URL::route('activate', [$user->id, Activation::create($user)->code]),
            );

            // Send the activation code through email
            Mail::send('emails.register-activate', $data, function ($m) use ($user) {
                $m->to($user->email, $user->first_name . ' ' . $user->last_name);
                $m->subject('Dear ' . $user->first_name . '! ACTIVATION REQUIRED');
            });


            // Prepare the success message
            $success = Lang::get('users/message.success.restored');

            // Redirect to the user management page
            return Redirect::route('deleted_users')->with('success', $success);
        } catch (UserNotFoundException $e) {
            // Prepare the error message
            $error = Lang::get('users/message.user_not_found', compact('id'));

            // Redirect to the user management page
            return Redirect::route('deleted_users')->with('error', $error);
        }
    }

    /**
     * Display specified user profile.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        try {
            // Get the user information
            $user = Sentinel::findUserById($id);

            //get country name
            if ($user->country) {
                $user->country = $this->countries[$user->country];
            }

        } catch (UserNotFoundException $e) {
            // Prepare the error message
            $error = Lang::get('users/message.user_not_found', compact('id'));

            // Redirect to the user management page
            return Redirect::route('admin.users.index')->with('error', $error);
        }

        // Show the page
        return View('admin.users.show', compact('user'));

    }

    public function passwordreset($id)
    {
        if (Request::ajax()) {
            $data = Request::all();
            $user = Sentinel::findUserById($id);
            $password = Request::get('password');
            $user->password = Hash::make($password);
            $user->save();

        }
    }

    public function lockscreen($id){
        $user = Sentinel::findUserById($id);
        return View('admin/lockscreen',compact('user'));
    }

    public function export()
    {
        return View('admin.users.export');
    }

    public function postExport(\Illuminate\Http\Request $request)
    {
        $data = User::select('first_name', 'last_name', 'email')->where('news', '=', 1)->get()->toArray();

        Excel::create('users', function($excel) use($data) {
            $excel->sheet('users', function($sheet) use($data) {
                $sheet->fromArray($data);
            });
        })->export('csv');

        //return Redirect::back()->with('success', trans('users/message.success.export'));
    }

    public function importedList()
    {
        $users = User::where('imported', '=', 1)->get();

        return View('admin.users.imported', compact('users'));
    }

    public function setSpammer()
    {
        if (Request::ajax()) {
            $msg = '';
            $user_id = Request::get('user_id');
            $value = Request::get('spammer', 0);

            if($user_id) {
                try {
                    $user = Sentinel::findUserById($user_id);

                    $user->spammer = $value;
                    $user->save();

                    $msg = Lang::get('users/message.success.update');
                } catch (UserNotFoundException $e) {
                    return response()->json(['success' => false, 'msg' => Lang::get('users/message.user_not_found', compact('id'))]);
                }
            } else {
                return response()->json(['success' => false, 'msg' => Lang::get('users/message.user_not_found', compact('id'))]);
            }

            return response()->json(['success' => true, 'msg' => $msg]);
        }
    }
}
