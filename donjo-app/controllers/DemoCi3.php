<?php

use App\Models\User;
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Demo CI3 Controller - Menggunakan Laravel Features
 */
class DemoCi3 extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Halaman utama demo CI3 dengan Laravel integration
     */
    public function index()
    {
        // Test CI3 logging menggunakan Laravel
        logger()->info('AKAS IFO');
        logger()->debug('AKAS DEBUG');

        log_message('info', 'AKAS CI3 INFO log entry');
        log_message('debug', 'AKAS CI3 DEBUG log entry');
        
        // Prepare cached data from Laravel cache
        $cachedData = cache()->remember('demo_ci3_data', 120, function() {
            return [
                'framework' => 'CodeIgniter 3',
                'integrated_with' => 'Laravel 10',
                'timestamp' => date('Y-m-d H:i:s'),
            ];
        });

        // Set session via Laravel session facade
        session()->put('session_dari_ci3', 'Data dari CI3 ' . date('Y-m-d H:i:s'));
        
        // Prepare data for CI3 view
        $data = [
            'title' => 'Demo CI3 + Laravel Integration',
            'cached_data' => $cachedData,
            'session_dari_ci3' => session()->get('session_dari_ci3'),
            'session_dari_laravel' => session()->get('session_dari_laravel'),
            'session_id' => session()->getId(),
            'session_all' => session()->all(),
            'examples' => [
                [
                    'title' => 'Laravel Cache',
                    'code' => 'cache()->remember(\'key\', 60, fn() => $data);',
                    'status' => '✅ Working',
                ],
                [
                    'title' => 'Laravel Session',
                    'code' => 'session()->put(\'key\', \'value\');',
                    'status' => '✅ Working',
                ],
            ],
        ];
        
        // Load and render CI3 view, return output
        echo $this->load->view('demo_ci3', $data, true);
    }

    /**
     * Render Blade template from CI3 controller
     */
    public function blade()
    {
        // Prepare data
        $data = [
            'title' => 'CI3 Rendering Blade Template',
            'message' => 'This Blade template is rendered by CodeIgniter 3 controller using MY_Blade library!',
        ];

        // Render Blade view langsung via helper view() Laravel
        // Bisa pakai 'return' atau 'echo', keduanya akan bekerja!
        return view('ci3-blade-demo', $data);
    }

    /**
     * Set session for testing - accessible via demo-ci3/session-set
     */
    public function session_set()
    {
        // Set session data with current timestamp
        session()->put('session_dari_ci3', 'BARUUU ' . date('Y-m-d H:i:s'));
        
        // Set via native PHP $_SESSION
        $_SESSION['user_id'] = 12345;
        $_SESSION['username'] = 'john_doe_ci3';
        $_SESSION['email'] = 'john@example.com';
        $_SESSION['role'] = 'admin';
        $_SESSION['set_from'] = 'CI3 Native $_SESSION';
        $_SESSION['timestamp'] = date('Y-m-d H:i:s');

        // Set via CI3 session library
        $this->load->library('session');
        $this->session->set_userdata('ci3_library_data', 'Data from CI3 session library');

        // Output JSON response
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'message' => 'Session set successfully from CI3',
            'data' => [
                'session_id' => session()->getId(),
                'session_name' => session()->getName(),
                'session_dari_ci3' => session()->get('session_dari_ci3'),
                'user_id_from_$_SESSION' => $_SESSION['user_id'] ?? null,
                'username_from_$_SESSION' => $_SESSION['username'] ?? null,
                'all_session_data' => session()->all(),
                'native_$_SESSION_data' => [
                    'user_id' => $_SESSION['user_id'] ?? null,
                    'username' => $_SESSION['username'] ?? null,
                    'email' => $_SESSION['email'] ?? null,
                    'role' => $_SESSION['role'] ?? null,
                    'set_from' => $_SESSION['set_from'] ?? null,
                    'timestamp' => $_SESSION['timestamp'] ?? null,
                ],
                'note' => 'Now access /demo-laravel/session-get to verify data is accessible in Laravel!',
            ]
        ], JSON_PRETTY_PRINT);
    }

    /**
     * Get session for testing - accessible via demo-ci3/session-get
     */
    public function session_get()
    {
        // Get via native PHP $_SESSION
        $native_session = [
            'user_id' => $_SESSION['user_id'] ?? null,
            'username' => $_SESSION['username'] ?? null,
            'email' => $_SESSION['email'] ?? null,
            'role' => $_SESSION['role'] ?? null,
        ];
        
        // Get via Laravel session helper
        $laravel_session = [
            'user_id' => session('user_id'),
            'username' => session('username'),
            'email' => session('email'),
            'role' => session('role'),
        ];
        
        // Get via CI3 session library
        $this->load->library('session');
        $ci3_session = [
            'user_id' => $this->session->userdata('user_id'),
            'username' => $this->session->userdata('username'),
            'email' => $this->session->userdata('email'),
            'role' => $this->session->userdata('role'),
        ];

        // Output JSON response
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'message' => 'Session retrieved successfully from CI3',
            'data' => [
                'session_id' => session()->getId(),
                'session_name' => session()->getName(),
                'native_$_SESSION' => $native_session,
                'laravel_session_helper' => $laravel_session,
                'ci3_session_library' => $ci3_session,
                'session_dari_ci3' => session()->get('session_dari_ci3'),
                'session_dari_laravel' => session()->get('session_dari_laravel'),
                'all_session' => session()->all(),
                'comparison' => [
                    'all_same' => ($native_session == $laravel_session && $laravel_session == $ci3_session),
                    'note' => 'All three methods should return the same data',
                ],
            ]
        ], JSON_PRETTY_PRINT);
    }

    // akas
    public function akas($id, $name = null)
    {
        $akas = User::find($id);
        
        dd([
            'id' => $akas->toArray(),
            'name' => $name ?? 'Nama tidak diberikan',
            'message' => 'Halo dari DemoCi3 akas method',
        ]);
    }
}
