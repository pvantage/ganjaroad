<?php

use Sentinel as Sentinel;
use App\Users;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class UsersAttachNormalUsersToOtherGroupsIfNotAttached extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $basicRole = Sentinel::findRoleByName('User');
        $userNearMeRole = Sentinel::findRoleByName('User Near Me');
        $userDisplayAdsRole = Sentinel::findRoleByName('User Display Ads');
        $basicRole->users()->orderBy('id')->chunk(100, function($users) use($userNearMeRole, $userDisplayAdsRole) {
            DB::transaction(function () use($users, $userNearMeRole, $userDisplayAdsRole){
                $affectedIds = [];
                print "Updating rows on users table. Id from {$users->min('id')} to {$users->max('id')} \n";
                foreach($users as $user) {
                    if(!$user->inRole($userNearMeRole)) {
                        $userNearMeRole->users()->attach($user);
                        $affectedIds[$user->id] = true;
                    }
                    if(!$user->inRole($userDisplayAdsRole)) {
                        $userDisplayAdsRole->users()->attach($user);
                        $affectedIds[$user->id] = true;
                    }
                }
                $affectedIds = implode(' ', array_keys($affectedIds));
                print "Affected user rows ids: {$affectedIds} \n";
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
