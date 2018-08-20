<?php

Form::macro('SelectWithAttribute', function($name, $list = array(), $selected = null, $options = array())
{
    $selected = $this->getValueAttribute($name, $selected);

    $options['id'] = $this->getIdAttribute($name, $options);

    if ( ! isset($options['name'])) $options['name'] = $name;

    $html = array();

    foreach ($list as $list_el)
    {
        $selected = $this->getSelectedValue($list_el['value'], $selected);
        $option_attr = array('value' => e($list_el['value']), 'selected' => $selected, 'parent' => $list_el['parent']);
        $html[] = '<option'.$this->html->attributes($option_attr).'>'.e($list_el['display']).'</option>';
    }

    $options = $this->html->attributes($options);

    $list = implode('', $html);

    return "<select{$options}>{$list}</select>";
});