<?php

return array(

    'classifiedschema_exists'	=> 'Category already exists!',
    'classifiedschema_not_found'=> 'Category [:id] does not exist.',

    'success' => array(
        'create'    => 'Category was successfully created.',
        'update'    => 'Category was successfully updated.',
        'delete'    => 'Category was successfully deleted.',
        'recreated' => 'Categories successfully recreated.',
        'removed' 	=> 'Successfully removed categories not connected with schema.',
        'changed_pricing' => 'Successfully Re-populate pricing.',
    ),

    'error' => array(
        'create'    => 'There was an issue creating the Category. Please try again.',
        'update'    => 'There was an issue updating the Category. Please try again.',
        'delete'    => 'There was an issue deleting the Category. Please try again.',
        'recreate'  => 'Schema parent was not found for this category',
    ),

);
