<?php

namespace App\Domain\Examables\GroupWork\Member\Policies;

use App\Domain\Examables\GroupWork\Member\Models\Member;
use App\Domain\Examables\GroupWork\Models\GroupWork;
use App\Support\Policies\BasePolicy;
use Domain\User\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Illuminate\Database\Eloquent\Model;

class MemberPolicy extends BasePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \Domain\User\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \Domain\User\Models\User  $user
     * @param  \App\Domain\Examables\GroupWork\Member\Models\Member  $member
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Member $member)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \Domain\User\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \Domain\User\Models\User  $user
     * @param  \App\Domain\Examables\GroupWork\Member\Models\Member  $member
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Member $member)
    {
        //
    }

    /**
     * Determine whether the user can remove a member from the group work.
     *
     * @param  \Domain\User\Models\User  $user
     * @param  \App\Domain\Examables\GroupWork\Member\Models\Member  $member
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Member $member)
    {


        if (!$this->userIsTheOwnerOfTheGroupWork($user, $member)) {
            return
                $this->userCanDoThisActionWithThisModel(
                    $user,
                    $this->getUserIdFromModel($member),
                    'remove_member_not_allowed'
                );
        }


        return $this->allow();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \Domain\User\Models\User  $user
     * @param  \App\Domain\Examables\GroupWork\Member\Models\Member  $member
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Member $member)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \Domain\User\Models\User  $user
     * @param  \App\Domain\Examables\GroupWork\Member\Models\Member  $member
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Member $member)
    {
        //
    }

    /**
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return string
     */
    protected function getUserIdFromModel(Model $model): string
    {
        $userId = $model->user_id;
        return $userId;
    }

    protected function userCanDoThisActionWithThisModel(User $user, string $userId, string $actionName): Response
    {
        $userCanDoThisActionWithThisModel =  ($user->id === $userId) ?

            $this->allow() : $this->deny(__("policies.group_work.members.{$actionName}"));

        return $userCanDoThisActionWithThisModel;
    }

    /**
     * Verify if the user is the owner of the group work
     * @param User $user
     * @param Member $member
     * @return bool
     */
    private function userIsTheOwnerOfTheGroupWork(User $user, Member $member): bool
    {
        $userIsTheOwnerOfTheGroupWork = ($user->id === $member->groupWork->user_member_owner->id);
        return $userIsTheOwnerOfTheGroupWork;
    }
}
