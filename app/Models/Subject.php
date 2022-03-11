<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Subject
 */
class Subject extends Model
{
    use HasFactory;

    /**
     *
     * @property int $ids
     * @property string $name 
     * @property int $user_id 
     */

    protected $fillable = [
        'name',
        'user_id',
    ];

    /**
     * Get the user that owns the Subject
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
