<?php

namespace App\Helpers;

use Lang;

class AdminPermissionsList
{
	/*
		$key => resource name
			0 => 'index'	//empty if not required
			1 => 'edit'
			2 => 'create'
			3 => 'change position'
	*/
	public static function getPermissions() {
		$permissions = array(
			'users' => array(
				'admin.users.index' => trans('permissions/general.users_list'),
				'admin.users.edit' => trans('permissions/general.users_edit'),
				'admin.users.create' => trans('permissions/general.users_create'),
				'delete.user' => trans('permissions/general.users_delate'),
			),
			'groups' => array(
				'groups' => trans('permissions/general.group_list'),
				'edit.group' => trans('permissions/general.group_edit'),
				'create.group' => trans('permissions/general.group_create'),
				'delete.group' => trans('permissions/general.group_delate'),
			),
			'blocks' => array(
				'blocks' => trans('permissions/general.blocks_list'),
				'edit.block' => trans('permissions/general.block_edit'),
				'create.block' => trans('permissions/general.block_create'),
				'delete.block' => trans('permissions/general.block_delate'),
			),
			'pages' => array(
				'pages' => trans('permissions/general.pages_list'),
				'edit.page' => trans('permissions/general.page_edit'),
				'create.page' => trans('permissions/general.page_create'),
				'delete.page' => trans('permissions/general.page_delate'),
			),
            'menu' => array(
				'menu' => trans('permissions/general.menu_list'),
				'menu.data.quickrename' => trans('permissions/general.menu_edit'),
				'menu.data.quickcreate' => trans('permissions/general.menu_create'),
				'menu.data.quickremove' => trans('permissions/general.menu_delate'),
				'menu.data.move' => trans('permissions/general.menu_move'),
			),
			'classifiedschema' => array(
				'classifiedschema' => trans('permissions/general.classifiedschema_list'),
				'classifiedschema.data.quickrename' => trans('permissions/general.classifiedschema_edit'),
				'classifiedschema.data.quickcreate' => trans('permissions/general.classifiedschema_create'),
				'classifiedschema.data.quickremove' => trans('permissions/general.classifiedschema_delate'),
				'classifiedschema.data.move' => trans('permissions/general.classifiedschema_move'),
			),
			'classifiedcategories' => array(
				'classifiedcategories' => trans('permissions/general.classifiedcategories_list'),
				'update.classifiedcategories' => trans('permissions/general.classifiedcategories_edit'),
				'create.classifiedcategories' => trans('permissions/general.classifiedcategories_create'),
				'delete.classifiedcategories' => trans('permissions/general.classifiedcategories_delate'),
				'classifiedcategories.data.move' => trans('permissions/general.classifiedcategories_move'),
                'classifiedcategories.recreate' => trans('permissions/general.recreateclassifiedcategories'),
			),
			'classifieds' => array(
				'classifieds' => trans('permissions/general.classifieds_list'),
				'update.classified' => trans('permissions/general.classifieds_edit'),
				'create.classified' => trans('permissions/general.classifieds_create'),
				'delete.classified' => trans('permissions/general.classifieds_delate'),
			),
			'nearmecategory' => array(
				'nearmecategories' => trans('permissions/general.nearmecategory_list'),
				'update.nearmecategory' => trans('permissions/general.nearmecategory_edit'),
				'create.nearmecategory' => trans('permissions/general.nearmecategory_create'),
				'delete.nearmecategory' => trans('permissions/general.nearmecategory_delate'),
			),
			'nearmeitemscategory' => array(
				'nearmeitemscategory' => trans('permissions/general.nearmeitemscategory_list'),
				'update.nearmeitemscategory' => trans('permissions/general.nearmeitemscategory_edit'),
				'create.nearmeitemscategory' => trans('permissions/general.nearmeitemscategory_create'),
				'delete.nearmeitemscategory' => trans('permissions/general.nearmeitemscategory_delate'),
			),
            'nearme' => array(
				'nearmes' => trans('permissions/general.nearme_list'),
				'update.nearme' => trans('permissions/general.nearme_edit'),
				'create.nearme' => trans('permissions/general.nearme_create'),
				'delete.nearme' => trans('permissions/general.nearme_delate'),
			),
			'ads' => array(
				'ads' => trans('permissions/general.ads_list'),
				'update.ads' => trans('permissions/general.ads_edit'),
				'create.ads' => trans('permissions/general.ads_create'),
				'delete.ads' => trans('permissions/general.ads_delate'),
			),
			'adscompanies' => array(
				'adscompanies' => trans('permissions/general.adscompanies_list'),
				'update.adscompanies' => trans('permissions/general.adscompanies_edit'),
				'create.adscompanies' => trans('permissions/general.adscompanies_create'),
				'delete.adscompanies' => trans('permissions/general.adscompanies_delate'),
			),
			'adspositions' => array(
				'adspositions' => trans('permissions/general.adspositions_list'),
				'update.adspositions' => trans('permissions/general.adspositions_edit'),
				'create.adspositions' => trans('permissions/general.adspositions_create'),
				'delete.adspositions' => trans('permissions/general.adspositions_delate'),
			),
			'blogcategory' => array(
				'blogcategories' => trans('permissions/general.blogcategories_list'),
				'update.blogcategory' => trans('permissions/general.blogcategory_edit'),
				'create.blogcategory' => trans('permissions/general.blogcategory_create'),
				'delete.blogcategory' => trans('permissions/general.blogcategory_delate'),
			),
			'blog' => array(
				'blogs' => trans('permissions/general.blogs_list'),
				'update.blog' => trans('permissions/general.blog_edit'),
				'create.blog' => trans('permissions/general.blog_create'),
				'delete.blog' => trans('permissions/general.blog_delate'),
			),
            'reporteditems' => array(
				'reporteditems' => trans('permissions/general.reporteditems_list'),
				'update.reporteditems' => trans('permissions/general.reporteditems_edit'),
			),
            'issues' => array(
				'issues' => trans('permissions/general.issues_list'),
				'update.issues' => trans('permissions/general.issues_edit'),
			),
            'plans' => array(
				'plans' => trans('permissions/general.plans_list'),
				'update.plan' => trans('permissions/general.plan_edit'),
				'create.plan' => trans('permissions/general.plan_create'),
				'delete.plan' => trans('permissions/general.plan_delate'),
			),
            'payments' => array(
				'payments' => trans('permissions/general.payments_list'),
			),
            'settings' => array(
				'settings' => trans('permissions/general.settings_list'),
                'update.settings' => trans('permissions/general.settings_edit'),
			),
            'coupons' => array(
                'coupons' => trans('permissions/general.coupons_list'),
				'update.coupons' => trans('permissions/general.coupons_edit'),
				'create.coupons' => trans('permissions/general.coupons_create'),
				'delete.coupons' => trans('permissions/general.coupons_delate'),
            ),
            'claim' => array(
                'claim' => trans('permissions/general.claim_claim'),
                'unclaim' => trans('permissions/general.claim_unclaim'),
                'claim.sales' => trans('permissions/general.claim_sales'),
                'claim.approve' => trans('permissions/general.claim_approve'),
                'claim.unapprove' => trans('permissions/general.claim_unapprove'),
            ),
            'sales' => array(
                'sales.reps' => trans('permissions/general.sales_reps'),
                'sales.users' => trans('permissions/general.sales_users'),
                'sales.approve' => trans('permissions/general.sales_approve'),
            ),
            'reports' => array(
                'reports.ads' => trans('permissions/general.reports_ads'),
                'reports.nearme' => trans('permissions/general.reports_nearme'),
                'reports.classified' => trans('permissions/general.reports_classified'),
            ),
            'refunds' => array(
                'refunds' => trans('permissions/general.refunds_list'),
                'ad_transaction_history' => trans('permissions/general.ad_transaction_history'),
                'refund' => trans('permissions/general.refunds_refund'),
            ),
            'cache' => array(
                'cache.clear' => trans('permissions/general.settings_cache_clear'),
            ),
		);
		
		return $permissions;
    }
}
