<?php

namespace Domain\Examables\Test\Policies;

use Domain\Subject\Models\Subject;
use Domain\User\Models\User;
use App\Support\Policies\BasePolicy;
use App\Domain\Examables\Test\Models\Test;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Database\Eloquent\Model;

class TestPolicy extends BasePolicy
{
    use HandlesAuthorization;

    public function __construct()
    {
        $this->recordName = 'Test';
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
     * @param  \App\Domain\Examables\Test\Models\Test  $test
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Test $test)
    {
        $userId = $this->getUserIdFromModel($test);
        return  $this->userCanViewThisModel($user, $userId);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \Domain\User\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user, Subject $subject)
    {
        return $this->userCanCreateThisModel($user, $subject->user_id);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \Domain\User\Models\User  $user
     * @param  \App\Domain\Examables\Test\Models\Test  $test
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Test $test)
    {
        $userId = $this->getUserIdFromModel($test);
        return  $this->userCanUpdateThisModel($user, $userId);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \Domain\User\Models\User  $user
     * @param  \App\Domain\Examables\Test\Models\Test  $test
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Test $test)
    {
        $userId = $this->getUserIdFromModel($test);
        return  $this->userCanDeleteThisModel($user, $userId);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \Domain\User\Models\User  $user
     * @param  \App\Domain\Examables\Test\Models\Test  $test
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Test $test)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \Domain\User\Models\User  $user
     * @param  \App\Domain\Examables\Test\Models\Test  $test
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Test $test)
    {
        //
    }

    /**
     * Determine whether the user can add a new topic to test.
     *
     * @param  \Domain\User\Models\User  $user
     * @param  \App\Domain\Examables\Test\Models\Test $test
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function addNewTopic(User $user, Test $test)
    {

        $userId = $this->getUserIdFromModel($test);

        $this->recordName = 'Topic';

        return $this->userCanDoThisActionWithThisModel(
            $user,
            $userId,
            'create'
        );
    }

    /**
     * Determine whether the user can get any topic from Test.
     *
     * @param  \Domain\User\Models\User  $user
     * @param  \App\Domain\Examables\Test\Models\Test  $test
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function getAnyTopic(User $user, Test $test)
    {
        $userId = $this->getUserIdFromModel($test);
        return  $this->userCanViewThisModel($user, $userId);
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
