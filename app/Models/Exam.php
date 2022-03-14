<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Exam
 * 
 * @property int $id
 * @property DateTime $effectiveDate
 * @property int $subject_id
 * 
 */
final class Exam extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'effectiveDate'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'effectiveDate' => 'datetime:Y-m-d H:m:i',
    ];

    /**
     * Get the subject that owns the Exam
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }
}
