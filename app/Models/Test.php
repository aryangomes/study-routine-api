<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
    protected $fillable = [
        'exam_id',
    ];

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
     * Get the exam that owns the Test
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class, 'exam_id', 'id');
    }
}
