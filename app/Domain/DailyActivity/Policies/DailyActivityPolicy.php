<?php

namespace App\Domain\DailyActivity\Policies;

use App\Domain\DailyActivity\Models\DailyActivity;
use Domain\User\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Database\Eloquent\Model;
use App\Support\Policies\BasePolicy;

class DailyActivityPolicy extends BasePolicy
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
     * @param  \App\Domain\DailyActivity\Models\DailyActivity  $dailyActivity
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, DailyActivity $dailyActivity)
    {
        return $this->userCanViewThisModel(
            $user,
            $this->getUserIdFromModel($dailyActivity)
        );
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \Domain\User\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $this->userCanCreateThisModel($user, $user->id);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \Domain\User\Models\User  $user
     * @param  \App\Domain\DailyActivity\Models\DailyActivity  $dailyActivity
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, DailyActivity $dailyActivity)
    {
        return $this->userCanUpdateThisModel(
            $user,
            $this->getUserIdFromModel($dailyActivity)
        );
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \Domain\User\Models\User  $user
     * @param  \App\Domain\DailyActivity\Models\DailyActivity  $dailyActivity
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, DailyActivity $dailyActivity)
    {
        return $this->userCanDeleteThisModel(
            $user,
            $this->getUserIdFromModel($dailyActivity)
        );
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \Domain\User\Models\User  $user
     * @param  \App\Domain\DailyActivity\Models\DailyActivity  $dailyActivity
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, DailyActivity $dailyActivity)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \Domain\User\Models\User  $user
     * @param  \App\Domain\DailyActivity\Models\DailyActivity  $dailyActivity
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, DailyActivity $dailyActivity)
    {
        //
    }

    /**
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return string
     */
    function getUserIdFromModel(Model $model): string
    {
        return $model->activitable->subject->user_id;
    }
}
