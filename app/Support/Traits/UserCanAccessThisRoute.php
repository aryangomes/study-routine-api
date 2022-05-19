<?php

namespace App\Support\Traits;

use Domain\User\Models\User;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\Sanctum;

/**
 * Trait with methods to know if a User can access a specific route
 * 
 */
trait UserCanAccessThisRoute
{
    private string $modelName;
    private ?string $routeName = null;
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
     * @dataProvider routesResourceWithEmailVerified
     */
    public function user_cannot_perform_this_action_because_it_is_not_verified($route, $method)
    {
        Sanctum::actingAs(User::factory()->unverified()->create());

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

    public function routesResourceWithAuthentication(): array
    {
        return [];
    }
    public function routesResourceWithPolicies(): array
    {
        return [];
    }

    public function routesResourceWithEmailVerified(): array
    {
        return [];
    }


    protected function makeRoutesResourceWithAuthentication(string $modelName, string $routeName): array
    {
        return [
            "User cannot view any {$modelName} because is not authenticated" => ["{$routeName}.index", 'get'],
            "User cannot view {$modelName} because is not authenticated" => ["{$routeName}.show", 'get'],
            "User cannot update {$modelName} because is not authenticated" => ["{$routeName}.update", 'patch'],
            "User cannot delete {$modelName} because is not authenticated" => ["{$routeName}.destroy", 'delete'],
        ];
    }

    protected function makeRoutesResourceWithEmailVerified(string $modelName, string $routeName): array
    {
        return [
            "User cannot view any {$modelName} because is not verified" => ["{$routeName}.index", 'get'],
            "User cannot view {$modelName} because is not verified" => ["{$routeName}.show", 'get'],
            "User cannot update {$modelName} because is not verified" => ["{$routeName}.update", 'patch'],
            "User cannot delete {$modelName} because is not verified" => ["{$routeName}.destroy", 'delete'],
        ];
    }

    protected function makeRoutesResourceWithPolicies(string $modelName, string $routeName): array
    {

        return [
            "User cannot view any {$modelName}" => ["{$routeName}.index", 'get'],
            "User cannot view {$modelName}" => ["{$routeName}.show", 'get'],
            "User cannot update {$modelName}" => ["{$routeName}.update", 'patch'],
            "User cannot delete {$modelName}" => ["{$routeName}.destroy", 'delete'],
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

    /**
     * Get the value of routeName
     */
    public function getRouteName()
    {
        if (is_null($this->routeName) && !is_null($this->modelName)) {
            $this->routeName = "{$this->modelName}s";
        }

        return $this->routeName;
    }

    /**
     * Set the value of routeName
     *
     * @return  self
     */
    public function setRouteName($routeName)
    {
        $this->routeName = $routeName;

        return $this;
    }
}
