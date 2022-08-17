<?php

namespace App\Domain\Homework\Models;

use App\Domain\DailyActivity\Models\DailyActivity;
use Database\Factories\HomeworkFactory;
use Domain\Subject\Models\Subject;
use Domain\User\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Homework extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'homeworks';


    protected $fillable = [
        'title',
        'due_date',
        'observation',
        'subject_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = ['due_date' => 'datetime:Y-m-d'];

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return HomeworkFactory::new();
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::saving(function ($homework) {
            $homework->setTitleAttribute($homework->title);
        });
    }

    /**
     * Set the default title
     *
     * @param  string  $value
     * @return void
     */
    public function setTitleAttribute($value)
    {
        if (is_null($value)) {

            $formattedDueDate = $this->due_date->format('M d, Y');

            $value = "Homework of {$this->subject->name} - {$formattedDueDate}";
        }

        $this->attributes['title'] = $value;
    }


    /**
     * Get the subject that owns the Homework
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Scope a query to only user's exams.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfUser($query, User $user)
    {
        return $query
            ->with('subject')
            ->whereHas('subject', function ($query) use ($user) {
                $query->where('user_id', '=', $user->id);
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
