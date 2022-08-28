<?php

namespace App\Core\PendingRouteTransformers;

use App\Core\Admin_Controller;
use App\Core\Api_Controller;
use App\Core\CI_Controller;
use App\Core\Mandiri_Controller;
use App\Core\MY_Controller;
use App\Core\Premium;
use App\Core\Web_Controller;
use OpenDesa\RouteDiscovery\PendingRouteTransformers\RejectDefaultControllerMethodRoutes;

class RejectDefaultCIControllerMethodRoutes extends RejectDefaultControllerMethodRoutes
{
    /**
     * @var array<int, string>
     */
    public array $rejectMethodsInClasses = [
        Admin_Controller::class,
        Api_Controller::class,
        CI_Controller::class,
        Mandiri_Controller::class,
        MY_Controller::class,
        Premium::class,
        Web_Controller::class,
    ];
}
