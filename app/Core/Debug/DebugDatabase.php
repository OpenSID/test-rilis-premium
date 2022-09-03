<?php

namespace App\Core\Debug;

use App\Core\CI_Controller;
use DebugBar\DataCollector\DataCollector;
use DebugBar\DataCollector\DataCollectorInterface;
use DebugBar\DataCollector\Renderable;

class DebugDatabase extends DataCollector implements DataCollectorInterface, Renderable
{
    /** @var CI_Controller */
    protected $ci;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(CI_Controller $ci)
    {
        $this->ci = $ci;
    }
    /**
     * {@inheritdoc}
     */
    public function collect()
    {
        $database = [];
        $cobjects = get_object_vars($this->ci);

        foreach ($cobjects as $name => $cobject) {
            if (is_object($cobject)) {
                if ($cobject instanceof \CI_DB) {
                    $controller = &get_instance();
                    if ($controller instanceof CI_Controller) {
                        $database = [
                            'database'    => $cobject->database,
                            'hostname'    => $cobject->hostname,
                            'queries'     => $cobject->queries,
                            'query_times' => $cobject->query_times,
                            'query_count' => $cobject->query_count,
                        ];
                    }
                } elseif ($cobject instanceof \CI_Model) {
                    foreach (get_object_vars($cobject) as $mname => $mobject) {
                        if ($mobject instanceof \CI_DB) {
                            $database = [
                                'database'    => $mobject->database,
                                'hostname'    => $mobject->hostname,
                                'queries'     => $mobject->queries,
                                'query_times' => $mobject->query_times,
                                'query_count' => $mobject->query_count,
                            ];
                        }
                    }
                }
            }
        }

        return collect($database['queries'])
            ->values();
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'CI Database';
    }

    /**
     * {@inheritDoc}
     */
    public function getWidgets()
    {
        return [
            "CI Database" => [
                "icon" => "archive",
                "widget" => "PhpDebugBar.Widgets.VariableListWidget",
                "map" => "CI Database",
                "default" => "{}"
            ]
        ];
    }
}