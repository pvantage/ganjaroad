<?php
/**
* Language file for auth error messages
*
*/

return array(

    'account_already_exists' => 'An account with this email already exists.',
    'account_not_found' => 'Email or password is incorrect.',
    'account_email_not_found' =>'Email is incorrect.',
    'account_not_activated'  => 'This user account is not activated.',
    'account_suspended'      => 'User account suspended because of too many login attempts. Try again after [:delay] seconds',
    'account_banned'         => 'This user account is banned.',
    'account_unpublished'    => 'Your account has been not approved by administrator yet.',
    'not_valid_user_or_password'    => 'Username or password is incorrect.',

    'signin' => array(
        'error'   => 'There was a problem while trying to log you in, please try again.',
        'success' => 'You have successfully logged in.',
    ),

    'login' => array(
        'error'   => 'There was a problem while trying to log you in, please try again.',
        'success' => 'You have successfully logged in.',
    ),

    'signup' => array(
        'error'   => 'There was a problem while trying to create your account, please try again.',
        'success' => 'Account sucessfully created.',
        'activate' => 'Your account has been created, please check your email for the activation link from do_not_reply@ganjaroad.com. If you do not see this please check your spam/junk folder. Please add do_not_reply@ganjaroad.com to your address book to help ensure delivery. If you did not receive the email, please email us at info@ganjaroad.com',
        'approve' => 'Account sucessfully created, but need to be approved by administrator',
    ),

	'forgot-password' => array(
		'error'   => 'There was a problem while trying to get a reset password code, please try again.',
		'success' => 'Password recovery email successfully sent.',
	),

	'forgot-password-confirm' => array(
		'error'   => 'There was a problem while trying to reset your password, please try again.',
		'success' => 'Your password has been successfully reset.',
	),

    'activate' => array(
        'error'   => 'There was a problem while trying to activate your account, please try again.',
        'success' => 'Your account has been successfully activated.',
    ),

    'contact' => array(
        'error'   => 'There was a problem while trying to submit the contact form, please try again.',
        'success' => 'Your contact details has been successfully sent. ',
    ),
);
