<?php

Form::macro('Access', function($value)
{
	if($value) {
		return '<span class="published"><i class="fa fa-check-circle" aria-hidden="true"></i></span>';
	} else {
		return '<span class="not-published">'.Lang::get('access/table.waiting_approval').'</span>';
	}
});