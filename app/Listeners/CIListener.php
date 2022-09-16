<?php

namespace App\Listeners;

use App\Legacy\Core\Debug\DebugConfig;
use App\Legacy\Core\Debug\DebugDatabase;
use App\Legacy\Core\Debug\DebugSession;
use App\Events\CIEvent;
use Barryvdh\Debugbar\Facades\Debugbar;

class CIListener
{
    /**
     * Handle the event.
     *
     * @param  \App\Events\CIEvent  $event
     * @return void
     */
    public function handle(CIEvent $event)
    {
        if (app()->environment('local') && class_exists(Debugbar::class)) {
            Debugbar::addCollector(new DebugConfig($event->ci));
            Debugbar::addCollector(new DebugDatabase($event->ci));
            Debugbar::addCollector(new DebugSession);
        }
    }
}
