<?php


namespace App\Support\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

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

    /**
     * Create and persist a Model using its Factory
     * @param Model $model
     * @param array $attributes
     * @return Collection
     */
    public function createModelsFromFactory(Model $model, array $attributes = [], $quantity = 1): Collection
    {
        return $model::factory()->count($quantity)->create($attributes);
    }
}
