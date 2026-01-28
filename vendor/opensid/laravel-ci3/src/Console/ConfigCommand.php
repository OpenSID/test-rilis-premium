<?php

namespace OpenSID\LaravelCI3\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ConfigCommand extends Command
{
    protected $signature = 'ci3:config 
                            {--show : Show current configuration}
                            {--detect : Auto-detect CI3 paths}';

    protected $description = 'Configure CodeIgniter 3 integration settings';

    public function handle()
    {
        if ($this->option('show')) {
            return $this->showConfiguration();
        }

        if ($this->option('detect')) {
            return $this->detectConfiguration();
        }

        // Interactive configuration
        return $this->interactiveConfiguration();
    }

    protected function showConfiguration()
    {
        $this->info('📋 Current CI3 Configuration:');
        $this->newLine();

        $config = [
            'CI3_APPPATH' => env('CI3_APPPATH', 'Not set'),
            'CI3_ENVIRONMENT' => env('CI3_ENVIRONMENT', 'Not set'),
            'CI3_BASEPATH' => env('CI3_BASEPATH', 'Auto-detected from vendor'),
            'SESSION_DRIVER' => env('SESSION_DRIVER', config('session.driver')),
        ];

        foreach ($config as $key => $value) {
            $this->line("  <fg=cyan>{$key}:</> {$value}");
        }

        $this->newLine();

        // Show detected paths
        $this->info('📁 Detected Paths:');
        $this->newLine();

        $paths = [
            'Application' => $this->detectApplicationPath(),
            'System' => $this->detectSystemPath(),
            'Modules' => $this->detectModulesPath(),
            'Web Root' => base_path('public'),
        ];

        foreach ($paths as $label => $path) {
            $exists = File::exists($path) || File::isDirectory($path);
            $status = $exists ? '<fg=green>✓</>' : '<fg=red>✗</>';
            $this->line("  {$status} <fg=cyan>{$label}:</> {$path}");
        }

        $this->newLine();

        return 0;
    }

    protected function detectConfiguration()
    {
        $this->info('🔍 Auto-detecting CI3 configuration...');
        $this->newLine();

        $detected = [];

        // Detect application path
        $appPath = $this->detectApplicationPath();
        if (File::isDirectory($appPath)) {
            $detected['CI3_APPPATH'] = str_replace('\\', '/', $appPath) . '/';
            $this->line("  <fg=green>✓</> Found application folder: {$appPath}");
        } else {
            $this->line("  <fg=red>✗</> Application folder not found");
        }

        // Detect system path
        $systemPath = $this->detectSystemPath();
        if (File::isDirectory($systemPath)) {
            $detected['CI3_BASEPATH'] = str_replace('\\', '/', $systemPath) . '/';
            $this->line("  <fg=green>✓</> Found system folder: {$systemPath}");
        } else {
            $this->line("  <fg=yellow>!</> System folder not found (will use vendor)");
        }

        // Detect modules path
        $modulesPath = $this->detectModulesPath();
        if (File::isDirectory($modulesPath)) {
            $this->line("  <fg=green>✓</> Found modules folder: {$modulesPath}");
        }

        $this->newLine();

        if (empty($detected)) {
            $this->error('No CI3 installation detected.');
            $this->line('Make sure you have a CodeIgniter 3 application folder.');
            return 1;
        }

        // Ask to save
        if ($this->confirm('Save detected configuration to .env?', true)) {
            $this->saveToEnv($detected);
            $this->info('✅ Configuration saved!');
        }

        return 0;
    }

    protected function interactiveConfiguration()
    {
        $this->info('⚙️  Interactive CI3 Configuration');
        $this->newLine();

        $config = [];

        // Application path
        $defaultAppPath = $this->detectApplicationPath();
        $appPath = $this->ask('CI3 Application path', $defaultAppPath);
        
        if (!File::isDirectory($appPath)) {
            $this->error('Directory does not exist: ' . $appPath);
            return 1;
        }
        
        $config['CI3_APPPATH'] = str_replace('\\', '/', $appPath) . '/';

        // Environment
        $environment = $this->choice(
            'CI3 Environment',
            ['development', 'testing', 'production'],
            0
        );
        $config['CI3_ENVIRONMENT'] = $environment;

        // System path (optional)
        if ($this->confirm('Specify custom system path?', false)) {
            $systemPath = $this->ask('CI3 System path', $this->detectSystemPath());
            if (File::isDirectory($systemPath)) {
                $config['CI3_BASEPATH'] = str_replace('\\', '/', $systemPath) . '/';
            }
        }

        $this->newLine();

        // Save configuration
        $this->saveToEnv($config);
        
        $this->info('✅ Configuration saved successfully!');
        $this->line('Run "php artisan ci3:config --show" to view configuration.');
        
        return 0;
    }

    protected function saveToEnv(array $config)
    {
        $envPath = base_path('.env');
        
        if (!File::exists($envPath)) {
            $this->error('.env file not found');
            return false;
        }

        $envContent = File::get($envPath);
        
        foreach ($config as $key => $value) {
            $pattern = "/^{$key}=.*/m";
            $replacement = "{$key}=\"{$value}\"";
            
            if (preg_match($pattern, $envContent)) {
                // Update existing
                $envContent = preg_replace($pattern, $replacement, $envContent);
            } else {
                // Add new
                $envContent .= "\n{$replacement}";
            }
        }
        
        File::put($envPath, $envContent);
        
        return true;
    }

    protected function detectApplicationPath()
    {
        // Try common locations
        $paths = [
            base_path('application'),
            base_path('app/CI3/application'),
            base_path('ci3/application'),
        ];

        foreach ($paths as $path) {
            if (File::isDirectory($path) && File::isDirectory($path . '/controllers')) {
                return $path;
            }
        }

        return base_path('application');
    }

    protected function detectSystemPath()
    {
        // Try common locations
        $paths = [
            base_path('system'),
            base_path('ci3/system'),
            base_path('vendor/codeigniter/framework/system'),
        ];

        foreach ($paths as $path) {
            if (File::isDirectory($path) && File::exists($path . '/core/CodeIgniter.php')) {
                return $path;
            }
        }

        return base_path('vendor/codeigniter/framework/system');
    }

    protected function detectModulesPath()
    {
        $paths = [
            base_path('Modules'),
            base_path('application/modules'),
            base_path('modules'),
        ];

        foreach ($paths as $path) {
            if (File::isDirectory($path)) {
                return $path;
            }
        }

        return base_path('Modules');
    }
}
