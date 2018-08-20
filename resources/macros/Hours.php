<?php

Form::macro('Hours', function($name, $selected = null)
{
	$selected = $this->getValueAttribute($name, $selected);
	$hours = TemplateHelper::getOpenHours();
	if($selected && TemplateHelper::isSerialized($selected)) {
		$selected = unserialize($selected);
		if ($selected !== false) {
		} else {
			$selected = false;
		}
	}
	
	$html = '<div class="row"><div class="form-group"><div class="col-lg-3">&nbsp;</div><div class="col-lg-4 open-hours">'.Lang::get('front/general.hours_open').'</div><div class="col-lg-4 close-hours">'.Lang::get('front/general.hours_close').'</div></div></div>';
	
	foreach($hours as $hour) {
		$am = '';
		$pm = '';
		if($selected) {
			if(isset($selected[$hour]['am'])) {
				$am = $selected[$hour]['am'];
			}
			if(isset($selected[$hour]['pm'])) {
				$pm = $selected[$hour]['pm'];
			}
		}
		
		$html .= '<div class="row">';
		$html .= '<div class="form-group">';
		$html .= '<label for="'.$hour.'" class="col-lg-3 control-label">'.Lang::get('front/general.'.$hour).'</label>';
		$html .= '<div class="col-lg-4">';
		$html .= '<input class="form-control" name="hours['.$hour.'][am]" placeholder="'.Lang::get('front/general.hours_closed').'" value="'.$am.'" type="text" />';
		$html .= '</div>';
		
		$html .= '<div class="col-lg-4">';
		$html .= '<input class="form-control" name="hours['.$hour.'][pm]" placeholder="'.Lang::get('front/general.hours_closed').'" value="'.$pm.'" type="text" />';
		$html .= '</div>';
		
		$html .= '</div>';
		$html .= '</div>';
	}
	
	return $html;
});