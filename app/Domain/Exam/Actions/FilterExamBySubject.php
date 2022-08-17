<?php

declare(strict_types=1);

namespace Domain\Exam\Actions;

use Illuminate\Database\Eloquent\Builder;

class FilterExamBySubject
{

    public static function filter(Builder $query, int|string $subjectId): Builder
    {
        return  $query->whereHas(
            'exam',
            function ($query) use ($subjectId) {

                $query->with('exam.subject')->where('subject_id', $subjectId);
            }
        );
    }
}
