<?php

namespace Domain\Subject\Models;

use App\Domain\Homework\Models\Homework;
use Database\Factories\SubjectFactory;
use Domain\Exam\Models\Exam;
use Domain\User\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Subject
 * 
 * @property int $id
 * @property string $name 
 * @property int $user_id 
 */
class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'user_id',
    ];

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return SubjectFactory::new();
    }

    /**
     * Get the user that owns the Subject
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all of the exams for the Subject
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function exams(): HasMany
    {
        return $this->hasMany(Exam::class);
    }

    /**
     * Get all of the homeworks for the Subject
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function homeworks(): HasMany
    {
        return $this->hasMany(Homework::class);
    }
}
