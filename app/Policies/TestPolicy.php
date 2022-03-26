<?php

namespace App\Policies;

use App\Models\Examables\Test;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

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
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Examables\Test  $test
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Test $test)
    {
        $userId = $this->getUserIdFromTest($test);
        return  $this->userCanViewThisModel($user, $userId);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user, Subject $subject)
    {
        return $this->userCanCreateThisModel($user, $subject->user_id);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Examables\Test  $test
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Test $test)
    {
        $userId = $this->getUserIdFromTest($test);
        return  $this->userCanUpdateThisModel($user, $userId);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Examables\Test  $test
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Test $test)
    {
        $userId = $this->getUserIdFromTest($test);
        return  $this->userCanDeleteThisModel($user, $userId);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Examables\Test  $test
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Test $test)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Examables\Test  $test
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Test $test)
    {
        //
    }

    /**
     * Determine whether the user can add a new topic to test.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Examables\Test $test
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function addNewTopic(User $user, Test $test)
    {

        $userId = $this->getUserIdFromTest($test);

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
     * @param  \App\Models\User  $user
     * @param  \App\Models\Examables\Test  $test
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function getAnyTopic(User $user, Test $test)
    {
        $userId = $this->getUserIdFromTest($test);
        return  $this->userCanViewThisModel($user, $userId);
    }

    private function getUserIdFromTest($test): string
    {
        $userId = $test->exam->subject->user_id;
        return $userId;
    }
}
