<?php

$suspiciousPatterns = [
    'eval',
    'base64_decode',
    'system',
    'shell_exec',
    'exec',
    'passthru',
    'proc_open',
    'curl_exec',
    'fsockopen',
    'fopen',
    'file_get_contents',
    'gzinflate',
    'gzuncompress',
    'str_rot13',
    'assert',
    'preg_replace.*e',
    'ob_start',
    'php_uname',
    'chr\([0-9]{2,3}\)',
    'document\.write',
    'new\s+Function',
    '<iframe[^>]*display\s*:\s*none',
    'unserialize\(',
    'serialize\(',
    'setcookie\(',
    'header\(\s*[\"\']?Location:',
    'SELECT\s+.*\s+FROM\s+.*\s+WHERE\s+.*\$_(GET|POST|REQUEST)',
    'INSERT\s+INTO\s+.*\s+VALUES\s+.*\$_(GET|POST|REQUEST)',
    '\$_SESSION',
    '\$_SERVER\[[\'"]HTTP_REFERER[\'"]\]',
    '\$_FILES',
    '\$request->file\(',
    'route\(.*\$_',
    'redirect\(.*\$_',
    '(\$|\b)password\s*=\s*[\"\']?[a-z0-9]{1,10}[\"\']?',
];

$allowedDomains = [
    'github.com',
    'github.io',
    'gitlab.com',
    'stackoverflow.com',
    'momentjs.com',
    'packagist.org',
    'laravel.com',
    'google.com',
    'cdnjs.cloudflare.com',
    'cdn.jsdelivr.net',
    'tailwindcss.com',
    'trusted.com',
    'chartjs.org',
    'w3.org',
    'apache.org',
    'jquery.com',
    'jquery.org',
    'microsoft.com',
    'mozilla.org',
    'chromium.org',
    'example.com',
    'youtube.com',
    'facebook.com',
    'twitter.com',
    'instagram.com',
    'linkedin.com',
    'lumbungkomunitas.net',
    'opendesa.id',
    'gnu.org',
];

$extensions = ['php', 'js', 'html', 'htm'];

$excludedFiles = [
    '.php-cs-fixer.php',
    'make_obfuscate.php',
    'Obfuscator.php',
    'rector.php',
];

$scanPaths = [
    __DIR__ . '/Modules/SimpleAnjungan',
];

foreach ($scanPaths as $scanPath) {
    echo "🔍 Memindai folder: {$scanPath}\n\n";

    $rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($scanPath));

    foreach ($rii as $file) {
        if ($file->isDir()) continue;

        $filePath = $file->getRealPath();
        $fileName = $file->getFilename();

        if (in_array($fileName, $excludedFiles)) continue;

        $ext = pathinfo($filePath, PATHINFO_EXTENSION);
        if (! in_array(strtolower($ext), $extensions)) continue;

        $suspicious = scanFile($filePath, $suspiciousPatterns, $allowedDomains);
        if (! empty($suspicious)) {
            echo "⚠️  File mencurigakan: {$filePath}\n";

            foreach ($suspicious as $item) {
                echo "   ➤ {$item}\n";
            }
            echo "\n";
        }
    }

    $composerLock = rtrim($scanPath, '/') . '/composer.lock';
    if (file_exists($composerLock)) {
        $packages = checkComposerPackages($composerLock);
        if (! empty($packages)) {
            echo "⚠️  composer.lock mencurigakan: {$composerLock}\n";

            foreach ($packages as $pkg) {
                echo "   ➤ Package mencurigakan: {$pkg}\n";
            }
            echo "\n";
        }
    }
}

echo "✅ Selesai dipindai.\n";

function getDomain($url)
{
    $host = parse_url($url, PHP_URL_HOST);

    return $host ?: '';
}

function scanFile($file, $patterns, $allowedDomains)
{
    $content = @file_get_contents($file);
    if (! $content) return [];

    $results = [];

    foreach ($patterns as $pattern) {
        if (preg_match("/{$pattern}/i", $content)) {
            $results[] = "🔴 Pola mencurigakan: {$pattern}";
        }
    }

    preg_match_all('/https?:\/\/[^\s"\']+/i', $content, $matches);
    if (! empty($matches[0])) {
        foreach ($matches[0] as $url) {
            $domain  = getDomain($url);
            $allowed = false;

            foreach ($allowedDomains as $whitelist) {
                if (stripos($domain, $whitelist) !== false) {
                    $allowed = true;
                    break;
                }
            }
            if (! $allowed) {
                $results[] = "🟠 URL tidak dikenal: {$url}";
            }
        }
    }

    return $results;
}

function checkComposerPackages($composerLockPath)
{
    $contents = @file_get_contents($composerLockPath);
    if (! $contents) return [];

    $json = json_decode($contents, true);
    if (! isset($json['packages'])) return [];

    $results = [];

    foreach ($json['packages'] as $pkg) {
        $name = $pkg['name'] ?? '';
        if (preg_match('/(eval|shell|hack|exploit|backdoor)/i', $name)) {
            $results[] = $name;
        }
    }

    return $results;
}
