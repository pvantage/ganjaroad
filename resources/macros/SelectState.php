<?php

use App\Helpers\CategoryTree;

Form::macro('SelectState', function($name, $list = array(), $selected = null, $options = array())
{
    $selected = $this->getValueAttribute($name, $selected);
    $options['id'] = $this->getIdAttribute($name, $options);

    if (!isset($options['name'])) $options['name'] = $name;
	if(isset($options['short']) && $options['short']) $states = stateList(true);
	else $states = stateList();
	
	$html = array();
	$html[] = '<option value="">'.Lang::get('nearme/form.select-state').'</option>';
	foreach($states as $key => $value) {
		if($selected == $key) $checked = ' selected="selected"';
		else $checked = '';
		
		$html[] = '<option value="'.$key.'"'.$checked.'>'.$value.'</option>';
	}

	$options = $this->html->attributes($options);
    $list = implode('', $html);

    return "<select{$options}>{$list}</select>";
});

if (!function_exists('stateList')) {
    function stateList($short = false)
    {
		if($short) {
			$states = array(
				'AL' => 'Alabama',
				'AK' => 'Alaska',
				'AZ' => 'Arizona',
				'AR' => 'Arkansas',
				'CA' => 'California',
				'CO' => 'Colorado',
				'CT' => 'Connecticut',
				'DE' => 'Delaware',
				'FL' => 'Florida',
				'GA' => 'Georgia',
				'HI' => 'Hawaii',
				'ID' => 'Idaho',
				'IL' => 'Illinois',
				'IN' => 'Indiana',
				'IA' => 'Iowa',
				'KS' => 'Kansas',
				'KY' => 'Kentucky',
				'LA' => 'Louisiana',
				'ME' => 'Maine',
				'MD' => 'Maryland',
				'MA' => 'Massachusetts',
				'MI' => 'Michigan',
				'MN' => 'Minnesota',
				'MS' => 'Mississippi',
				'MO' => 'Missouri',
				'MT' => 'Montana',
				'NE' => 'Nebraska',
				'NV' => 'Nevada',
				'NH' => 'New Hampshire',
				'NJ' => 'New Jersey',
				'NM' => 'New Mexico',
				'NY' => 'New York',
				'NC' => 'North Carolina',
				'ND' => 'North Dakota',
				'OH' => 'Ohio',
				'OK' => 'Oklahoma',
				'OR' => 'Oregon',
				'PA' => 'Pennsylvania',
				'RI' => 'Rhode Island',
				'SC' => 'South Carolina',
				'SD' => 'South Dakota',
				'TN' => 'Tennessee',
				'TX' => 'Texas',
				'UT' => 'Utah',
				'VT' => 'Vermont',
				'VA' => 'Virginia',
				'WA' => 'Washington',
				'WV' => 'West Virginia',
				'WI' => 'Wisconsin',
				'WY' => 'Wyoming'
			);
		} else {
			$states = array(
				'Alabama' => 'Alabama',
				'Alaska' => 'Alaska',
				'Arizona' => 'Arizona',
				'Arkansas' => 'Arkansas',
				'California' => 'California',
				'Colorado' => 'Colorado',
				'Connecticut' => 'Connecticut',
				'Delaware' => 'Delaware',
				'Florida' => 'Florida',
				'Georgia' => 'Georgia',
				'Hawaii' => 'Hawaii',
				'Idaho' => 'Idaho',
				'Illinois' => 'Illinois',
				'Indiana' => 'Indiana',
				'Iowa' => 'Iowa',
				'Kansas' => 'Kansas',
				'Kentucky' => 'Kentucky',
				'Louisiana' => 'Louisiana',
				'Maine' => 'Maine',
				'Maryland' => 'Maryland',
				'Massachusetts' => 'Massachusetts',
				'Michigan' => 'Michigan',
				'Minnesota' => 'Minnesota',
				'Mississippi' => 'Mississippi',
				'Missouri' => 'Missouri',
				'Montana' => 'Montana',
				'Nebraska' => 'Nebraska',
				'Nevada' => 'Nevada',
				'New Hampshire' => 'New Hampshire',
				'New Jersey' => 'New Jersey',
				'New Mexico' => 'New Mexico',
				'New York' => 'New York',
				'North Carolina' => 'North Carolina',
				'North Dakota' => 'North Dakota',
				'Ohio' => 'Ohio',
				'Oklahoma' => 'Oklahoma',
				'Oregon' => 'Oregon',
				'Pennsylvania' => 'Pennsylvania',
				'Rhode Island' => 'Rhode Island',
				'South Carolina' => 'South Carolina',
				'South Dakota' => 'South Dakota',
				'Tennessee' => 'Tennessee',
				'Texas' => 'Texas',
				'Utah' => 'Utah',
				'Vermont' => 'Vermont',
				'Virginia' => 'Virginia',
				'Washington' => 'Washington',
				'West Virginia' => 'West Virginia',
				'Wisconsin' => 'Wisconsin',
				'Wyoming' => 'Wyoming'
			);
		}

        return $states;
    }
}