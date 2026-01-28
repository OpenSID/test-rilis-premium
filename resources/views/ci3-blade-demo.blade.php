<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'CI3 Rendering Blade Template' }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .container { 
            max-width: 1200px; 
            margin: 0 auto; 
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            padding: 40px;
            text-align: center;
        }
        .header h1 { font-size: 2.5em; margin-bottom: 10px; }
        .header p { font-size: 1.2em; opacity: 0.9; }
        .badge {
            display: inline-block;
            padding: 8px 20px;
            background: rgba(255,255,255,0.25);
            border-radius: 25px;
            font-size: 0.95em;
            margin: 8px;
            font-weight: 600;
        }
        .highlight {
            background: rgba(255,255,255,0.4);
            padding: 15px 30px;
            border-radius: 8px;
            margin: 20px auto;
            display: inline-block;
            font-size: 1.1em;
        }
        .content { padding: 40px; }
        .card {
            background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
            border-radius: 12px;
            padding: 30px;
            margin: 25px 0;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .card h3 {
            color: #c13584;
            margin-bottom: 20px;
            font-size: 1.8em;
            text-align: center;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin: 20px 0;
        }
        .info-item {
            background: white;
            padding: 25px;
            border-radius: 10px;
            border: 3px solid #f093fb;
            text-align: center;
        }
        .info-item strong {
            color: #c13584;
            display: block;
            margin-bottom: 12px;
            font-size: 1em;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .info-item span {
            color: #495057;
            font-size: 1.2em;
            font-weight: 600;
        }
        .success-box {
            background: #d4edda;
            border: 3px solid #28a745;
            color: #155724;
            padding: 25px;
            border-radius: 12px;
            margin: 25px 0;
            text-align: center;
        }
        .success-box h3 {
            color: #28a745;
            font-size: 2em;
            margin-bottom: 15px;
        }
        .success-box p {
            font-size: 1.2em;
            line-height: 1.6;
        }
        .footer {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 40px;
            text-align: center;
        }
        .footer p {
            font-size: 1.1em;
            margin: 5px 0;
        }
        .footer strong {
            color: #ffecd2;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎨 {{ $title ?? 'CI3 + Blade Magic' }}</h1>
            <p>CodeIgniter 3 Controller Rendering Laravel Blade Template!</p>
            <div class="highlight">
                <strong>🔥 This is AMAZING! 🔥</strong>
            </div>
            <div>
                <span class="badge">🚀 CI3 Controller</span>
                <span class="badge">⚡ Blade Template</span>
                <span class="badge">💫 Full Integration</span>
            </div>
        </div>

        <div class="content">
            <div class="success-box">
                <h3>✅ Integration Successful!</h3>
                <p>CodeIgniter 3 controller is now rendering a Laravel Blade template!</p>
                <p>You're using <strong>Blade syntax</strong> with all its power from within CI3.</p>
            </div>

            <div class="card">
                <h3>📊 View Information</h3>
                <div class="info-grid">
                    <div class="info-item">
                        <strong>🎯 Controller Type</strong>
                        <span>CodeIgniter 3</span>
                    </div>
                    <div class="info-item">
                        <strong>🎨 Template Engine</strong>
                        <span>Laravel Blade</span>
                    </div>
                    <div class="info-item">
                        <strong>🔧 Rendering Method</strong>
                        <span>MY_Blade Library</span>
                    </div>
                    <div class="info-item">
                        <strong>⚡ Performance</strong>
                        <span>Fully Cached</span>
                    </div>
                </div>
            </div>

            <div class="card">
                <h3>💡 What This Means</h3>
                <div class="info-grid">
                    <div class="info-item">
                        <strong>{{ '{{' }} Blade Syntax }}</strong>
                        <span>✅ Working</span>
                    </div>
                    <div class="info-item">
                        <strong>&#64;foreach Loops</strong>
                        <span>✅ Available</span>
                    </div>
                    <div class="info-item">
                        <strong>&#64;if Conditions</strong>
                        <span>✅ Functional</span>
                    </div>
                    <div class="info-item">
                        <strong>Components</strong>
                        <span>✅ Supported</span>
                    </div>
                </div>
            </div>

            @if(isset($message))
            <div class="card" style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);">
                <h3>📨 Message from Controller</h3>
                <div style="text-align: center; padding: 20px;">
                    <p style="font-size: 1.3em; color: #c13584;">{{ $message }}</p>
                </div>
            </div>
            @endif
        </div>

        <div class="footer">
            <p><strong>View Engine:</strong> Laravel Blade (.blade.php)</p>
            <p><strong>Controller:</strong> CodeIgniter 3 with MY_Blade Library</p>
            <p><strong>🎉 Powered by:</strong> CI3 + Laravel Perfect Integration</p>
        </div>
    </div>
</body>
</html>
