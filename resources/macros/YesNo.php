<?php

Form::macro('YesNo', function($name, $selected = null)
{
	$selected = $this->getValueAttribute($name, $selected);
	if($selected) $checked = ' checked="checked"';
	else $checked = '';
	
	$html = '<div data-toggle="buttons" class="myswitch make-switch switch-mini">
			<input type="checkbox" class="yesno-value" data-config="'.$name.'" data-on-text="'.Lang::get('general.yes').'" data-off-text="'.Lang::get('general.no').'" '.$checked.'>
			<input type="hidden" name="'.$name.'" value="'.(int)$selected.'">
		</div>';
		
	return $html;
});