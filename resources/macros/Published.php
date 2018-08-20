<?php

Form::macro('Published', function($value)
{
	if($value) {
		return '<span class="published"><i class="fa fa-check-circle" aria-hidden="true"></i></span>';
	} else {
		return '<span class="not-published"><i class="fa fa-times-circle" aria-hidden="true"></i></span>';
	}
});