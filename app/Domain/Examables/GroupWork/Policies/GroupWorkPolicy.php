<?php

namespace App\Domain\Examables\GroupWork\Policies;

use App\Domain\Examables\GroupWork\Models\GroupWork;
use App\Support\Policies\BasePolicy;
use Domain\User\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Database\Eloquent\Model;

class GroupWorkPolicy extends BasePolicy
{
    use HandlesAuthorization;

    public function __construct()
    {
        $this->recordName = 'Group Work';
    }

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
     * @param  \App\Domain\Examables\GroupWork\Models\GroupWork  $groupWork
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, GroupWork $groupWork)
    {
        $userId = $this->getUserIdFromModel($groupWork);
        return  $this->userCanViewThisModel($user, $userId);
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
     * @param  \App\Domain\Examables\GroupWork\Models\GroupWork  $groupWork
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, GroupWork $groupWork)
    {
        $userId = $this->getUserIdFromModel($groupWork);
        return  $this->userCanUpdateThisModel($user, $userId);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \Domain\User\Models\User  $user
     * @param  \App\Domain\Examables\GroupWork\Models\GroupWork  $groupWork
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, GroupWork $groupWork)
    {
        $userId = $this->getUserIdFromModel($groupWork);
        return  $this->userCanDeleteThisModel($user, $userId);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \Domain\User\Models\User  $user
     * @param  \App\Domain\Examables\GroupWork\Models\GroupWork  $groupWork
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, GroupWork $groupWork)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \Domain\User\Models\User  $user
     * @param  \App\Domain\Examables\GroupWork\Models\GroupWork  $groupWork
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, GroupWork $groupWork)
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
        $userId = $model->exam->subject->user_id;
        return $userId;
    }
}
