<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CI3 + Laravel Integration Example</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        h1 { 
            color: #667eea;
            margin-bottom: 10px;
            font-size: 2.5em;
        }
        .subtitle {
            color: #666;
            margin-bottom: 30px;
            font-size: 1.1em;
        }
        .badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.85em;
            font-weight: bold;
            margin-right: 10px;
            margin-bottom: 10px;
        }
        .badge-success { background: #10b981; color: white; }
        .badge-info { background: #3b82f6; color: white; }
        .badge-warning { background: #f59e0b; color: white; }
        
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }
        .card {
            background: #f8fafc;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            padding: 20px;
            transition: all 0.3s;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            border-color: #667eea;
        }
        .card h3 {
            color: #1e293b;
            margin-bottom: 15px;
            font-size: 1.3em;
        }
        .card p {
            color: #64748b;
            line-height: 1.6;
            margin-bottom: 10px;
        }
        .code {
            background: #1e293b;
            color: #10b981;
            padding: 15px;
            border-radius: 5px;
            font-family: 'Courier New', monospace;
            font-size: 0.9em;
            overflow-x: auto;
            margin: 10px 0;
        }
        .feature-list {
            list-style: none;
            margin-top: 15px;
        }
        .feature-list li {
            padding: 8px 0;
            border-bottom: 1px solid #e2e8f0;
            color: #475569;
        }
        .feature-list li:before {
            content: "✓ ";
            color: #10b981;
            font-weight: bold;
            margin-right: 8px;
        }
        .stats {
            display: flex;
            gap: 20px;
            margin: 30px 0;
            flex-wrap: wrap;
        }
        .stat-box {
            flex: 1;
            min-width: 150px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
        }
        .stat-box h4 {
            font-size: 2em;
            margin-bottom: 5px;
        }
        .stat-box p {
            font-size: 0.9em;
            opacity: 0.9;
        }
        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid;
        }
        .alert-success {
            background: #d1fae5;
            border-color: #10b981;
            color: #065f46;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e2e8f0;
            text-align: center;
            color: #64748b;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🎉 Laravel + CodeIgniter 3</h1>
        <p class="subtitle">Integration Example - Running Side by Side</p>
        
        <div>
            <span class="badge badge-success">Laravel 10</span>
            <span class="badge badge-info">CodeIgniter 3</span>
            <span class="badge badge-warning">Fully Integrated</span>
        </div>

        <?php if (isset($flash_message)): ?>
        <div class="alert alert-success">
            <?php echo $flash_message; ?>
        </div>
        <?php endif; ?>

        <div class="stats">
            <div class="stat-box">
                <h4><?php echo isset($stats['total_users']) ? $stats['total_users'] : 0; ?></h4>
                <p>Total Users</p>
            </div>
            <div class="stat-box">
                <h4><?php echo isset($stats['total_posts']) ? $stats['total_posts'] : 0; ?></h4>
                <p>Total Posts</p>
            </div>
            <div class="stat-box">
                <h4><?php echo isset($stats['online_users']) ? $stats['online_users'] : 0; ?></h4>
                <p>Online Users</p>
            </div>
        </div>

        <div class="grid">
            <div class="card">
                <h3>🚀 Laravel Features in CI3</h3>
                <ul class="feature-list">
                    <li>Laravel Cache</li>
                    <li>Laravel Session</li>
                    <li>Laravel Database</li>
                    <li>Laravel Logging</li>
                    <li>Laravel Auth</li>
                    <li>Laravel Storage</li>
                    <li>Laravel Validator</li>
                </ul>
                <div class="code">
                            cache()->put('key', 'value');<br>
                            app('db')->table('users')->get();
                </div>
            </div>

            <div class="card">
                <h3>🔧 CI3 Features in Laravel</h3>
                <ul class="feature-list">
                    <li>CI3 Models</li>
                    <li>CI3 Libraries</li>
                    <li>CI3 Helpers</li>
                    <li>CI3 Database</li>
                    <li>CI3 Views</li>
                    <li>CI3 Config</li>
                </ul>
                <div class="code">
                    $ci = app('ci');<br>
                    $ci-&gt;load-&gt;model('user_model');<br>
                    $ci-&gt;load-&gt;library('email');
                </div>
            </div>

            <div class="card">
                <h3>🔄 Session Synchronization</h3>
                <p>CI3 dan Laravel menggunakan session storage yang sama!</p>
                <ul class="feature-list">
                    <li>Set di Laravel, akses di CI3</li>
                    <li>Set di CI3, akses di Laravel</li>
                    <li>Flash messages tersinkron</li>
                    <li>Same session cookie</li>
                </ul>
                <div class="code">
                    // CI3<br>
                    $this-&gt;session-&gt;set_userdata('key', 'val');<br><br>
                    // Laravel<br>
                    session('key'); // 'val'
                </div>
            </div>

            <div class="card">
                <h3>🎯 Routing Fallback</h3>
                <p>Tidak perlu URL berbeda untuk Laravel dan CI3!</p>
                <ul class="feature-list">
                    <li>Request cek Laravel dulu</li>
                    <li>Jika 404 → cek CI3</li>
                    <li>Jika 404 → show 404 page</li>
                    <li>Seamless integration</li>
                </ul>
                <div class="code">
                    /users → Laravel route<br>
                    /legacy → CI3 route<br>
                    No prefix needed!
                </div>
            </div>

            <div class="card">
                <h3>📦 MY_Controller Helper</h3>
                <p>Extended CI3 controller dengan Laravel integration built-in</p>
                <ul class="feature-list">
                    <li>$this->remember() - Cache</li>
                    <li>$this->log() - Logging</li>
                    <li>$this->user() - Auth</li>
                    <li>$this->json() - JSON response</li>
                    <li>$this->validate() - Validation</li>
                </ul>
            </div>

            <div class="card">
                <h3>✨ Best Practices</h3>
                <ul class="feature-list">
                    <li>Gunakan Laravel untuk fitur baru</li>
                    <li>Keep CI3 untuk legacy code</li>
                    <li>Migrasi bertahap ke Laravel</li>
                    <li>Share session & cache</li>
                    <li>Use Laravel logging</li>
                    <li>Leverage both frameworks</li>
                </ul>
            </div>
        </div>

        <div style="margin-top: 40px; padding: 30px; background: #f1f5f9; border-radius: 8px;">
            <h3 style="color: #1e293b; margin-bottom: 15px;">📝 Quick Example</h3>
            
            <p style="color: #475569; margin-bottom: 15px;"><strong>Dari CI3 Controller:</strong></p>
            <div class="code">
class Products extends MY_Controller {
    public function index() {
        // Cache dengan Laravel
        $products = $this->remember('products', function() {
            return $this->db->get('products')->result();
        }, 3600);
        
        // Log dengan Laravel
        $this->log('Products viewed by ' . $this->user()->name);
        
        // Load view
        $this->load->view('products', compact('products'));
    }
}
            </div>

            <p style="color: #475569; margin: 20px 0 15px;"><strong>Dari Laravel Controller:</strong></p>
            <div class="code">
public function legacy() {
    $ci = app('ci');
    $ci->load->model('legacy_model');
    $data = $ci->legacy_model->get_data();
    
    return view('modern', compact('data'));
}
            </div>
        </div>

        <div class="footer">
            <p><strong>Integration Status:</strong> ✅ Fully Operational</p>
            <p style="margin-top: 10px;">Laravel <?php echo app()->version(); ?> + CodeIgniter 3</p>
            <p style="margin-top: 5px; font-size: 0.9em;">
                Session Driver: Laravel | 
                Cache: Laravel | 
                Routing: Fallback Enabled
            </p>
        </div>
    </div>
</body>
</html>
