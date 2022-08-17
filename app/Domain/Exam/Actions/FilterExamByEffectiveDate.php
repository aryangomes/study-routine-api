<?php

declare(strict_types=1);

namespace Domain\Exam\Actions;

use Illuminate\Database\Eloquent\Builder;

class FilterExamByEffectiveDate
{

    public static function filter(Builder $query, string $effectiveDate): Builder
    {
        return  $query->whereHas(
            'exam',
            function ($query) use ($effectiveDate) {

                $query->whereDate('effective_date', $effectiveDate);
            }
        );
    }
}
