<?php

Form::macro('TableYesNo', function($value)
{
	if($value) {
		return '<span class="table-yes">'.Lang::get('general.yes').'</span>';
	} else {
		return '<span class="table-no">'.Lang::get('general.no').'</span>';
	}
});