<?php

namespace App\Helpers;

use Lang;

class UserPermissionsList
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

            'nearme' => array(
                'mynearme' => array(
                    'create.nearme' => 'nearme'
                ),
                'newnearme' => array(
                    'create.nearme' => 'nearme'
                ),
                'savenewnearme' => array(
                    'create.nearme' => 'nearme'
                ),
                'nearmeedit' => array(
                    'update.nearme' => 'nearme'
                ),
                'updatenearme' => array(
                    'update.nearme' => 'nearme'
                )
            ),
            
            'ads' => array(
                'myads' => array(
                    'create.ads' => 'ads'
                ),
                'newad' => array(
                    'create.ads' => 'ads'
                ),
                'savead' => array(
                    'create.ads' => 'ads'
                ),
                'adedit' => array(
                    'create.ads' => 'ads'
                ),
                'updatead' => array(
                    'update.ads' => 'ads'
                )
            ),
            
            'classifieds' => array(
                'myclassifieds' => array(
                    'create.classified' => 'classifieds',
                ),
                'newclassified' => array(
                    'create.classified' => 'classifieds',
                ),
                'saveclassified' => array(
                    'create.classified' => 'classifieds',
                ),
                'classifiededit' => array(
                    'update.classified' => 'classifieds',
                ),
                'updateclassified' => array(
                    'update.classified' => 'classifieds',
                )
            )
		);
		
		return $permissions;
    }
}
