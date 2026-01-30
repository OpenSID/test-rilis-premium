<?php

namespace OpenSID\LaravelCI3\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class TestCommand extends Command
{
    protected $signature = 'ci3:test';

    protected $description = 'Test CodeIgniter 3 integration setup';

    protected $passed = 0;
    protected $failed = 0;

    public function handle()
    {
        $this->info('🧪 Testing CodeIgniter 3 Integration...');
        $this->newLine();

        // Test 2: Check if config files exist
        $this->test('CI3 config files exist', function () {
            return File::exists(app_path('../application/config/hooks.php')) &&
                   File::exists(app_path('../application/config/modules.php'));
        });

        // Test 3: Check if core files exist
        $this->test('CI3 core files exist', function () {
            return File::exists(app_path('../application/core/MY_Controller.php'));
        });

        // Test 4: Check environment configuration
        $this->test('Environment variables configured', function () {
            return !empty(env('CI3_APPPATH'));
        });

        // Test 5: Check if routes are configured
        $this->test('CI3 routes configured', function () {
            $routesPath = base_path('routes/web.php');
            if (!File::exists($routesPath)) {
                return false;
            }
            $content = File::get($routesPath);
            return strpos($content, 'ci3') !== false || strpos($content, "app('ci3')") !== false;
        });

        // Test 6: Test Laravel facades availability
        $this->test('Laravel facades available', function () {
            try {
                // Test if we can access facades
                $testValue = 'ci3_test_' . time();
                \Illuminate\Support\Facades\Cache::put('ci3_test', $testValue, 60);
                $retrieved = \Illuminate\Support\Facades\Cache::get('ci3_test');
                return $retrieved === $testValue;
            } catch (\Exception $e) {
                $this->comment('  Error: ' . $e->getMessage());
                return false;
            }
        });

        // Test 7: Check CI3 application folder structure
        $this->test('CI3 folder structure valid', function () {
            $appPath = env('CI3_APPPATH', base_path('application') . '/');
            return File::isDirectory(rtrim($appPath, '/') . '/controllers') &&
                   File::isDirectory(rtrim($appPath, '/') . '/models') &&
                   File::isDirectory(rtrim($appPath, '/') . '/views');
        });

        // Test 8: Check if CI3 can be instantiated
        $this->test('CI3 instance can be created', function () {
            try {
                $ci3 = app('ci3');
                return $ci3 !== null;
            } catch (\Exception $e) {
                $this->comment('  Error: ' . $e->getMessage());
                return false;
            }
        });

        // Summary
        $this->newLine();
        $total = $this->passed + $this->failed;
        
        if ($this->failed === 0) {
            $this->info("✅ All tests passed! ({$this->passed}/{$total})");
            $this->newLine();
            $this->line('Your CodeIgniter 3 integration is ready to use!');
            return 0;
        } else {
            $this->warn("⚠️  Some tests failed: {$this->passed} passed, {$this->failed} failed");
            $this->newLine();
            $this->line('Please check your configuration and files.');
            return 1;
        }
    }

    protected function test($description, callable $callback)
    {
        $result = $callback();
        
        if ($result) {
            $this->line("  <fg=green>✓</> {$description}");
            $this->passed++;
        } else {
            $this->line("  <fg=red>✗</> {$description}");
            $this->failed++;
        }
        
        return $result;
    }
}
