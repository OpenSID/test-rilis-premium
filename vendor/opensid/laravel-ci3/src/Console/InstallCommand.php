<?php

namespace OpenSID\LaravelCI3\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class InstallCommand extends Command
{
    protected $signature = 'ci3:install 
                            {--force : Overwrite existing files}
                            {--no-test : Skip testing after installation}';

    protected $description = 'Install and configure CodeIgniter 3 integration automatically';

    public function handle()
    {
        $this->info('🚀 Installing CodeIgniter 3 Integration...');
        $this->newLine();

        // Step 1: Publish files
        $this->task('Publishing CI3 files', function () {
            $params = ['--tag' => 'ci3-all', '--provider' => 'OpenSID\LaravelCI3\Providers\CodeIgniterServiceProvider'];
            
            if ($this->option('force')) {
                $params['--force'] = true;
            }
            
            $this->callSilent('vendor:publish', $params);
            return true;
        });

        // Step 2: Auto-detect and configure CI3 path
        $this->task('Configuring CI3 application path', function () {
            return $this->configureCI3Path();
        });

        // Step 3: Configure routes
        $this->task('Setting up CI3 routes', function () {
            return $this->configureRoutes();
        });

        // Step 4: Configure sessions (if needed)
        $this->task('Configuring session sharing', function () {
            return $this->configureSessions();
        });

        // Step 5: Test installation
        if (!$this->option('no-test')) {
            $this->newLine();
            $this->info('🧪 Testing installation...');
            $this->call('ci3:test');
        }

        $this->newLine();
        $this->info('✅ CodeIgniter 3 integration installed successfully!');
        $this->newLine();
        
        $this->line('Next steps:');
        $this->line('  1. Access CI3 routes at: http://your-app.test/ci3/controller/method');
        $this->line('  2. Use facades in CI3: Session::get(), Cache::put(), Log::info()');
        $this->line('  3. Run tests: php artisan ci3:test');
        $this->newLine();
        
        return 0;
    }

    protected function configureCI3Path()
    {
        $envPath = base_path('.env');
        
        if (!File::exists($envPath)) {
            return false;
        }

        $envContent = File::get($envPath);
        
        // Check if CI3_APPPATH already exists
        if (strpos($envContent, 'CI3_APPPATH=') !== false) {
            return true; // Already configured
        }

        // Auto-detect CI3 application folder
        $ci3Path = base_path('application');
        
        if (!File::isDirectory($ci3Path)) {
            $this->warn('CI3 application folder not found at: ' . $ci3Path);
            return false;
        }

        // Add to .env
        $ci3Config = "\n# CodeIgniter 3 Configuration\n";
        $ci3Config .= "CI3_APPPATH=\"" . str_replace('\\', '/', $ci3Path) . "/\"\n";
        $ci3Config .= "CI3_ENVIRONMENT=development\n";
        
        File::append($envPath, $ci3Config);
        
        return true;
    }

    protected function configureRoutes()
    {
        $routesPath = base_path('routes/web.php');
        
        if (!File::exists($routesPath)) {
            return false;
        }

        $routesContent = File::get($routesPath);
        
        // Check if CI3 route already exists
        if (strpos($routesContent, "Route::any('ci3/{any}") !== false) {
            return true; // Already configured
        }

        // Add CI3 catch-all route
        $ci3Route = "\n// CodeIgniter 3 Routes\n";
        $ci3Route .= "Route::any('ci3/{any}', function () {\n";
        $ci3Route .= "    return app('ci3');\n";
        $ci3Route .= "})->where('any', '.*');\n";
        
        File::append($routesPath, $ci3Route);
        
        return true;
    }

    protected function configureSessions()
    {
        $sessionConfigPath = config_path('session.php');
        
        if (!File::exists($sessionConfigPath)) {
            return false;
        }

        $sessionContent = File::get($sessionConfigPath);
        
        // Check current session driver
        if (strpos($sessionContent, "'driver' => env('SESSION_DRIVER', 'file')") === false &&
            strpos($sessionContent, "'driver' => 'file'") !== false) {
            // Already configured or using different driver
            return true;
        }

        // Check .env for SESSION_DRIVER
        $envPath = base_path('.env');
        if (File::exists($envPath)) {
            $envContent = File::get($envPath);
            if (strpos($envContent, 'SESSION_DRIVER=') === false) {
                File::append($envPath, "SESSION_DRIVER=file\n");
            }
        }
        
        return true;
    }
}
