<?php

namespace App\Core\PendingRouteTransformers;

use App\Core\CI_Controller;
use OpenDesa\RouteDiscovery\PendingRouteTransformers\RejectDefaultControllerMethodRoutes;

class RejectDefaultCIControllerMethodRoutes extends RejectDefaultControllerMethodRoutes
{
    /**
     * @var array<int, string>
     */
    public array $rejectMethodsInClasses = [
        CI_Controller::class,
    ];
}
