<?php

use App\NearmeItems;
use App\NearmeItemsCategory;

Form::macro('NearmeItems', function($name, $nearme = null)
{
	$nearme_items = new NearmeItems;
	$fileds = $nearme_items->getFrontendFillable();

	$html = '<table class="table table-bordered" id="nearmeitems">';
	$html .= getHead($fileds);
	
	if($nearme) $selected = $nearme->items()->orderBy('category_id')->orderBy('name')->get()->toArray();
	else $selected = false;
	
	if(!$selected) $selected = Form::old($name);
	if(!$selected) $selected = false;

	$i = 0;
	if($selected) {
		$html .= '<tbody>';
		foreach($selected as $sel) {
			$html .= '<tr><td></td>';
			
			foreach($fileds as $field) {
				if($field == 'nearme') continue;
				$html .= itemWrapper($field, $i, $sel, $nearme);	
			}
			$html .= '<td class="remove"><a href="javascript:void(0)" class="remove_row">'.Lang::get('nearmeitemscategory/form.remove_item').'</a></td>';
			$html .= '</tr>';
			++$i;
		}
		$html .= '</tbody>';
	} else {
		$html .= '<tbody></tbody>';
	}
	$html .= getFoot();
	
	return $html;
});

Form::macro('NearmeBlankItem', function()
{
	$nearme_items = new NearmeItems;
	$fileds = $nearme_items->getFrontendFillable();
	
	$html = '<tr><td></td>';
	foreach($fileds as $field) {
		if($field == 'nearme') continue;
		$html .= itemWrapper($field, 0, null, null, true);	
	}
	$html .= '<td class="remove"><a href="javascript:void(0)" class="remove_row">'.Lang::get('nearmeitemscategory/form.remove_item').'</a></td>';
	$html .= '</tr>';
	
	return $html;
});
if (!function_exists('getHead')) {
    function getHead($fileds)
    {
        $html = '<thead><tr class="filters"><th></th>';
        foreach($fileds as $field) {
            if($field == 'nearme') continue;
            $html .= '<th class="row-'.$field.'">'.Lang::get('nearmeitemscategory/form.table_'.$field).'</th>';
        }
        $html .= '<th>'.Lang::get('nearmeitemscategory/form.actions').'</th></tr></thead>';

        return $html;
    }
}
if (!function_exists('getFoot')) {
    function getFoot()
    {
        $html = '</tr></tbody>';
        $html .= '<tfoot></tfoot>';
        $html .= '</table>';

        $html .= '<div class="add-row"><a href="javascript:void(0)" id="addtablerow">'.Lang::get('nearmeitemscategory/form.add_another_item').'</a></div>';

        return $html;
    }
}
if (!function_exists('itemWrapper')) {
    function itemWrapper($field = null, $i = 0, $selected = null, $nearme = null, $blank = false)
    {	
        if(isset($selected[$field])) $val_selected = $selected[$field];
        else $val_selected = '';

        $html = '<td data-title="'.Lang::get('nearmeitemscategory/form.table_'.$field).'" data-search="'.$val_selected.'" data-order="'.$val_selected.'" class="row-'.$field.'"><div class="form-group">';

        switch($field) {
            case('category_id'):
                $html .= '<select name="nearmeitems['.$i.'][category_id]" class="form-control select2">';
                $options = NearmeItemsCategory::getActive()->get();
                foreach($options as $option) {
                    if (Lang::has('nearmeitemscategory/options', $option->title)) $text = Lang::get('nearmeitemscategory/options.'.$option->title);
                    else $text = $option->title;

                    if($val_selected == $option->id) $selected_text = ' selected';
                    else $selected_text = '';

                    $html .= '<option value="'.$option->id.'"'.$selected_text.'>'.$text.'</option>';
                }
                $html .= '</select>';
                break;
            case('published'):
                $html .= '<select name="nearmeitems['.$i.'][published]" class="form-control select2">';
                $options = array(
                    '0' => Lang::get('general.no'),
                    '1' => Lang::get('general.yes')
                );

                foreach($options as $key => $option) {
                    if($val_selected == $key) $selected_text = ' selected';
                    else $selected_text = '';

                    $html .= '<option value="'.$key.'"'.$selected_text.'>'.$option.'</option>';
                }
                $html .= '</select>';
                break;

            case('image'):
                if(!$blank) {
                    $old = Form::old('old_nearmeitems['.$i.'][image]');
                    if(!$old) $old = Form::old('old_nearmeitems['.$i.'][old_image]');

                    if($nearme && $nearme->id && $val_selected && $val_selected != '') {
                        $img = '<img src="'.asset('uploads/nearme/'.$nearme->id.'/'.$val_selected).'" />';
                    } elseif($old) {
                        $img = '<img src="'.asset('uploads/temp/'.$old).'" />';
                        $val_selected = $old;
                    } else {
                        $img = '';
                    }
                } else {
                    $img = '';
                    $val_selected = '';
                }

                $html .= '<div class="fileinput fileinput-new" data-provides="fileinput"><div class="fileinput-new thumbnail">'.$img.'</div><div class="fileinput-preview fileinput-exists thumbnail"></div><div><span class="btn btn-primary btn-file"><span class="fileinput-new">'.Lang::get('front/general.select_image').'</span><span class="fileinput-exists">'.Lang::get('front/general.change').'</span><input type="file" name="nearmeitems['.$i.'][image]" /><input type="hidden" name="nearmeitems['.$i.'][old_image]" value="'.$val_selected.'" /></span><span class="btn btn-primary fileinput-exists" data-dismiss="fileinput">'.Lang::get('front/general.remove').'</span></div></div>';
                break;

            case('description'):
                $html .= '<textarea class="form-control" name="nearmeitems['.$i.']['.$field.']" placeholder="'.Lang::get('nearmeitemscategory/form.table_'.$field).'">'.$val_selected.'</textarea>';
                break;

            default:
                $html .= '<input class="form-control" name="nearmeitems['.$i.']['.$field.']" placeholder="'.Lang::get('nearmeitemscategory/form.table_'.$field).'" value="'.$val_selected.'" type="text" />';
        }
        $html .= '</div></td>';

        return $html;
    }
}