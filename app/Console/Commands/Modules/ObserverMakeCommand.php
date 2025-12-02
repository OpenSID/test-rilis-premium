<?php

namespace App\Console\Commands\Modules;

class ObserverMakeCommand extends BaseModuleMakeCommand
{
    protected $signature = 'make:observer {name} {--module=}';
    protected $description = 'Create a new Observer class (optionally for a specific module)';

    protected function stub(): string
    {
        return 'app/Console/Commands/Modules/Stubs/observer.stub';
    }

    protected function moduleFolder(): string
    {
        return 'Observers';
    }

    protected function defaultNamespace(): string
    {
        return 'App\\Observers';
    }
}
