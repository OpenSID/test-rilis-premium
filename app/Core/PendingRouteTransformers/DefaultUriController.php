<?php

namespace App\Core\PendingRouteTransformers;

use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use OpenDesa\RouteDiscovery\PendingRoutes\PendingRoute;
use OpenDesa\RouteDiscovery\PendingRoutes\PendingRouteAction;
use OpenDesa\RouteDiscovery\PendingRouteTransformers\PendingRouteTransformer;

class DefaultUriController implements PendingRouteTransformer
{
    /**
     * {@inheritdoc}
     */
    public function transform(Collection $pendingRoutes): Collection
    {
        return $pendingRoutes->each(function (PendingRoute $pendingRoute) {
            $pendingRoute->actions->each(function (PendingRouteAction $action) use ($pendingRoute) {
                if (Str::contains($action->uri, 'index') && $actual = $pendingRoute->class->getMethod($action->method->name)) {
                    $action->uris = Str::remove(['/index', '.php'], $action->uri);
                    foreach ($actual->getParameters() as $param) {
                        $action->uris = $param->isOptional()
                            ? "{$action->uris}/{{$param->getName()}?}"
                            : "{$action->uris}/{{$param->getName()}}";
                    }
                    Route::match($action->methods, $action->uris, $action->action());
                }
            });
        });
    }
}