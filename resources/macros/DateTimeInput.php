<?php
use App\Helpers\Template;

Form::macro('DateTimeInput', function($name, $selected = null, $options = array())
{
    $selected = $this->getValueAttribute($name, $selected);

    if(isset($options['class'])) $options['class'] = 'input-group '.$options['class'];
	else $options['class'] = 'input-group';
	
	$options = $this->html->attributes($options);
	
	if($selected && $selected != '0000-00-00 00:00:00') {
		$date = DateTime::createFromFormat(Template::getSqlDateTimeFormat(), $selected);
		if($date) $selected = $date->format(Template::getDisplayedDateTimeFormat());
        else {
            $date = DateTime::createFromFormat(Template::getSqlDateFormat(), $selected);
            if($date) $selected = $date->format(Template::getDisplayedDateFormat());
        }
	}

    $html = '<div '.$options.'>
				<div class="input-group-addon">
					<i class="fa fa-calendar"></i>
				</div>
				<input type="text" id="'.$name.'" name="'.$name.'" value="'.$selected.'" class="form-control datetime-input">
			</div>';
	
	return $html;
});