<?php

namespace App\Console\Commands\Modules;

class ScopeMakeCommand extends BaseModuleMakeCommand
{
    protected $signature = 'make:scope {name} {--module=}';
    protected $description = 'Create a new Scope class (optionally for a specific module)';

    protected function stub(): string
    {
        return 'app/Console/Commands/Modules/Stubs/scope.stub';
    }

    protected function moduleFolder(): string
    {
        return 'Models/Scopes';
    }

    protected function defaultNamespace(): string
    {
        return 'App\\Models\\Scopes';
    }
}
