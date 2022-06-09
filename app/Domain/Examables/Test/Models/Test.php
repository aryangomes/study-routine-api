<?php

namespace Domain\Examables\Test\Models;

use Database\Factories\Examables\Test\TestFactory;
use Domain\Exam\Models\Exam;
use Domain\Examables\Test\Topic\Models\Topic;
use Domain\User\Models\User;
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
class Test extends Exam
{
    use HasFactory;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // protected $fillable = [];

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return TestFactory::new();
    }

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
}
