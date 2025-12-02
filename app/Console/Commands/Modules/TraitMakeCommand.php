<?php

namespace App\Console\Commands\Modules;

use App\Console\Commands\Modules\BaseModuleMakeCommand;

class TraitMakeCommand extends BaseModuleMakeCommand
{
    protected $signature = 'make:trait {name} {--module=}';
    protected $description = 'Create a new trait (optionally for a specific module)';

    protected function stub(): string
    {
        return 'app/Console/Commands/Modules/Stubs/trait.stub';
    }

    protected function moduleFolder(): string
    {
        return 'Traits';
    }

    protected function defaultNamespace(): string
    {
        return 'App\Traits';
    }
}
