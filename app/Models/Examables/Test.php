<?php

namespace App\Models\Examables;

use App\Models\Exam;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * Class Test 
 * 
 * Test is a Exam
 * 
 * @property int $id
 * @property int $exam_id
 * 
 */
class Test extends Model
{
    use HasFactory;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // protected $fillable = [];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::deleting(function ($test) {

            $test->exam->delete();
        });
    }

    /**
     * Get all of the topics for the Test
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function topics(): HasMany
    {
        return $this->hasMany(Topic::class,);
    }

    /**
     * Get Exam's Test
     * @return MorphOne
     */

    public function exam(): MorphOne
    {
        return $this->morphOne(Exam::class, 'examable');
    }

    /**
     * Scope a query to only include popular users.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfUser($query, User $user)
    {
        return $query
            ->with('exam', 'exam.subject')
            ->whereHas('exam.subject', function ($query) use ($user) {
                $query->where('user_id', '=', $user->id);
            });
    }
}
