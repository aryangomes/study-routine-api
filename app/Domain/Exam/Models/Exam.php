<?php

namespace Domain\Exam\Models;

use Database\Factories\ExamFactory;
use DateTime;
use Domain\Subject\Models\Subject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

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
    use HasFactory;

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
        'effective_date' => 'datetime:Y-m-d H:m:i',
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
     *
     * @return MorphTo
     */
    public function examable()
    {
        return $this->morphTo();
    }
}
