<?php

namespace Domain\Subject\Models;

use Database\Factories\SubjectFactory;
use Domain\User\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Subject
 * 
 * @property int $ids
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
}
