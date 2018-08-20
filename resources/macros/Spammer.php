<?php

Form::macro('Spammer', function($user_id, $value)
{
	$form = '<form action="#" id="setpammer_'.$user_id.'" class="spammer-form">';
	$form .= '<input type="hidden" name="user_id" value="'.$user_id.'" />';
	if($value) {
		$checked = 'checked';
	} else {
		$checked = '';
	}
	
	$form .= '<input type="checkbox" class="spammer-checkbox" value="1" '.$checked.' />';
	$form .= '<script type="text/javascript">';
	$form .= '
		jQuery("#setpammer_'.$user_id.'").submit(function( event ) {
			event.preventDefault();
		});
		
		jQuery("#setpammer_'.$user_id.' .spammer-checkbox").on("click", function() {
			jQuery.showLoader();
			
			var user_id = jQuery(this).closest(".spammer-form").find("input[name=\'user_id\']").val();
			var checked = 0;
			if(jQuery(this).is(":checked")) {
				checked = 1;
			}
			
			jQuery.ajax({
				url: "'.route('spammer/set').'",
				type: "post",
				data: {"user_id": user_id, "spammer": checked, "_token": jQuery("input[name=_token]").val()},
				success: function(data){
					if(data.success) {
						toastr.success(data.msg, "'.Lang::get('general.success').'");
					} else if(data.msg) {
						toastr.error(data.msg, "'.Lang::get('general.error').'");
					} else toastr.error(data.msg, "'.Lang::get('general.something_went_wrong').'");
				}
			}).done(function() {
				jQuery.hideLoader();
			});
		});
	';
	
	$form .= '</script>';
	$form .= '</form>';
	
	return $form;
});