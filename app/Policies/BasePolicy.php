<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;


/**
 * Base Class of Policies
 * 
 * @property string $recordName Name of record to translation function
 */
abstract class BasePolicy
{
    protected $recordName = 'record';

    /**
     * Verify if the User can get any of a record
     * 
     * @param User $user
     * @param string $userId Property that associates the Record with User
     * @return Response
     */
    public function userCanViewAnyThisModel(User $user): Response
    {
        $userCanDoThisActionWithThisModel =
            $this->userIsTheAuthenticatedUser($user,  'get_all');

        return $userCanDoThisActionWithThisModel;
    }

    /**
     * Verify if the User can get details of a record
     * 
     * @param User $user
     * @param string $userId Property that associates the Record with User
     * @return Response
     */
    public function userCanViewThisModel(User $user, string $userId): Response
    {
        $userCanDoThisActionWithThisModel =
            $this->userCanDoThisActionWithThisModel($user, $userId, 'view');

        return $userCanDoThisActionWithThisModel;
    }



    /**
     * Verify if the User can update a record
     * 
     * @param User $user
     * @param string $userId Property that associates the Record with User
     * @return Response
     */
    public function userCanUpdateThisModel(User $user, string $userId): Response
    {
        $userCanDoThisActionWithThisModel =
            $this->userCanDoThisActionWithThisModel($user, $userId, 'update');

        return $userCanDoThisActionWithThisModel;
    }

    /**
     * Verify if the User can delete a record
     * 
     * @param User $user
     * @param string $userId Property that associates the Record with User
     * @return Response
     */
    public function userCanDeleteThisModel(User $user, string $userId): Response
    {
        $userCanDoThisActionWithThisModel =
            $this->userCanDoThisActionWithThisModel($user, $userId, 'delete');

        return $userCanDoThisActionWithThisModel;
    }

    private function userCanDoThisActionWithThisModel(User $user, string $userId, string $actionName): Response
    {
        $userCanDoThisActionWithThisModel =  ($user->id === $userId) ?

            Response::allow() : Response::deny(__("policies.user_cannot_{$actionName}", [
                'record' => $this->recordName
            ]));

        return $userCanDoThisActionWithThisModel;
    }

    private function userIsTheAuthenticatedUser(User $user, string $actionName): Response
    {
        $userIsTheAuthenticatedUser =  ($user->id === auth()->id()) ?

            Response::allow() : Response::deny(__("policies.user_cannot_{$actionName}", [
                'record' => $this->recordName
            ]));

        return $userIsTheAuthenticatedUser;
    }
}
