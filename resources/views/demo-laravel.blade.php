<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        h1 {
            color: #667eea;
            font-size: 2.5em;
            margin-bottom: 10px;
        }
        .badge {
            display: inline-block;
            padding: 8px 20px;
            border-radius: 25px;
            font-size: 0.9em;
            font-weight: bold;
            margin: 10px 10px 20px 0;
        }
        .badge-laravel { background: #FF2D20; color: white; }
        .badge-ci3 { background: #EE4623; color: white; }
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin: 30px 0;
        }
        .card {
            background: #f8fafc;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding: 25px;
            transition: all 0.3s;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            border-color: #667eea;
        }
        .card h3 {
            color: #1e293b;
            margin-bottom: 15px;
            font-size: 1.3em;
        }
        .code {
            background: #1e293b;
            color: #10b981;
            padding: 15px;
            border-radius: 8px;
            font-family: 'Courier New', monospace;
            font-size: 0.85em;
            overflow-x: auto;
            margin: 10px 0;
            line-height: 1.6;
        }
        .status {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 0.85em;
            font-weight: bold;
            margin-top: 10px;
        }
        .status-success { background: #d1fae5; color: #065f46; }
        .status-warning { background: #fef3c7; color: #92400e; }
        .nav {
            background: #f1f5f9;
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            display: flex;
            gap: 15px;
            align-items: center;
        }
        .nav a {
            color: #667eea;
            text-decoration: none;
            font-weight: bold;
            padding: 8px 16px;
            border-radius: 6px;
            transition: all 0.3s;
        }
        .nav a:hover {
            background: #667eea;
            color: white;
        }
        .nav a.active {
            background: #667eea;
            color: white;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }
        th {
            background: #f8fafc;
            font-weight: bold;
            color: #1e293b;
        }
        .highlight {
            background: #fef3c7;
            padding: 20px;
            border-left: 4px solid #f59e0b;
            border-radius: 5px;
            margin: 20px 0;
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
        <h1>🚀 Demo Laravel</h1>
        <div>
            <span class="badge badge-laravel">Laravel {{ app()->version() }}</span>
            <span class="badge badge-ci3">+ CodeIgniter 3</span>
        </div>

        <div class="nav">
            <span style="font-weight: bold; color: #64748b;">Navigation:</span>
            <a href="/demo-laravel" class="active">Demo Laravel</a>
            <a href="/demo-ci3">Demo CI3</a>
            <a href="/demo-laravel/api">API Test</a>
        </div>

        <div class="highlight">
            <strong>🎯 Ini adalah halaman Laravel yang menggunakan fitur-fitur CI3!</strong><br>
            Laravel dapat mengakses model, library, database, dan config dari CodeIgniter 3.
        </div>

        <h2 style="color: #1e293b; margin: 30px 0 20px;">📊 Integration Status</h2>
        <div class="grid">
            @foreach($examples as $example)
            <div class="card">
                <h3>{{ $example['title'] }}</h3>
                <div class="code">{{ $example['code'] }}</div>
                <span class="status {{ str_contains($example['status'], '✅') ? 'status-success' : 'status-warning' }}">
                    {{ $example['status'] }}
                </span>
            </div>
            @endforeach
        </div>

        <h2 style="color: #1e293b; margin: 30px 0 20px;">💾 Data Examples</h2>
        
        <div class="card" style="margin-bottom: 20px;">
            <h3>📦 Cached Data (Laravel Cache)</h3>
            <div class="code">
Framework: {{ $cached_data['framework'] }}<br>
Integrated with: {{ $cached_data['integrated_with'] }}<br>
Timestamp: {{ $cached_data['timestamp'] }}
            </div>
        </div>

        <div class="card" style="margin-bottom: 20px;">
            <h3>🔄 Session Synchronization</h3>
            <table>
                <tr>
                    <th>Session Key</th>
                    <th>Value</th>
                </tr>
                <tr>
                    <td><code>demo_laravel_visit</code></td>
                    <td>{{ session('demo_laravel_visit') }}</td>
                </tr>
                <tr>
                    <td><code>demo_ci3_visit</code></td>
                    <td>{{ $last_ci3_visit }}</td>
                </tr>
            </table>
            <p style="color: #64748b; font-size: 0.9em; margin-top: 10px;">
                ℹ️ Session yang di-set di Laravel dapat diakses di CI3, dan sebaliknya.
            </p>
        </div>

        <div class="card" style="margin-bottom: 20px;">
            <h3>⚙️ CI3 Configuration</h3>
            <table>
                <tr>
                    <th>Config Key</th>
                    <th>Value</th>
                </tr>
                <tr>
                    <td>Base URL</td>
                    <td>{{ $ci3_config['base_url'] ?: '(empty)' }}</td>
                </tr>
                <tr>
                    <td>Index Page</td>
                    <td>{{ $ci3_config['index_page'] }}</td>
                </tr>
                <tr>
                    <td>Session Driver</td>
                    <td><strong style="color: #10b981;">{{ $ci3_config['sess_driver'] }}</strong> ✅</td>
                </tr>
            </table>
        </div>

        @if($laravel_users->count() > 0 || count($ci3_users) > 0)
        <h2 style="color: #1e293b; margin: 30px 0 20px;">👥 Database Query Examples</h2>
        
        @if($laravel_users->count() > 0)
        <div class="card" style="margin-bottom: 20px;">
            <h3>Laravel Database Query Builder</h3>
            <div class="code">DB::table('users')->select('id', 'name', 'email')->limit(5)->get();</div>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                </tr>
                @foreach($laravel_users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                </tr>
                @endforeach
            </table>
        </div>
        @endif

        @if(count($ci3_users) > 0)
        <div class="card" style="margin-bottom: 20px;">
            <h3>CI3 Database (Accessed from Laravel)</h3>
            <div class="code">$ci->db->select('id, name, email')->from('users')->limit(5)->get()->result();</div>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                </tr>
                @foreach($ci3_users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                </tr>
                @endforeach
            </table>
        </div>
        @endif
        @endif

        <h2 style="color: #1e293b; margin: 30px 0 20px;">💡 Code Examples</h2>
        
        <div class="card">
            <h3>Laravel Controller Code</h3>
            <div class="code">
// Get CI3 instance<br>
$ci = app('ci');<br><br>
// Load CI3 Database<br>
$ci->load->database();<br>
$users = $ci->db->get('users')->result();<br><br>
// Use Laravel Cache<br>
$data = cache()->remember('key', 60, fn() => $data);<br><br>
// Session is automatically shared<br>
session(['key' => 'value']);
            </div>
        </div>

        <div class="footer">
            <p><strong>Integration Status:</strong> ✅ Fully Operational</p>
            <p style="margin-top: 10px;">
                Laravel dapat mengakses semua fitur CI3 melalui <code>app('ci')</code>
            </p>
            <p style="margin-top: 10px; font-size: 0.9em;">
                Session Driver: Laravel | Cache: Laravel | Database: Both
            </p>
        </div>
    </div>
</body>
</html>
