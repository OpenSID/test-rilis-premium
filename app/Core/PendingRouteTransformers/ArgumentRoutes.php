<?php

namespace App\Core\PendingRouteTransformers;

use ReflectionMethod;
use Illuminate\Support\Collection;
use Illuminate\Routing\Controller as BaseController;
use OpenDesa\RouteDiscovery\PendingRoutes\PendingRoute;
use OpenDesa\RouteDiscovery\PendingRoutes\PendingRouteAction;
use OpenDesa\RouteDiscovery\PendingRouteTransformers\PendingRouteTransformer;

class ArgumentRoutes implements PendingRouteTransformer
{
    /**
     * {@inheritdoc}
     */
    public function transform(Collection $pendingRoutes): Collection
    {
        return $pendingRoutes
            ->each(function (PendingRoute $pendingRoute) {
                $pendingRoute->actions
                    ->each(function (PendingRouteAction $action) use ($pendingRoute) {
                        if ($actual = $pendingRoute->class->getMethod($action->method->name)) {
                            foreach ($actual->getParameters() as $param) {
                                $action->uri = $param->isOptional()
                                    ? "{$action->uri}/{{$param->getName()}?}"
                                    : "{$action->uri}/{{$param->getName()}}";
                            }
                        }
                    });
            });
    }
}