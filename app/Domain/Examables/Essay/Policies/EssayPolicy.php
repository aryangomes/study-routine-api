<?php

namespace App\Domain\Examables\Essay\Policies;

use App\Domain\Examables\Essay\Models\Essay;
use App\Support\Policies\BasePolicy;
use Domain\User\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Database\Eloquent\Model;

class EssayPolicy extends BasePolicy
{
    use HandlesAuthorization;


    public function __construct()
    {
        $this->recordName = 'Essay';
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
     * @param  \App\Domain\Examables\Essay\Models\Essay  $essay
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Essay $essay)
    {
        $userId = $this->getUserIdFromModel($essay);
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

        return $this->userCanCreateThisModel($user, $user->id);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \Domain\User\Models\User  $user
     * @param  \App\Domain\Examables\Essay\Models\Essay  $essay
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Essay $essay)
    {
        $userId = $this->getUserIdFromModel($essay);
        return  $this->userCanUpdateThisModel($user, $userId);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \Domain\User\Models\User  $user
     * @param  \App\Domain\Examables\Essay\Models\Essay  $essay
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Essay $essay)
    {
        $userId = $this->getUserIdFromModel($essay);
        return  $this->userCanDeleteThisModel($user, $userId);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \Domain\User\Models\User  $user
     * @param  \App\Domain\Examables\Essay\Models\Essay  $essay
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Essay $essay)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \Domain\User\Models\User  $user
     * @param  \App\Domain\Examables\Essay\Models\Essay  $essay
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Essay $essay)
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
        return $model->exam->subject->user_id;
    }
}
