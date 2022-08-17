<?php

namespace App\Domain\DailyActivity\Models;

use App\Domain\Homework\Models\Homework;
use Database\Factories\DailyActivityFactory;
use DB;
use Domain\Exam\Models\Exam;
use Domain\User\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class DailyActivity extends Model
{
    use HasFactory;


    protected $fillable = [
        'date_of_activity',
        'start_time',
        'end_time',
        'activitable_id',
        'activitable_type',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'date_of_activity' => 'datetime:Y-m-d',
        'start_time' => 'datetime:H:i:s',
        'end_time' => 'datetime:H:i:s',
    ];

    private static array $ACTIVITABLES = [
        'homework' => Homework::class,
        'exam' => Exam::class,
    ];

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return DailyActivityFactory::new();
    }

    /**
     * Get the user that owns the DailyActivity
     *
     * @return \Domain\User\Models\User
     */
    public function getUser(): User
    {
        return $this->activitable->subject->user;
    }


    /**
     * Get the value of ACTIVITABLES
     */
    public static function getActivitables()
    {

        return self::$ACTIVITABLES;
    }

    /**
     * Scope a query to only user's daily activities.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfUser($query, User $user)
    {

        $userActivitables = $query
            ->whereHas('activitable', function (Builder $query) use ($user) {
                $userSubjects = $user->subjects()->select('id')->get(['id'])->toArray();

                $query->with('activitable.subject')->whereIn('subject_id', $userSubjects);
            });


        return $userActivitables;
    }

    /**
     * Scope a query to only today activities.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeToday($query)
    {

        return $query->whereDate('date_of_activity', date('Y-m-d'));
    }

    /**
     * 
     *
     * @return MorphTo
     */
    public function activitable()
    {
        return $this->morphTo();
    }

    private function getUserOfDailyActivityFromHomework(): User
    {
        return $this->activitable->subject->user;
    }

    private function getUserOfDailyActivityFromExams(): User
    {
        return $this->activitable->exam->subject->user;
    }

    private function isAHomeworkActivity(): bool
    {
        return $this->activitable_type === self::$ACTIVITABLES['homework'];
    }

    private function isAExamActivity(): bool
    {
        return !is_null($this->activitable?->exam);
    }
}
