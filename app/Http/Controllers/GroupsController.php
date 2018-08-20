<?php namespace App\Http\Controllers;

use App\Http\Requests\GroupRequest;
use Lang;
use Redirect;
use Sentinel;
use View;

class GroupsController extends WeedController
{
    /**
     * Show a list of all the groups.
     *
     * @return View
     */
    public function index()
    {
        // Grab all the groups
        $roles = Sentinel::getRoleRepository()->all();

        // Show the page
        return View('admin/groups/index', compact('roles'));
    }

    /**
     * Group create.
     *
     * @return View
     */
    public function create()
    {
        // Show the page
        return View('admin/groups/create');
    }

    /**
     * Group create form processing.
     *
     * @return Redirect
     */
    public function store(GroupRequest $request)
    {
        
        if ($role = Sentinel::getRoleRepository()->createModel()->create([
            'name' => $request->get('name'),
            'slug' => str_slug($request->get('name'))
        ])
        ) {
            $role->permissions = [];
            if($permissions = $request->get('permissions')) {
                foreach($permissions as $key => $value){
                    $role->addPermission($key);
                }
            }
            $role->save();
        
            // Redirect to the new group page
            return Redirect::route('groups')->with('success', Lang::get('groups/message.success.create'));
        }

        // Redirect to the group create page
        return Redirect::route('create/group')->withInput()->with('error', Lang::get('groups/message.error.create'));
    }

    /**
     * Group update.
     *
     * @param  int $id
     * @return View
     */
    public function edit($id = null)
    {
        try {
            // Get the group information
            $role = Sentinel::findRoleById($id);

        } catch (GroupNotFoundException $e) {
            // Redirect to the groups management page
            return Redirect::route('groups')->with('error', Lang::get('groups/message.group_not_found', compact('id')));
        }

        // Show the page
        return View('admin/groups/edit', compact('role'));
    }

    /**
     * Group update form processing page.
     *
     * @param  int $id
     * @return Redirect
     */
    public function update($id = null, GroupRequest $request)
    {
        try {
            // Get the group information
            $group = Sentinel::findRoleById($id);
        } catch (GroupNotFoundException $e) {
            // Redirect to the groups management page
            return Rediret::route('groups')->with('error', Lang::get('groups/message.group_not_found', compact('id')));
        }

        $group->permissions = [];
        if($permissions = $request->get('permissions')) {
            foreach($permissions as $key => $value){
                $group->addPermission($key);
            }
        }
        
        // Update the group data
        $group->name = $request->get('name');

        // Was the group updated?
        if ($group->save()) {
            // Redirect to the group page
            return Redirect::route('groups')->with('success', Lang::get('groups/message.success.update'));
        } else {
            // Redirect to the group page
            return Redirect::route('update/group', $id)->with('error', Lang::get('groups/message.error.update'));
        }

    }

    /**
     * Delete confirmation for the given group.
     *
     * @param  int $id
     * @return View
     */
    public function getModalDelete($id = null)
    {
        $model = 'groups';
        $confirm_route = $error = null;
        try {
            // Get group information
            $role = Sentinel::findRoleById($id);


            $confirm_route = route('delete/group', ['id' => $role->id]);
            return View('admin/layouts/modal_confirmation', compact('error', 'model', 'confirm_route'));
        } catch (GroupNotFoundException $e) {

            $error = Lang::get('admin/groups/message.group_not_found', compact('id'));
            return View('admin/layouts/modal_confirmation', compact('error', 'model', 'confirm_route'));
        }
    }

    /**
     * Delete the given group.
     *
     * @param  int $id
     * @return Redirect
     */
    public function destroy($id = null)
    {
        try {
            // Get group information
            $role = Sentinel::findRoleById($id);

            // Delete the group
            $role->delete();

            // Redirect to the group management page
            return Redirect::route('groups')->with('success', Lang::get('groups/message.success.delete'));
        } catch (GroupNotFoundException $e) {
            // Redirect to the group management page
            return Redirect::route('groups')->with('error', Lang::get('groups/message.group_not_found', compact('id')));
        }
    }

}
