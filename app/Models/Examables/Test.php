<?php

namespace App\Models\Examables;

use App\Models\Exam;
use App\Models\Topic;
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
