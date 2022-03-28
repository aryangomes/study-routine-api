<?php


namespace App\Support\Traits;

use Illuminate\Database\Eloquent\Model;

trait CreateAModelFromFactory
{
    /**
     * Create and persist a Model using its Factory
     * @param Model $model
     * @param array $attributes
     * @return Model
     */
    public function createModelFromFactory(Model $model, array $attributes = []): Model
    {
        return $model::factory()->create($attributes);
    }
}
