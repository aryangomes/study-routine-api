<?php

namespace App\Support\Policies;

use Domain\User\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Illuminate\Database\Eloquent\Model;

/**
 * Base Class of Policies
 * 
 * @property string $recordName Name of record to translation function
 */
abstract class BasePolicy
{
    use HandlesAuthorization;
    protected $recordName = 'record';

    abstract protected function getUserIdFromModel(Model $model): string;

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
     * Verify if the User can get any of a record
     * 
     * @param User $user
     * @param string $userId Property that associates the Record with User
     * @return Response
     */
    public function userCanCreateThisModel(User $user, ?string $userId): Response
    {
        $userCanDoThisActionWithThisModel = false;
        if (isset($userId)) {
            $userCanDoThisActionWithThisModel = $this->userCanDoThisActionWithThisModel(
                $user,
                $userId,
                'create'
            );
        } else {
            $userCanDoThisActionWithThisModel =
                $this->userIsTheAuthenticatedUser($user,  'create');
        }
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

    protected function userCanDoThisActionWithThisModel(User $user, string $userId, string $actionName): Response
    {
        $userCanDoThisActionWithThisModel =  ($user->id === $userId) ?

            $this->allow() : $this->deny(__("policies.user.user_cannot_{$actionName}", [
                'record' => $this->recordName
            ]));

        return $userCanDoThisActionWithThisModel;
    }

    protected function userIsTheAuthenticatedUser(User $user, string $actionName): Response
    {
        $userIsTheAuthenticatedUser =  ($user->id === auth()->id()) ?

            $this->allow() : $this->deny(__("policies.user.user_cannot_{$actionName}", [
                'record' => $this->recordName
            ]));

        return $userIsTheAuthenticatedUser;
    }

    /**
     * Verify if user can do an action
     * @param bool|callable $test
     * @param string $messageDeny
     * @return Response
     */
    protected function userCanDoThisAction(bool|callable $test, string $messageDeny = "This is action is forbidden."): Response
    {
        $userCanDoThisAction =  $test ?

            $this->allow() : $this->deny($messageDeny);

        return $userCanDoThisAction;
    }
}
