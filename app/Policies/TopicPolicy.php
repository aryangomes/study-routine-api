<?php

namespace App\Policies;

use App\Models\Examables\Test;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Response;

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
     * @param  \App\Models\Topic  $topic
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Topic $topic)
    {
        $userId = $this->getUserIdFromTopic($topic);
        return $this->userCanViewThisModel($user, $userId);
    }

    /**
     * Determine whether the user can add a new topic to test.
     *
     * @param  \App\Models\User  $user
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
     * @param  \App\Models\User  $user
     * @param  \App\Models\Topic  $topic
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Topic $topic)
    {
        $userId = $this->getUserIdFromTopic($topic);
        return $this->userCanUpdateThisModel($user, $userId);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Topic  $topic
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Topic $topic)
    {

        $userId = $this->getUserIdFromTopic($topic);
        return $this->userCanDeleteThisModel($user, $userId);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
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
     * @param  \App\Models\User  $user
     * @param  \App\Models\Topic  $topic
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Topic $topic)
    {
        //
    }

    private function getUserIdFromTopic($topic): string
    {
        $userId = $topic->test->exam->subject->user_id;
        return $userId;
    }
}
