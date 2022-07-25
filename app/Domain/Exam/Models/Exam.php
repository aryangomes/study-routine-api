<?php

namespace Domain\Exam\Models;

use App\Domain\DailyActivity\Models\DailyActivity;
use Carbon\Carbon;
use Database\Factories\ExamFactory;
use DateTime;
use Domain\Subject\Models\Subject;
use Domain\User\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Notifications\Notifiable;

/**
 * Class Exam
 * 
 * @property int $id
 * @property DateTime $effective_date
 * @property int $subject_id
 * 
 */
class Exam extends Model
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'effective_date',
        'subject_id',
        'examable_id',
        'examable_type',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'effective_date' => 'datetime:Y-m-d H:i:s',
    ];

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return ExamFactory::new();
    }

    /**
     * Get the subject that owns the Exam
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }


    /**
     * Get "examable" model
     * 
     * A examble model can be a
     *  - Domain\Examables\Test\Models\Test
     *  - Domain\Examables\GroupWork\Models\GroupWork
     *
     * @return MorphTo
     */
    public function examable()
    {
        return $this->morphTo();
    }

    /**
     * Scope a query to only include effective date
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOneWeekToEffectiveDate($query)
    {
        return $query->where('effective_date', Carbon::today()->addWeek());
    }

    /**
     * Scope a query to only user's exams.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfUser($query, User $user)
    {

        return
            $query->with('exam.subject')->whereHas('exam.subject', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            });
    }

    /**
     * Get Exam's daily activity
     * 
     * @return MorphOne
     */

    public function dailyActivity(): MorphOne
    {
        return $this->morphOne(DailyActivity::class, 'activitable');
    }
}
