<?php

use App\Helpers\ExtraFields;
use App\Helpers\Template;

Form::macro('Settings', function($setting, $errors)
{	
	$placeholder = ($setting->placeholder) ? ' placeholder="'.$setting->placeholder.'"' : '';
	$sessionValue = Session::getOldInput($setting->code);

	$values = false;
	if($setting->options) {
		$array = unserialize($setting->options);
		if(isset($array['values'])) $values = $array['values'];
	}
	
	$current_val = $setting->value;
	$name = $setting->code;
	
	if(Form::old($name)) {
		$current_val = Form::old($name);
	}
	
	$has_error = $errors->first($name, 'has-error');
	
	$html = '<div class="form-group'.(($has_error) ? ' has-error' : '').'">';
	
	switch($setting->type) {
		case('text'): 
			$html .= '<label class="control-label col-sm-2" for="'.$name.'">'.$setting->title.'</label>';
			$html .= '<div class="col-sm-5">
				<input class="form-control" type="text" value="'.$current_val.'" name="'.$name.'"'.$placeholder.' />
			</div>';
			break;
		case('textarea'): 
			$html .= '<label class="control-label col-sm-2" for="'.$name.'">'.$setting->title.'</label>';
			$html .= '<div class="col-sm-5">
				<textarea class="form-control" name="'.$name.'"'.$placeholder.' rows="3">'.$current_val.'</textarea>
			</div>';
			break;
		case('price'): 
			$html .= '<label class="control-label col-sm-2" for="'.$name.'">'.$setting->title.'</label>';
			$html .= '<div class="col-sm-5"><input class="form-control validate-price" type="text" value="'.$current_val.'" id="'.$name.'" name="'.$name.'"'.$placeholder.' /></div>';
			break;
		case('radio'): 
			$html .= '<label class="control-label col-sm-2" for="'.$name.'">'.$setting->title.'</label>';
			$html .= '<div class="col-sm-5">';

			if($values) {
				foreach($values as $key => $value) {
					if($key == $current_val) $selected = ' checked="checked"';
					else $selected = '';
					
					$html .= '<div class="radio-inline">
						<label><input type="radio" name="'.$name.'" value="'.$key.'"'.$selected.'>'.$value.'</label>
					</div>';
				}
			}
			$html .= '</div>';
			break;
		case('usergroups'):
			$values = Sentinel::getRoleRepository()->lists('name', 'id')->all();
		case('select'):
			$html .= '<label class="control-label col-sm-2" for="'.$name.'">'.$setting->title.'</label>';
			$html .= '<div class="col-sm-5"><select class="form-control select2" id="'.$name.'" name="'.$name.'"'.$placeholder.'>';
			
			if($values) {
				foreach($values as $key => $value) {
					if($key == $current_val) $selected = ' selected';
					else $selected = '';
					
					$html .= '<option value="'.$key.'"'.$selected.'>'.$value.'</option>';
				}
			}
			$html .= '</select></div>';
			break;
		case('countries'):
			$values = Template::getCountriesList(false);
		case('multiselect'):
			$html .= '<label class="control-label col-sm-2" for="'.$name.'">'.$setting->title.'</label>';
			$html .= '<div class="col-sm-5"><select class="form-control multiselect-value" name="'.$name.'[]"'.$placeholder.' multiple="multiple">';
            
            if(!is_array($current_val)) $current_values = explode(',', $current_val);
			else $current_values = $current_val;
			
            if($values) {
				foreach($values as $key => $value) {
					if(in_array($key, $current_values)) $selected = ' selected=""';
					else $selected = '';

                    $html .= '<option value="'.$key.'"'.$selected.'>'.$value.'</option>';
				}
			}
			$html .= '</select></div>';
			break;
		case('yesno'):
			$checked = ((int)$current_val) ? ' checked="checked"' : '';
			
			$html .= '<label class="control-label col-sm-2" for="'.$name.'">'.$setting->title.'</label>';
			$html .= '<div class="col-sm-5">
					<div data-toggle="buttons" class="myswitch make-switch switch-mini">
						<input type="checkbox" class="yesno-value" data-config="'.$name.'"'.$checked.'>
						<input type="hidden" name="'.$name.'" value="'.$current_val.'">
					</div></div>';
			break;
		case('cron'):
			$input = '';
			$select = '';
			
			if(is_array($current_val)) {
				if(isset($current_val['input'])) $input = $current_val['input'];
				if(isset($current_val['select'])) $select = $current_val['select'];
			} elseif($current_val) {
				$set_values = unserialize($current_val);
				if(isset($set_values['input'])) $input = $set_values['input'];
				if(isset($set_values['select'])) $select = $set_values['select'];
			}
			
			$html .= '<label class="control-label col-sm-2" for="'.$name.'">'.$setting->title.'</label>';
			$html .= '<div class="col-sm-3"><input class="form-control" type="text" value="'.$input.'" name="'.$name.'[input]"'.$placeholder.' /></div>';
			
			$html .= '<div class="col-sm-3"><select class="form-control select2" name="'.$name.'[select]"'.$placeholder.'>';
			if($values) {
				foreach($values as $key => $value) {
					if($key == $select) $selected = ' selected';
					else $selected = '';
					
					$html .= '<option value="'.$key.'"'.$selected.'>'.$value.'</option>';
				}
			}
			$html .= '</select></div>';
			break;
		case('image'):
			$html .= '<label class="control-label col-sm-2" for="'.$name.'">'.$setting->title.'</label>';
			$html .= '<div class="col-sm-5">
				<div class="fileinput fileinput-new" data-provides="fileinput">
					<div class="fileinput-new thumbnail" style="max-width: 200px; max-height: 150px;">';
					if($current_val)
						$html .= '<img src="'.Template::getSettingsImageDir().$current_val.'" class="img-responsive" />';

					$html .= '</div>
						<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"></div>
							<div>
								<span class="btn btn-primary btn-file">
									<span class="fileinput-new">'.Lang::get('front/general.select_image').'</span>
									<span class="fileinput-exists">'.Lang::get('front/general.change').'</span>
									<input type="file" name="'.$setting->code.'" />
								</span>
								<span class="btn btn-primary fileinput-exists" data-dismiss="fileinput">'.Lang::get('front/general.remove').'</span>
							</div>
						</div></div>';
			break;
	}
	
	if($has_error) {
		$html .= '<div class="col-sm-4">' . $errors->first($name, '<span class="help-block">:message</span> ') . '</div>';
	}
	
	$html .= '</div>';
	
	return $html;
});