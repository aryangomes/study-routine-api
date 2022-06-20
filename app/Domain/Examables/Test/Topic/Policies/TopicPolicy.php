<?php

namespace Domain\Examables\Test\Topic\Policies;

use App\Support\Policies\BasePolicy;
use App\Domain\Examables\Test\Models\Test;
use Domain\Examables\Test\Topic\Models\Topic;
use Domain\User\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Database\Eloquent\Model;

class TopicPolicy extends BasePolicy
{
    use HandlesAuthorization;


    public function __construct()
    {
        $this->recordName = 'Topic';
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
     * @param  \App\Models\Topic  $topic
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Topic $topic)
    {
        $userId = $this->getUserIdFromModel($topic);
        return $this->userCanViewThisModel($user, $userId);
    }

    /**
     * Determine whether the user can add a new topic to test.
     *
     * @param  \Domain\User\Models\User  $user
     * @param  \App\Models\Examables\Test $test
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user, Test $test)
    {
        return true;
        $userId = $test->exam->subject->user_id;

        return $this->userCanDoThisActionWithThisModel(
            $user,
            $userId,
            'create'
        );
    }


    /**
     * Determine whether the user can update the model.
     *
     * @param  \Domain\User\Models\User  $user
     * @param  \App\Models\Topic  $topic
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Topic $topic)
    {
        $userId = $this->getUserIdFromModel($topic);
        return $this->userCanUpdateThisModel($user, $userId);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \Domain\User\Models\User  $user
     * @param  \App\Models\Topic  $topic
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Topic $topic)
    {

        $userId = $this->getUserIdFromModel($topic);
        return $this->userCanDeleteThisModel($user, $userId);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \Domain\User\Models\User  $user
     * @param  \App\Models\Topic  $topic
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Topic $topic)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \Domain\User\Models\User  $user
     * @param  \App\Models\Topic  $topic
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Topic $topic)
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
        $userId = $model->test->exam->subject->user_id;
        return $userId;
    }
}
