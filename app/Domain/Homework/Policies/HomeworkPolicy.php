<?php

namespace App\Domain\Homework\Policies;

use App\Domain\Homework\Models\Homework;
use App\Support\Policies\BasePolicy;
use Domain\Subject\Models\Subject;
use Domain\User\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Database\Eloquent\Model;

class HomeworkPolicy extends BasePolicy
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
     * @param  \App\Domain\Homework\Models\Homework  $homework
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Homework $homework)
    {
        return $this->userCanViewThisModel(
            $user,
            $this->getUserIdFromModel($homework)
        );
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \Domain\User\Models\User  $user
     * @param  int  $subjectId
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user, int $subjectId)
    {
        $subject = Subject::find($subjectId);
        $userId = $subject->user_id;
        return $this->userCanCreateThisModel($user, $userId);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \Domain\User\Models\User  $user
     * @param  \App\Domain\Homework\Models\Homework  $homework
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Homework $homework)
    {

        return $this->userCanUpdateThisModel(
            $user,
            $this->getUserIdFromModel($homework)
        );
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \Domain\User\Models\User  $user
     * @param  \App\Domain\Homework\Models\Homework  $homework
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Homework $homework)
    {
        return $this->userCanDeleteThisModel(
            $user,
            $this->getUserIdFromModel($homework)
        );
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \Domain\User\Models\User  $user
     * @param  \App\Domain\Homework\Models\Homework  $homework
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Homework $homework)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \Domain\User\Models\User  $user
     * @param  \App\Domain\Homework\Models\Homework  $homework
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Homework $homework)
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
        return $model->subject->user_id;
    }
}
