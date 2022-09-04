<?php

namespace App\Core\Debug;

use App\Core\CI_Controller;
use DebugBar\DataCollector\Renderable;
use DebugBar\DataCollector\DataCollector;
use DebugBar\DataCollector\DataCollectorInterface;

class DebugConfig extends DataCollector implements DataCollectorInterface, Renderable
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
        $data = [];
        foreach ($this->ci->config->config as $key => $value) {
            $data[$key] = is_string($value) ? $value : $this->formatVar($value);
        }
        return $data;
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'CI Config';
    }

    /**
     * {@inheritDoc}
     */
    public function getWidgets()
    {
        return [
            "CI Config" => [
                "icon" => "archive",
                "widget" => "PhpDebugBar.Widgets.VariableListWidget",
                "map" => "CI Config",
                "default" => "{}"
            ]
        ];
    }
}