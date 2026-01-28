<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    
    <!-- CSS dari folder assets di root -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            border-bottom: 3px solid #4CAF50;
            padding-bottom: 10px;
        }
        .message {
            background: #e8f5e9;
            padding: 15px;
            border-left: 4px solid #4CAF50;
            margin: 20px 0;
        }
        .assets-info {
            background: #fff3cd;
            padding: 15px;
            border-left: 4px solid #ffc107;
            margin: 20px 0;
        }
        .btn {
            background: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .btn:hover {
            background: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>{{ $title }}</h1>
        
        <div class="message">
            <strong>Pesan:</strong> {{ $message }}
        </div>
        
        <div class="assets-info">
            <h3>Informasi Assets</h3>
            <p>View ini menggunakan assets dari folder <code>assets/</code> di root project:</p>
            <ul>
                <li>CSS: <code>{{ asset('assets/css/style.css') }}</code></li>
                <li>JavaScript: <code>{{ asset('assets/js/script.js') }}</code></li>
                <li>Gambar SVG: <code>{{ asset('assets/images/logo.svg') }}</code></li>
            </ul>
        </div>

        <div style="margin-top: 20px;">
            <h3>Contoh Gambar dari Assets</h3>
            <!-- Contoh menggunakan gambar dari folder assets -->
            <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" style="max-width: 200px; display: block; margin: 10px 0; border: 1px solid #ddd; border-radius: 8px;">
        </div>

        <div style="margin-top: 30px;">
            <a href="{{ ci_route('baru.index') }}" class="btn">Kembali ke Index</a>
        </div>
    </div>

    <!-- JavaScript dari folder assets di root -->
    <script src="{{ asset('assets/js/script.js') }}"></script>
    
    <script>
        console.log('Demo view berhasil dimuat!');
        console.log('Title: {{ $title }}');
    </script>
</body>
</html>
