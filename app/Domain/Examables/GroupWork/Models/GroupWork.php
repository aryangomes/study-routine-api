<?php

namespace App\Domain\Examables\GroupWork\Models;

use App\Domain\Examables\GroupWork\Member\Models\Member;
use Database\Factories\Examables\GroupWorkFactory;
use Domain\Exam\Models\Exam;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class GroupWork extends Exam
{
    use HasFactory;

    protected $table = 'groups_work';
    protected $fillable = ['topic', 'note'];

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return GroupWorkFactory::new();
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::deleting(function ($groupWork) {

            $groupWork->exam->delete();
        });
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
     * Get all of the members for the GroupWork
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function members(): HasMany
    {
        return $this->hasMany(Member::class, 'group_work_id', 'id');
    }
}
