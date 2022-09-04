<?php

namespace App\Listeners;

use App\Core\Debug\DebugConfig;
use App\Core\Debug\DebugDatabase;
use App\Core\Debug\DebugSession;
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
        if (app()->environment('local')) {
            Debugbar::addCollector(new DebugConfig($event->ci));
            Debugbar::addCollector(new DebugDatabase($event->ci));
            Debugbar::addCollector(new DebugSession);
        }
    }
}
