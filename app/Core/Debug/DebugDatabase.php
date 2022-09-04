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
        $this->ci->load->helper('text');
    }

    /**
     * {@inheritdoc}
     */
    public function collect()
    {
        $dbs = [];
        $data = [];

        // Let's determine which databases are currently connected to
        foreach (get_object_vars($this->ci) as $name => $cobject) {
            if (is_object($cobject)) {
                if ($cobject instanceof \CI_DB) {
                    $dbs[get_class($this->ci) . ':$' . $name] = $cobject;
                } elseif ($cobject instanceof \CI_Model) {
                    foreach (get_object_vars($cobject) as $mname => $mobject) {
                        if ($mobject instanceof \CI_DB) {
                            $dbs[get_class($cobject) . ':$' . $mname] = $mobject;
                        }
                    }
                }
            }
        }

        foreach ($dbs as $name => $db) {
            foreach ($db->queries as $key => $value) {
                $data[$this->formatDuration($db->query_times[$key])] = $value;
            }
        }

        return $data;
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
                "icon" => "database",
                "widget" => "PhpDebugBar.Widgets.VariableListWidget",
                "map" => "CI Database",
                "default" => "{}"
            ]
        ];
    }
}