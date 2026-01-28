<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #EE4623 0%, #C72E1A 100%);
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
            color: #EE4623;
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
            border-color: #EE4623;
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
            color: #EE4623;
            text-decoration: none;
            font-weight: bold;
            padding: 8px 16px;
            border-radius: 6px;
            transition: all 0.3s;
        }
        .nav a:hover {
            background: #EE4623;
            color: white;
        }
        .nav a.active {
            background: #EE4623;
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
            background: #fee2e2;
            padding: 20px;
            border-left: 4px solid #EE4623;
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
        <h1>🔥 Demo CodeIgniter 3</h1>
        <div>
            <span class="badge badge-ci3">CodeIgniter 3</span>
            <span class="badge badge-laravel">+ Laravel <?php echo config('app.name'); ?></span>
        </div>

        <div class="nav">
            <span style="font-weight: bold; color: #64748b;">Navigation:</span>
            <a href="/demo-laravel">Demo Laravel</a>
            <a href="/demo-ci3" class="active">Demo CI3</a>
            <a href="/demo-ci3/api">API Test</a>
        </div>

        <div class="highlight">
            <strong>🎯 Ini adalah halaman CI3 yang menggunakan semua fitur Laravel!</strong><br>
            CodeIgniter 3 dapat mengakses cache, session, database, config, dan semua fitur Laravel.
        </div>

        <h2 style="color: #1e293b; margin: 30px 0 20px;">📊 Integration Status</h2>
        <div class="grid">
            <?php foreach($examples as $example): ?>
            <div class="card">
                <h3><?php echo $example['title']; ?></h3>
                <div class="code"><?php echo $example['code']; ?></div>
                <span class="status <?php echo strpos($example['status'], '✅') !== false ? 'status-success' : 'status-warning'; ?>">
                    <?php echo $example['status']; ?>
                </span>
            </div>
            <?php endforeach; ?>
        </div>

        <h2 style="color: #1e293b; margin: 30px 0 20px;">💾 Data Examples</h2>
        
        <div class="card" style="margin-bottom: 20px;">
            <h3>📦 Cached Data (Laravel Cache from CI3)</h3>
            <div class="code">
Framework: <?php echo isset($cached_data['framework']) ? $cached_data['framework'] : 'N/A'; ?><br>
Integrated with: <?php echo isset($cached_data['integrated_with']) ? $cached_data['integrated_with'] : 'N/A'; ?><br>
Timestamp: <?php echo isset($cached_data['timestamp']) ? $cached_data['timestamp'] : 'N/A'; ?>
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
                    <td><code>demo_ci3_visit</code></td>
                    <td><?php echo session()->get('demo_ci3_visit'); ?></td>
                </tr>
                <tr>
                    <td><code>demo_laravel_visit</code></td>
                    <td><?php echo isset($session_dari_laravel) ? $session_dari_laravel : 'Not set'; ?></td>
                </tr>
                <tr>
                    <td><code>ci3_page_view</code> (CI3 Session)</td>
                    <td><?php echo isset($session_dari_ci3) ? 'Available' : 'Not available'; ?></td>
                </tr>
            </table>
            <p style="color: #64748b; font-size: 0.9em; margin-top: 10px;">
                ℹ️ Session yang di-set di CI3 dapat diakses di Laravel, dan sebaliknya.
            </p>
        </div>

        <div class="card" style="margin-bottom: 20px;">
            <h3>⚙️ Laravel Configuration (Accessed from CI3)</h3>
            <table>
                <tr>
                    <th>Config Key</th>
                    <th>Value</th>
                </tr>
                <tr>
                    <td>App Name</td>
                    <td><?php echo config('app.name'); ?></td>
                </tr>
                <tr>
                    <td>Environment</td>
                    <td><?php echo app()->environment(); ?></td>
                </tr>
                <tr>
                    <td>Debug Mode</td>
                    <td><?php echo config('app.debug') ? 'Enabled' : 'Disabled'; ?></td>
                </tr>
            </table>
        </div>

        <?php if(isset($is_logged_in) && $is_logged_in && isset($user) && $user): ?>
        <div class="card" style="margin-bottom: 20px;">
            <h3>👤 Laravel Authentication (Accessed from CI3)</h3>
            <table>
                <tr>
                    <th>Field</th>
                    <th>Value</th>
                </tr>
                <tr>
                    <td>User ID</td>
                    <td><?php echo $user->id; ?></td>
                </tr>
                <tr>
                    <td>Name</td>
                    <td><?php echo $user->name; ?></td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td><?php echo $user->email; ?></td>
                </tr>
            </table>
        </div>
        <?php endif; ?>

        <h2 style="color: #1e293b; margin: 30px 0 20px;">✅ Integration Status</h2>
        <div class="card">
            <p><strong>Session Sync:</strong> ✅ Working - Both CI3 and Laravel share session data</p>
            <p><strong>Cache Sharing:</strong> ✅ Working - Both frameworks can access same cache</p>
            <p><strong>View Rendering:</strong> ✅ Working - CI3→Blade via MY_Blade, Laravel→CI3 via CI3ViewRenderer</p>
        </div>

        <div class="footer">
            <p><strong>Integration Status:</strong> ✅ Fully Operational</p>
            <p style="margin-top: 10px;">
                CI3 dapat mengakses semua fitur Laravel melalui helper functions
            </p>
            <p style="margin-top: 10px; font-size: 0.9em;">
                Session Driver: Laravel | Cache: Laravel | Database: Both
            </p>
        </div>
    </div>
</body>
</html>
