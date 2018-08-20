<?php

use App\Helpers\ExtraFields;

Form::macro('ExtraFields', function($name, $list = array(), $selected = null, $options = array(), $admin = false)
{
	if (Lang::has('extrafields.'.$list->placeholder)) {
		$text = Lang::get('extrafields.'.$list->placeholder);
	} else $text = $list->placeholder;
	
	$placeholder = ($text) ? ' placeholder="'.$text.'"' : '';
	$sessionValue = Session::getOldInput($name);

	$values = false;
	if($list->options) {
		$array = unserialize($list->options);
		if(isset($array['values'])) $values = $array['values'];
	}
	
	$old = '';
	if(Form::old($name)) {
		$old = Form::old($name);
	}elseif($selected) {
		if(is_array($selected)) {
			foreach($selected as $field) {
				if($field['code'] == $name) $old = $field['value'];
			}
		} else $old = $selected;
	}

	switch($list->type) {
		case('text'): 
			
			$html = '<label class="control-label" for="'.$name.'">'.$list->title.'</label>';
			$html .= '<div class="">
				<input class="form-control" type="text" value="'.htmlspecialchars($old).'" name="'.$name.'"'.$placeholder.' />
			</div>';
			break;
		case('price'): 
			$html = '<label class="control-label" for="'.$name.'">'.$list->title.'</label>';
			$html .= '<div class=""><input class="form-control validate-price" type="text" value="'.$old.'" id="'.$name.'" name="'.$name.'"'.$placeholder.' /></div>';
			break;
		case('radio'): 
			$html = '<label class="control-label" for="'.$name.'">'.$list->title.'</label>';
			$html .= '<div class="">';

			if($values) {
				foreach($values as $key => $value) {
					if($key == $old) $selected = ' checked="checked"';
					else $selected = '';
					
					$html .= '<div class="radio-inline">
						<label><input type="radio" name="'.$name.'" value="'.$key.'"'.$selected.'>'.$value.'</label>
					</div>';
				}
			}
			$html .= '</div>';
			break;
		case('country'):
		case('state'):
		case('select'): 
			if($list->type == 'country') {
				$values = TemplateHelper::getCountriesList(false);
				if(!$old) $old = 'US';
			}
			if($list->type == 'state') $values = TemplateHelper::getStateList(true);
			
			$html = '<label class="control-label" for="'.$name.'">'.$list->title.'</label>';
			if($list->type == 'state') $html .= '<div class=""><select class="form-control select2" name="'.$name.'"'.$placeholder.'>';
			else $html .= '<div class=""><select class="form-control select2" id="'.$name.'" name="'.$name.'"'.$placeholder.'>';

			if($values) {
				foreach($values as $key => $value) {
					if($key == $old) $selected = ' selected';
					else $selected = '';
					
					$html .= '<option value="'.$key.'"'.$selected.'>'.$value.'</option>';
				}
			}
			$html .= '</select></div>';
			
			if($list->type == 'state') {
				$html .= '<input class="form-control" type="text" value="'.$old.'" id="'.$name.'" name="'.$name.'"'.$placeholder.' />';
			}
			
			break;
	}
	
	return $html;
});