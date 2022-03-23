<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\Sanctum;

/**
 * Trait with methods to know if a User can access a specific route
 * 
 */
trait UserCanAccessThisRoute
{
    private string $modelName;

    private Model $model;

    /**
     * @test
     * @dataProvider routesResourceWithPolicies
     */
    public function user_cannot_perform_this_action_because_it_is_unauthorized($route, $method)
    {
        Sanctum::actingAs(User::factory()->create());

        $methodJson = $method . "Json";

        $response = $this->$methodJson(
            route(
                $route,
                [$this->modelName => $this->model]
            )
        );


        $response->assertStatus(403);
    }

    /**
     * @test
     * @dataProvider routesResourceWithAuthentication
     */
    public function user_cannot_access_route_because_its_unauthenticated($route, $method)
    {
        $methodJson = $method . "Json";

        $response = $this->$methodJson(
            route(
                $route,
                [$this->modelName => $this->model]
            )
        );
        $response->assertUnauthorized();
    }

    protected function makeRoutesResourceWithAuthentication(): array
    {
        return [
            "User cannot view any {$this->modelName} because is not authenticated" => ["{$this->modelName}s.index", 'get'],
            "User cannot view {$this->modelName} because is not authenticated" => ["{$this->modelName}s.show", 'get'],
            "User cannot update {$this->modelName} because is not authenticated" => ["{$this->modelName}s.update", 'patch'],
            "User cannot delete {$this->modelName} because is not authenticated" => ["{$this->modelName}s.destroy", 'delete'],
        ];
    }

    protected function makeRoutesResourceWithPolicies(): array
    {

        return [
            "User cannot view any {$this->modelName}" => ["{$this->modelName}s.index", 'get'],
            "User cannot view {$this->modelName}" => ["{$this->modelName}s.show", 'get'],
            "User cannot update {$this->modelName}" => ["{$this->modelName}s.update", 'patch'],
            "User cannot delete {$this->modelName}" => ["{$this->modelName}s.destroy", 'delete'],
        ];
    }

    /**
     * Set the value of modelName
     *
     * 
     */
    protected function setModelName($modelName): void
    {
        $this->modelName = $modelName;
    }

    /**
     * Set the value of model
     *
     * 
     */
    protected function setModel($model): void
    {
        $this->model = $model;
    }

    protected function initializeModelAndModelName(string $modelName, Model $model)
    {
        $this->setModelName($modelName);

        $this->setModel($model);
    }
}
