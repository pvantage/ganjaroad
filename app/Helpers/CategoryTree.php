<?php

namespace App\Helpers;

class CategoryTree
{
	public static function generateListTree($categories) {
		$main_categories = $sub_categories = array();
        foreach ($categories as $category) {
            if((int)$category->parent == 0) $main_categories[$category->id] = $category;
            else $sub_categories[$category->parent][] = $category;
        }

        $items = array();
        foreach ($main_categories as $main) {
            $items = CategoryTree::generateSubItems($main, $sub_categories, 0, $items);
        }

        return $items;
	}

    public static function generateSubItems($current, $sub, $level, &$items = array())
    {
        $current->name = CategoryTree::createTitle($current->title, $level);
        $items[] = $current;
        ++$level;

        if(isset($sub[$current->id])) {
            foreach($sub[$current->id] as $child) {
                $child_item = CategoryTree::generateSubItems($child, $sub, $level, $items);
            }
        }

        return $items;
    }
    
    public static function generateOptionsSubItems($current, $sub, $selected, $html = array(), $level = 0, $skip = 0)
    {
        $option_attr = array('value' => $current['id']);
        $selected_option = CategoryTree::getSelectedValue($current['id'], $selected);
        if($selected_option) $option_attr['selected'] = 'selected';
        $title = CategoryTree::createTitle($current['title'], $level);

        $html = array('<option'.CategoryTree::attributes($option_attr).'>'.$title.'</option>');
        ++$level;

        if(isset($sub[$current['id']])) {
            foreach($sub[$current['id']] as $child) {
                if($child->id == $skip) continue;
                $child_array = CategoryTree::generateOptionsSubItems($child, $sub, $selected, $html, $level, $skip);
                $html = array_merge($html, $child_array);
            }
        }

        return $html;
    }
    
    public static function getSelectedValue($value, $selected)
    {
        if (is_array($selected)) {
            return in_array($value, $selected) ? true : false;
        }

        return ((string) $value == (string) $selected) ? true : false;
    }
    
    public static function attributes($attributes)
    {
        $html = [];

        foreach ((array) $attributes as $key => $value) {
            $element = CategoryTree::attributeElement($key, $value);
            if (! is_null($element)) {
                $html[] = $element;
            }
        }

        return count($html) > 0 ? ' ' . implode(' ', $html) : '';
    }

    public static function attributeElement($key, $value)
    {
        if (is_numeric($key)) {
            $key = $value;
        }

        if (! is_null($value)) {
            return $key . '="' . e($value) . '"';
        }
    }

    public static function createTitle($title, $level)
    {
        $before = '';
        for($i = 0; $i < $level; ++$i) {
            $before  .= '-';
        }

        if(strlen($before) > 0) {
            $before  .= '>' . ' ';
        }
        
        return $before . strip_tags($title);
    }
}
