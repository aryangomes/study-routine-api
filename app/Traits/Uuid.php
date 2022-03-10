<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait Uuid
{

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $modelKeyName = $model->getKeyName();

            if (empty($model->{$modelKeyName})) {
                $model->{$modelKeyName} = Str::uuid()->toString();
            }
        });
    }

    /**
     * 
     * @return boolean
     */
    public function getIncrementing(): bool
    {
        return false;
    }

    /**
     * 
     * @return string
     */
    public function getKeyType(): string
    {
        return 'string';
    }
}
