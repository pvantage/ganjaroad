<?php

Form::macro('Author', function($value)
{
	$user = Sentinel::findById($value);
	if($user) {
        $url = URL::to('admin/users/' . $user->id .'/edit');
        if($user->last_name || $user->first_name) $name = $user->last_name . ' ' . $user->first_name;
        else $name = $user->email;
        
		return '<a href="'.$url.'">'.$name.'</a>';
	} else {
		return trans('general.user_not_exist');
	}
	
});