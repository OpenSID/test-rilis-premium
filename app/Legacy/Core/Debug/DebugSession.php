<?php

namespace App\Legacy\Core\Debug;

use DebugBar\DataCollector\DataCollector;
use DebugBar\DataCollector\DataCollectorInterface;
use DebugBar\DataCollector\Renderable;

class DebugSession extends DataCollector implements DataCollectorInterface, Renderable
{
    /**
     * {@inheritdoc}
     */
    public function collect()
    {
        $data = [];
        foreach ($_SESSION as $key => $value) {
            $data[$key] = is_string($value) ? $value : $this->formatVar($value);
        }
        return $data;
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'CI Session';
    }

    /**
     * {@inheritDoc}
     */
    public function getWidgets()
    {
        return [
            "CI Session" => [
                "icon" => "archive",
                "widget" => "PhpDebugBar.Widgets.VariableListWidget",
                "map" => "CI Session",
                "default" => "{}"
            ]
        ];
    }
}