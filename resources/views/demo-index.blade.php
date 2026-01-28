<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel + CI3 Integration Demo</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 40px 20px;
        }
        .container { 
            max-width: 1400px; 
            margin: 0 auto;
        }
        .header {
            text-align: center;
            color: white;
            margin-bottom: 50px;
        }
        .header h1 {
            font-size: 3.5em;
            margin-bottom: 15px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        .header p {
            font-size: 1.3em;
            opacity: 0.95;
        }
        .demo-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 30px;
            margin: 30px 0;
        }
        .demo-card {
            background: white;
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            transition: all 0.3s;
            border-top: 5px solid transparent;
        }
        .demo-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.3);
        }
        .demo-card.ci3 {
            border-top-color: #f5576c;
        }
        .demo-card.laravel {
            border-top-color: #2f80ed;
        }
        .demo-card.mixed {
            border-top-color: #f093fb;
        }
        .demo-card h2 {
            font-size: 1.8em;
            margin-bottom: 15px;
            color: #333;
        }
        .demo-card p {
            color: #666;
            line-height: 1.6;
            margin-bottom: 20px;
        }
        .badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85em;
            margin: 5px 5px 15px 0;
            font-weight: 600;
        }
        .badge.ci3 {
            background: #ffe5e9;
            color: #c13584;
        }
        .badge.laravel {
            background: #d1ecf1;
            color: #0c5460;
        }
        .badge.blade {
            background: #f8d7da;
            color: #721c24;
        }
        .badge.view {
            background: #d4edda;
            color: #155724;
        }
        .demo-link {
            display: inline-block;
            padding: 12px 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
            margin-top: 10px;
        }
        .demo-link:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
            transform: scale(1.05);
        }
        .section-title {
            color: white;
            font-size: 2em;
            margin: 50px 0 30px;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .features {
            background: white;
            border-radius: 16px;
            padding: 40px;
            margin: 40px 0;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        .features h3 {
            color: #667eea;
            font-size: 2em;
            margin-bottom: 25px;
            text-align: center;
        }
        .feature-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }
        .feature-item {
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
            border-left: 4px solid #667eea;
        }
        .feature-item strong {
            color: #667eea;
            display: block;
            margin-bottom: 8px;
            font-size: 1.1em;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🚀 Laravel + CI3 Integration</h1>
            <p>Complete Views & Session Integration Demo</p>
        </div>

        <div class="features">
            <h3>✨ Fitur Terintegrasi</h3>
            <div class="feature-list">
                <div class="feature-item">
                    <strong>🔄 Session Sync</strong>
                    Session data bisa dibaca/tulis dari Laravel maupun CI3
                </div>
                <div class="feature-item">
                    <strong>💾 Cache Sharing</strong>
                    Laravel Cache bisa diakses dari CI3 controller
                </div>
                <div class="feature-item">
                    <strong>🎨 View Integration</strong>
                    CI3 bisa render Blade, Laravel bisa render CI3 views
                </div>
                <div class="feature-item">
                    <strong>📚 Helper Functions</strong>
                    ci3_view() dan blade_view() tersedia di kedua framework
                </div>
            </div>
        </div>

        <h2 class="section-title">📋 View Integration Demos</h2>
        
        <div class="demo-grid">
            <!-- CI3 Traditional View -->
            <div class="demo-card ci3">
                <h2>🔴 CI3 → CI3 View</h2>
                <div>
                    <span class="badge ci3">CI3 Controller</span>
                    <span class="badge view">CI3 View (.php)</span>
                </div>
                <p>CodeIgniter 3 controller rendering traditional CI3 view dengan PHP syntax.</p>
                <a href="/demo-ci3" class="demo-link">Lihat Demo →</a>
            </div>

            <!-- CI3 Rendering Blade -->
            <div class="demo-card mixed">
                <h2>🎨 CI3 → Blade View</h2>
                <div>
                    <span class="badge ci3">CI3 Controller</span>
                    <span class="badge blade">Blade Template</span>
                </div>
                <p>CodeIgniter 3 controller rendering Laravel Blade template menggunakan MY_Blade library.</p>
                <a href="/demo-ci3/blade" class="demo-link">Lihat Demo →</a>
            </div>

            <!-- Laravel Blade View -->
            <div class="demo-card laravel">
                <h2>🔵 Laravel → Blade View</h2>
                <div>
                    <span class="badge laravel">Laravel Controller</span>
                    <span class="badge blade">Blade Template</span>
                </div>
                <p>Laravel controller rendering Blade template - cara standard Laravel.</p>
                <a href="/demo-laravel" class="demo-link">Lihat Demo →</a>
            </div>

            <!-- Laravel Rendering CI3 View -->
            <div class="demo-card mixed">
                <h2>💜 Laravel → CI3 View</h2>
                <div>
                    <span class="badge laravel">Laravel Controller</span>
                    <span class="badge view">CI3 View (.php)</span>
                </div>
                <p>Laravel controller rendering CI3 traditional view menggunakan ci3_view() helper.</p>
                <a href="/demo-laravel/ci3-view" class="demo-link">Lihat Demo →</a>
            </div>
        </div>

        <h2 class="section-title">🔐 Session Integration Demos</h2>
        
        <div class="demo-grid">
            <!-- Session Set Laravel -->
            <div class="demo-card laravel">
                <h2>Set Session (Laravel)</h2>
                <div>
                    <span class="badge laravel">Laravel</span>
                    <span class="badge">JSON API</span>
                </div>
                <p>Set session data dari Laravel, akan bisa dibaca di CI3.</p>
                <a href="/demo-laravel/session-set" class="demo-link">Set Session →</a>
            </div>

            <!-- Session Get Laravel -->
            <div class="demo-card laravel">
                <h2>Get Session (Laravel)</h2>
                <div>
                    <span class="badge laravel">Laravel</span>
                    <span class="badge">JSON API</span>
                </div>
                <p>Baca session data di Laravel, termasuk yang diset dari CI3.</p>
                <a href="/demo-laravel/session-get" class="demo-link">Get Session →</a>
            </div>

            <!-- Session Set CI3 -->
            <div class="demo-card ci3">
                <h2>Set Session (CI3)</h2>
                <div>
                    <span class="badge ci3">CI3</span>
                    <span class="badge">JSON API</span>
                </div>
                <p>Set session data dari CI3, akan bisa dibaca di Laravel.</p>
                <a href="/demo-ci3/session-set" class="demo-link">Set Session →</a>
            </div>

            <!-- Session Get CI3 -->
            <div class="demo-card ci3">
                <h2>Get Session (CI3)</h2>
                <div>
                    <span class="badge ci3">CI3</span>
                    <span class="badge">JSON API</span>
                </div>
                <p>Baca session data di CI3, termasuk yang diset dari Laravel.</p>
                <a href="/demo-ci3/session-get" class="demo-link">Get Session →</a>
            </div>
        </div>

        <div class="features" style="margin-top: 60px;">
            <h3>📖 Cara Penggunaan</h3>
            <div class="feature-list">
                <div class="feature-item">
                    <strong>Di CI3 Controller:</strong>
                    <code style="display:block; padding:10px; background:white; margin-top:10px; border-radius:4px; font-family: monospace;">
                        // Render Blade<br>
                        $this->load->library('my_blade');<br>
                        $this->my_blade->render('view-name', $data);
                    </code>
                </div>
                <div class="feature-item">
                    <strong>Di Laravel Controller:</strong>
                    <code style="display:block; padding:10px; background:white; margin-top:10px; border-radius:4px; font-family: monospace;">
                        // Render CI3 View<br>
                        return ci3_view('view-name', $data);
                    </code>
                </div>
                <div class="feature-item">
                    <strong>Session Anywhere:</strong>
                    <code style="display:block; padding:10px; background:white; margin-top:10px; border-radius:4px; font-family: monospace;">
                        // CI3 & Laravel sama<br>
                        session()->put('key', 'value');<br>
                        $value = session('key');
                    </code>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
