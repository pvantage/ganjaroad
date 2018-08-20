<?php

Form::macro('Active', function($value)
{
	switch($value) {
		case(0): return '<span class="unpublished not-active"><i class="fa fa-times-circle" aria-hidden="true"></i></span>'; break; //unpublished
		case(1): return '<span class="active"><i class="fa fa-check-circle" aria-hidden="true"></i></span>'; break; //active
		case(2): return '<span class="expired"><i class="fa fa-times-circle" aria-hidden="true"></i></span>'; break; //expired
	}
});