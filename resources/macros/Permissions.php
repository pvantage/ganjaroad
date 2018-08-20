<?php

use App\Helpers\AdminPermissionsList;

Form::macro('Permissions', function($role_id = null, $user_id = null)
{
	if($user_id) {
		$user = Sentinel::findById($user_id);
		$userRoles = $user->roles()->lists('id')->all();
		foreach ($userRoles as $roleId) {
			$role = Sentinel::findRoleById($roleId);
			$role->users()->detach($user);
		}
		
		$role = false;
	} else {
		$role = Sentinel::findRoleById($role_id);
		$user = false;
	}
	$permissions_list = AdminPermissionsList::getPermissions();
	
	$html = '
		<table data-toggle="table" class="permissions-table">
			<thead>
				<tr>
					<th data-field="resource">&nbsp;</th>
					<th data-field="grid">'.Lang::get('permissions/general.grid').'</th>
					<th data-field="edit">'.Lang::get('permissions/general.edit').'</th>
					<th data-field="create">'.Lang::get('permissions/general.create').'</th>
					<th data-field="delete">'.Lang::get('permissions/general.delete').'</th>
					<th data-field="delete">'.Lang::get('permissions/general.move').'</th>
                    <th data-field="delete">'.Lang::get('permissions/general.recreate').'</th>
                    <th data-field="delete">'.Lang::get('permissions/general.import').'</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>&nbsp;</th>';
	foreach($permissions_list as $key => $resource) {
		
		if($key == 'claim') {
			$html .= '</tr>
					</tbody>
				</table>
			';
			
			$html .= '
				<table data-toggle="table" class="permissions-table">
					<thead>
						<tr>
							<th data-field="resource">&nbsp;</th>
							<th data-field="claim">'.Lang::get('permissions/general.claim_claim').'</th>
							<th data-field="unclaim">'.Lang::get('permissions/general.claim_unclaim').'</th>
							<th data-field="reports">'.Lang::get('permissions/general.claim_sales').'</th>
							<th data-field="approve">'.Lang::get('permissions/general.claim_approve').'</th>
							<th data-field="unapprove">'.Lang::get('permissions/general.claim_unapprove').'</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>&nbsp;</th>';
		} elseif($key == 'sales') {
            $html .= '</tr>
					</tbody>
				</table>
			';
			
			$html .= '
				<table data-toggle="table" class="permissions-table">
					<thead>
						<tr>
                            <th data-field="resource">&nbsp;</th>
                            <th data-field="reports">'.Lang::get('permissions/general.sales_reps_reports').'</th>
                            <th data-field="reports">'.Lang::get('permissions/general.sales_users_reports').'</th>
							<th data-field="approval">'.Lang::get('permissions/general.sales_approve').'</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>&nbsp;</th>';
        } elseif($key == 'reports') {
            $html .= '</tr>
					</tbody>
				</table>
			';
			
			$html .= '
				<table data-toggle="table" class="permissions-table">
					<thead>
						<tr>
                            <th data-field="resource">&nbsp;</th>
                            <th data-field="refunds_list">'.Lang::get('permissions/general.reports_ads').'</th>
							<th data-field="ad_transaction_history">'.Lang::get('permissions/general.reports_nearme').'</th>
							<th data-field="refund">'.Lang::get('permissions/general.reports_classified').'</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>&nbsp;</th>';
        } elseif($key == 'refunds') {
            $html .= '</tr>
					</tbody>
				</table>
			';
			
			$html .= '
				<table data-toggle="table" class="permissions-table">
					<thead>
						<tr>
                            <th data-field="resource">&nbsp;</th>
                            <th data-field="refunds_list">'.Lang::get('permissions/general.refunds_list').'</th>
							<th data-field="ad_transaction_history" style="width: 200px">'.Lang::get('permissions/general.ad_transaction_history').'</th>
							<th data-field="refund">'.Lang::get('permissions/general.refunds_refund').'</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>&nbsp;</th>';
        } elseif($key == 'cache') {
			$html .= '</tr>
					</tbody>
				</table>
			';
			
			$html .= '
				<table data-toggle="table" class="permissions-table">
					<thead>
						<tr>
                            <th data-field="resource">&nbsp;</th>
                            <th data-field="refunds_list">'.Lang::get('permissions/general.cache_clear').'</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>&nbsp;</th>';
		}
		
		$html .= '<tr>';
		$html .= '<td>'.Lang::get('permissions/general.'.$key).'</td>';
		
		foreach($resource as $permission => $name) {
			if($role) {
				$has_access = $role->hasAccess($permission);
				$checked = ($has_access) ? 'checked="checked"' : '';
				$class = ($has_access) ? ' active' : '';
			} elseif($user) {
				$has_access = $user->hasAccess($permission);
				$checked = ($has_access) ? 'checked="checked"' : '';
				$class = ($has_access) ? ' active' : '';
			} else {
				$checked = '';
				$class = '';
			}
			
			$html .= '<td>
				<div class="btn-group" data-toggle="buttons">
					<label class="btn'.$class.'">
						<input type="checkbox" name="permissions['.$permission.']" '.$checked.' value="1" autocomplete="off">
						<span class="glyphicon glyphicon-ok"></span>
						<span class="glyphicon glyphicon-remove"></span>
					</label>
				</div>
			</td>';
		}
		$html .= '</tr>';
	}
	
	if($user_id) {
		foreach ($userRoles as $roleId) {
			$role = Sentinel::findRoleById($roleId);
			$role->users()->attach($user);
		}
	} 
	
	$html .= '</tr>
			</tbody>
		</table>
	';
	
	return $html;
	
});