<?php

namespace OpenSID\LaravelCI3\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Debug Bar - Display debug information in views
 * Works for both CI3 and Laravel views
 */
class DebugBar
{
    private $startTime;
    private $startMemory;

    public function __construct()
    {
        $this->startTime = $_SERVER['REQUEST_TIME_FLOAT'] ?? microtime(true);
        $this->startMemory = memory_get_usage(true);
    }

    /**
     * Get debug information array
     */
    public function getDebugInfo()
    {
        return [
            'framework' => $this->detectFramework(),
            'method' => $_SERVER['REQUEST_METHOD'] ?? 'N/A',
            'path' => $_SERVER['REQUEST_URI'] ?? 'N/A',
            'session' => [
                'id' => session()->getId() ?? 'N/A',
                'name' => session()->getName() ?? 'PHPSESSID',
                'data_count' => count(session()->all() ?? []),
            ],
            'cache' => $this->getCacheInfo(),
            'database' => $this->getDatabaseInfo(),
            'performance' => [
                'elapsed' => round((microtime(true) - $this->startTime) * 1000, 2) . ' ms',
                'memory_used' => $this->formatBytes(memory_get_usage(true) - $this->startMemory),
                'memory_peak' => $this->formatBytes(memory_get_peak_usage(true)),
            ],
            'environment' => app()->environment(),
            'debug' => config('app.debug') ? 'ON' : 'OFF',
        ];
    }

    /**
     * Detect which framework is running
     */
    private function detectFramework()
    {
        // Check if we're in a CI3 controller context (from middleware)
        if (isset($GLOBALS['CI3']) && is_object($GLOBALS['CI3'])) {
            return 'CodeIgniter 3 (via Laravel)';
        }
        return 'Laravel 10';
    }

    /**
     * Get cache information
     */
    private function getCacheInfo()
    {
        try {
            $driver = config('cache.default') ?? 'file';
            // Try to get a test value
            $testKey = '__debug_bar_test_' . time();
            cache()->put($testKey, 'test', 1);
            $testValue = cache()->get($testKey);
            cache()->forget($testKey);
            
            return [
                'driver' => $driver,
                'status' => $testValue === 'test' ? 'Working' : 'Not working',
            ];
        } catch (\Exception $e) {
            return [
                'driver' => 'unknown',
                'status' => 'Error: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Get database information
     */
    private function getDatabaseInfo()
    {
        try {
            $defaultConnection = config('database.default') ?? 'sqlite';
            $queryCount = count(DB::getQueryLog()) ?? 0;
            
            return [
                'default_connection' => $defaultConnection,
                'query_count' => $queryCount,
                'queries' => $this->getLastQueries(5),
            ];
        } catch (\Exception $e) {
            return [
                'error' => 'Database not available',
            ];
        }
    }

    /**
     * Get last N queries
     */
    private function getLastQueries($limit = 5)
    {
        try {
            $queries = DB::getQueryLog();
            $last = array_slice($queries, -$limit);
            
            return array_map(function ($query) {
                return [
                    'query' => $query['query'] ?? '',
                    'time' => round($query['time'] ?? 0, 2) . ' ms',
                ];
            }, $last);
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Format bytes to human readable
     */
    private function formatBytes($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));
        
        return round($bytes, 2) . ' ' . $units[$pow];
    }

    /**
     * Render debug bar as HTML
     */
    public function render()
    {
        if (!config('app.debug')) {
            return '';
        }

        $info = $this->getDebugInfo();
        
        ob_start();
        ?>
        <div id="debug-bar" style="
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: #1a1a1a;
            color: #e0e0e0;
            font-family: 'Monaco', 'Menlo', 'Courier New', monospace;
            font-size: 12px;
            max-height: 200px;
            overflow-y: auto;
            border-top: 2px solid #ff6b6b;
            z-index: 999999;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.3);
        ">
            <style>
                #debug-bar * { box-sizing: border-box; margin: 0; padding: 0; }
                #debug-bar .tab-buttons {
                    display: flex;
                    border-bottom: 1px solid #333;
                    background: #0d0d0d;
                    overflow-x: auto;
                }
                #debug-bar .tab-btn {
                    padding: 8px 15px;
                    cursor: pointer;
                    background: #1a1a1a;
                    border: none;
                    color: #e0e0e0;
                    border-right: 1px solid #333;
                    white-space: nowrap;
                    transition: background 0.2s;
                }
                #debug-bar .tab-btn:hover { background: #2a2a2a; }
                #debug-bar .tab-btn.active { background: #ff6b6b; color: #000; }
                #debug-bar .tab-content {
                    display: none;
                    padding: 10px;
                    background: #1a1a1a;
                }
                #debug-bar .tab-content.active { display: block; }
                #debug-bar .info-grid {
                    display: grid;
                    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                    gap: 15px;
                }
                #debug-bar .info-section {
                    background: #252525;
                    padding: 10px;
                    border-radius: 4px;
                    border-left: 3px solid #ff6b6b;
                }
                #debug-bar .info-title {
                    color: #ff6b6b;
                    font-weight: bold;
                    margin-bottom: 5px;
                }
                #debug-bar .info-item {
                    display: flex;
                    justify-content: space-between;
                    padding: 3px 0;
                    border-bottom: 1px solid #333;
                }
                #debug-bar .info-item:last-child { border-bottom: none; }
                #debug-bar .label { color: #88d8ff; }
                #debug-bar .value { color: #a0ff6b; font-weight: bold; }
                #debug-bar .query-item {
                    background: #0d0d0d;
                    padding: 8px;
                    margin: 5px 0;
                    border-left: 2px solid #88d8ff;
                    border-radius: 2px;
                    word-break: break-all;
                }
                #debug-bar .query-time {
                    color: #ffaa00;
                    font-size: 11px;
                    margin-top: 3px;
                }
            </style>

            <div class="tab-buttons">
                <button class="tab-btn active" onclick="debugBarTab('general')">📊 General</button>
                <button class="tab-btn" onclick="debugBarTab('performance')">⚡ Performance</button>
                <button class="tab-btn" onclick="debugBarTab('session')">🔑 Session</button>
                <button class="tab-btn" onclick="debugBarTab('database')">💾 Database</button>
                <button class="tab-btn" onclick="debugBarTab('cache')">📦 Cache</button>
            </div>

            <!-- General Tab -->
            <div id="tab-general" class="tab-content active">
                <div class="info-grid">
                    <div class="info-section">
                        <div class="info-title">Request</div>
                        <div class="info-item">
                            <span class="label">Framework:</span>
                            <span class="value"><?php echo $info['framework']; ?></span>
                        </div>
                        <div class="info-item">
                            <span class="label">Method:</span>
                            <span class="value"><?php echo $info['method']; ?></span>
                        </div>
                        <div class="info-item">
                            <span class="label">Path:</span>
                            <span class="value"><?php echo substr($info['path'], 0, 40); ?></span>
                        </div>
                    </div>
                    <div class="info-section">
                        <div class="info-title">Environment</div>
                        <div class="info-item">
                            <span class="label">Environment:</span>
                            <span class="value"><?php echo $info['environment']; ?></span>
                        </div>
                        <div class="info-item">
                            <span class="label">Debug Mode:</span>
                            <span class="value"><?php echo $info['debug']; ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Performance Tab -->
            <div id="tab-performance" class="tab-content">
                <div class="info-grid">
                    <div class="info-section">
                        <div class="info-title">Performance Metrics</div>
                        <div class="info-item">
                            <span class="label">Elapsed Time:</span>
                            <span class="value"><?php echo $info['performance']['elapsed']; ?></span>
                        </div>
                        <div class="info-item">
                            <span class="label">Memory Used:</span>
                            <span class="value"><?php echo $info['performance']['memory_used']; ?></span>
                        </div>
                        <div class="info-item">
                            <span class="label">Memory Peak:</span>
                            <span class="value"><?php echo $info['performance']['memory_peak']; ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Session Tab -->
            <div id="tab-session" class="tab-content">
                <div class="info-grid">
                    <div class="info-section">
                        <div class="info-title">Session Information</div>
                        <div class="info-item">
                            <span class="label">Session ID:</span>
                            <span class="value" style="word-break: break-all;"><?php echo $info['session']['id']; ?></span>
                        </div>
                        <div class="info-item">
                            <span class="label">Session Name:</span>
                            <span class="value"><?php echo $info['session']['name']; ?></span>
                        </div>
                        <div class="info-item">
                            <span class="label">Data Count:</span>
                            <span class="value"><?php echo $info['session']['data_count']; ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Database Tab -->
            <div id="tab-database" class="tab-content">
                <div class="info-grid">
                    <div class="info-section">
                        <div class="info-title">Database</div>
                        <div class="info-item">
                            <span class="label">Default Connection:</span>
                            <span class="value"><?php echo $info['database']['default_connection'] ?? 'N/A'; ?></span>
                        </div>
                        <div class="info-item">
                            <span class="label">Query Count:</span>
                            <span class="value"><?php echo $info['database']['query_count'] ?? 0; ?></span>
                        </div>
                    </div>
                </div>
                <?php if (!empty($info['database']['queries'])): ?>
                <div style="margin-top: 10px;">
                    <div class="info-title">Last Queries</div>
                    <?php foreach ($info['database']['queries'] as $query): ?>
                    <div class="query-item">
                        <div><?php echo $query['query']; ?></div>
                        <div class="query-time">Time: <?php echo $query['time']; ?></div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>

            <!-- Cache Tab -->
            <div id="tab-cache" class="tab-content">
                <div class="info-grid">
                    <div class="info-section">
                        <div class="info-title">Cache</div>
                        <div class="info-item">
                            <span class="label">Driver:</span>
                            <span class="value"><?php echo $info['cache']['driver'] ?? 'N/A'; ?></span>
                        </div>
                        <div class="info-item">
                            <span class="label">Status:</span>
                            <span class="value"><?php echo $info['cache']['status'] ?? 'Unknown'; ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
        function debugBarTab(tabName) {
            // Hide all tabs
            document.querySelectorAll('#debug-bar .tab-content').forEach(el => {
                el.classList.remove('active');
            });
            document.querySelectorAll('#debug-bar .tab-btn').forEach(el => {
                el.classList.remove('active');
            });
            
            // Show selected tab
            document.getElementById('tab-' + tabName).classList.add('active');
            event.target.classList.add('active');
        }
        </script>
        <?php
        
        return ob_get_clean();
    }
}
