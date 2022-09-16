<?php

namespace App\Legacy\Core\PendingRouteTransformers;

use App\Legacy\Core\Admin_Controller;
use App\Legacy\Core\Api_Controller;
use App\Legacy\Core\CI_Controller;
use App\Legacy\Core\Mandiri_Controller;
use App\Legacy\Core\MY_Controller;
use App\Legacy\Core\Premium;
use App\Legacy\Core\Web_Controller;
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
