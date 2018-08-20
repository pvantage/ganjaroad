<?php


Form::macro('UrlInput', function($name, $placeholder, $value = null, $http = true)
{
    $selected = $this->getValueAttribute($name, $value);
    if($http) $button = 'http://';
    else $button = 'https://';
    
    $value = '';
    if($selected) {
        $url = parse_url($selected);
        $button = $url['scheme'].'://';
        $value = $url['host'];
    }
    
	$html = '<div class="input-group url-wrapper '.$name.'-wrapper">
		<div class="input-group-btn">';
    
    if($http) {
        $html .= '<button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="'.$name.'_button">'.$button.'</button>'
                . '<div class="dropdown-menu">'
                . '<a class="dropdown-item url-type'.(($button == 'http://') ? ' selected' : '').'" href="javascript:void(0)">http://</a>'
                . '<a class="dropdown-item url-type'.(($button == 'https://') ? ' selected' : '').'" href="javascript:void(0)">https://</a>'
                . '</div>';
    } else {
        $html .= '<button type="button" class="btn btn-secondary" aria-haspopup="true" id="'.$name.'_button">'.$button.'</button>'
                . '<div class="dropdown-menu hidden" style="display: none !important">'
                . '<a class="dropdown-item url-type selected" href="javascript:void(0)">https://</a>'
                . '</div>';
    }
	
    $html .= '</div>
		<input type="text" name="temp_'.$name.'" class="form-control temp_url" aria-label="'.$placeholder.'" value="'.$value.'" />
		<input type="hidden" name="'.$name.'" id="'.$name.'" class="form-control real-value" value="'.$selected.'" />
	</div>';
		
    return $html;
});