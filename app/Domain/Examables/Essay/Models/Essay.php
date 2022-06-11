<?php

namespace App\Domain\Examables\Essay\Models;

use Database\Factories\Examables\EssayFactory;
use Domain\Exam\Models\Exam;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Essay extends Exam
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'topic',
        'observation'
    ];

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return EssayFactory::new();
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::deleting(function ($essay) {

            $essay->exam->delete();
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
}
