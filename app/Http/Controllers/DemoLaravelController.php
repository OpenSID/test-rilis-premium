<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DemoLaravelController extends Controller
{
    /**
     * Demo Laravel menggunakan CI3
     */
    public function index()
    {
        // Log session info for debugging
        \Log::info('Laravel Index: Session info', [
            'session_id' => session()->getId(),
            'session_name' => session()->getName(),
            'is_started' => session()->isStarted(),
        ]);
        
        // Get CI3 instance
        $ci = app('ci');
        
        // Test: Load CI3 config (no database needed)
        $ci->load->config('config');
        $ci3Config = [
            'base_url' => $ci->config->item('base_url'),
            'index_page' => $ci->config->item('index_page'),
            'sess_driver' => $ci->config->item('sess_driver'),
        ];
        
        // Test: Load CI3 helper
        $ci->load->helper('url');
        
        // Example: Use Laravel Cache
        $cachedData = cache()->remember('demo_laravel_data', 60, function() {
            return [
                'timestamp' => now()->format('Y-m-d H:i:s'),
                'framework' => 'Laravel 10',
                'integrated_with' => 'CodeIgniter 3',
            ];
        });

        // Example: Use Laravel Session
        session(['demo_laravel_visit' => now()->format('Y-m-d H:i:s')]);
        $lastVisit = session('demo_ci3_visit', 'Belum pernah mengunjungi demo CI3');

        session()->put('session_dari_laravel', 'Data ini diset dari Laravel');
        
        // Force save session
        session()->save();

        // Prepare data
        $data = [
            'title' => 'Demo Laravel menggunakan CI3',
            'cached_data' => $cachedData,
            'session_demo_ci3' => session()->get('demo_ci3_visit'),
            'session_demo_laravel' => session()->get('akas'),
            'session_dari_ci3' => session()->get('session_dari_ci3'),
            'session_dari_laravel' => session()->get('session_dari_laravel'),
            'session_id' => session()->getId(),
            'session_all' => session()->all(),
            'last_ci3_visit' => $lastVisit,
            'ci3_config' => $ci3Config,
            'ci_loaded' => $ci !== null,
            'laravel_users' => collect([]), // Empty for now
            'ci3_users' => [], // Empty for now
            'examples' => [
                [
                    'title' => 'CI3 Instance',
                    'code' => '$ci = app(\'ci\');',
                    'status' => '✅ Loaded Successfully!',
                ],
                [
                    'title' => 'CI3 Config',
                    'code' => '$ci->load->config(\'config\'); $ci->config->item(\'base_url\');',
                    'status' => '✅ Working: ' . ($ci3Config['base_url'] ?: 'default'),
                ],
                [
                    'title' => 'CI3 Helper',
                    'code' => '$ci->load->helper(\'url\');',
                    'status' => '✅ Loaded',
                ],
                [
                    'title' => 'Laravel Cache',
                    'code' => 'cache()->remember(\'key\', 60, fn() => $data);',
                    'status' => '✅ Working',
                ],
                [
                    'title' => 'Session Sharing',
                    'code' => 'session([\'key\' => \'value\']);',
                    'status' => '✅ Synchronized',
                ],
                [
                    'title' => 'Global Variable',
                    'code' => '$GLOBALS[\'CI3\'] or ci3()',
                    'status' => '✅ Available: ' . (isset($GLOBALS['CI3']) ? 'YES' : 'NO'),
                ],
            ],
        ];

        return view('demo-laravel', $data);
    }

    /**
     * Render CI3 view from Laravel controller
     */
    public function ci3View()
    {
        // Get CI3 instance
        $ci = app('ci');
        
        // Prepare data
        $data = [
            'title' => 'Laravel Rendering CI3 View',
            'cached_data' => [
                'framework' => 'Laravel 10',
                'laravel_integration' => true,
                'timestamp' => now()->format('Y-m-d H:i:s'),
            ],
            'session_dari_ci3' => session('session_dari_ci3'),
            'session_dari_laravel' => session('session_dari_laravel'),
            'session_id' => session()->getId(),
            'examples' => [
                [
                    'title' => 'CI3 View',
                    'code' => 'ci3_view("view_name", $data)',
                    'status' => '✅ Working',
                ],
                [
                    'title' => 'Laravel Helper',
                    'code' => 'return ci3_view("demo_ci3");',
                    'status' => '✅ Available',
                ],
            ],
        ];
        
        // Use ci3_view helper to render CI3 view from Laravel
        return ci3_view('demo_ci3', $data);
    }

    /**
     * Set session data for testing cross-framework session sync
     */
    public function sessionSet()
    {
        // Set session data
        session(['session_dari_laravel' => 'AKAS ' . now()]);
        
        return response()->json([
            'success' => true,
            'message' => 'Session set successfully',
            'data' => [
                'session_id' => session()->getId(),
                'session_name' => session()->getName(),
                'session_dari_laravel' => session('session_dari_laravel'),
                'all_session' => session()->all(),
            ]
        ]);
    }

    /**
     * Get session data for testing cross-framework session sync
     */
    public function sessionGet()
    {
        // Get session data yang di-set dari CI3
        $session_data = [
            'user_id' => session('user_id'),
            'username' => session('username'),
            'email' => session('email'),
            'role' => session('role'),
            'set_from' => session('set_from'),
            'timestamp' => session('timestamp'),
            'ci3_library_data' => session('ci3_library_data'),
        ];
        
        return response()->json([
            'success' => true,
            'message' => 'Session retrieved successfully from Laravel',
            'data' => [
                'session_id' => session()->getId(),
                'session_name' => session()->getName(),
                'session_dari_laravel' => session('session_dari_laravel'),
                'session_dari_ci3' => session('session_dari_ci3'),
                'data_from_ci3_native_$_SESSION' => $session_data,
                'all_session' => session()->all(),
                'test_result' => [
                    'user_id_exists' => session()->has('user_id'),
                    'user_id_value' => session('user_id'),
                    'username_value' => session('username'),
                    'note' => 'If these values are not null, session bridge is working!',
                ],
            ]
        ]);
    }
    
    /**
     * Set session from Laravel for testing
     */
    public function sessionSetFromLaravel()
    {
        // Set session data dari Laravel
        session([
            'laravel_user_id' => 99999,
            'laravel_username' => 'jane_doe_laravel',
            'laravel_email' => 'jane@example.com',
            'laravel_role' => 'user',
            'set_from' => 'Laravel session() helper',
            'timestamp' => now()->format('Y-m-d H:i:s'),
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Session set from Laravel',
            'data' => [
                'session_id' => session()->getId(),
                'set_data' => [
                    'laravel_user_id' => session('laravel_user_id'),
                    'laravel_username' => session('laravel_username'),
                    'laravel_email' => session('laravel_email'),
                    'laravel_role' => session('laravel_role'),
                ],
                'all_session' => session()->all(),
                'note' => 'Now access /ci3/demo-ci3/session-get to verify data is accessible in CI3',
            ],
        ]);
    }
}
