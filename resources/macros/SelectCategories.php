<?php

use App\Helpers\CategoryTree;

Form::macro('SelectCategories', function($name, $list = array(), $selected = null, $options = array(), $skip = 0)
{
    $selected = $this->getValueAttribute($name, $selected);
    $options['id'] = $this->getIdAttribute($name, $options);

    if ( ! isset($options['name'])) $options['name'] = $name;

	$main_categories = $sub_categories = array();
    foreach ($list as $list_el) {
		if((int)$list_el['parent'] == 0) $main_categories[$list_el['id']] = $list_el;
		else $sub_categories[$list_el['parent']][] = $list_el;
	}

    if($skip !== 0) {
        $first = array(
            'value' => 0,
        );
		
		if(CategoryTree::getSelectedValue(0, (int)$selected)) $first['selected'] = 'selected';
		
        $html = array('<option'.CategoryTree::attributes($first).'>'.Lang::get('classifiedcategory/form.noparent').'</option>');
    } else $html = array();
	
    foreach ($main_categories as $main) {
		if($main->id == $skip) continue;
		$items = CategoryTree::generateOptionsSubItems($main, $sub_categories, $selected, array(), 0, $skip);
		$html = array_merge((array)$html, (array)$items);
    }
	
    $options = $this->html->attributes($options);

    $list = implode('', $html);

    return "<select{$options}>{$list}</select>";
});