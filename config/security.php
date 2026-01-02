<?php

/*
 *
 * File ini bagian dari:
 *
 * OpenSID
 *
 * Sistem informasi desa sumber terbuka untuk memajukan desa
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * Dengan ini diberikan izin, secara gratis, kepada siapa pun yang mendapatkan salinan
 * dari perangkat lunak ini dan file dokumentasi terkait ("Aplikasi Ini"), untuk diperlakukan
 * tanpa batasan, termasuk hak untuk menggunakan, menyalin, mengubah dan/atau mendistribusikan,
 * asal tunduk pada syarat berikut:
 *
 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.
 *
 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package   OpenSID
 * @author    Tim Pengembang OpenDesa
 * @copyright Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

return [

    'enabled' => true,

    'headers' => [

        // HTTP Strict Transport Security - enforce HTTPS connections
        'Strict-Transport-Security' => implode('; ', [
            'max-age=31536000',
            'includeSubDomains',
        ]),

        // Prevent MIME type sniffing - enforce declared content types
        'X-Content-Type-Options' => 'nosniff',

        // Content Security Policy - control resource loading
        'Content-Security-Policy' => implode('; ', [
            "default-src 'self'",
            implode(' ', [
                "script-src 'self' 'unsafe-inline' 'unsafe-eval' blob:",
                // CDN & Library
                '*.jsdelivr.net *.cloudflare.com code.jquery.com cdn.ckeditor.com',
                'unpkg.com uicdn.toast.com toast.com cdn.datatables.net',
                // Google & Analytics
                '*.googleapis.com www.google.com www.gstatic.com',
                // Other domains
                '*.github.io *.facebook.net platform.twitter.com',
                // OpenDesa domain
                '*.opendesa.id',
            ]),
            "worker-src 'self' blob:",
            "child-src 'self' blob:",
            implode(' ', [
                "style-src 'self' 'unsafe-inline'",
                'fonts.googleapis.com *.gstatic.com *.jsdelivr.net *.cloudflare.com',
                'unpkg.com uicdn.toast.com toast.com cdn.datatables.net',
            ]),
            "img-src 'self' data: * *.opendesa.id",
            implode(' ', [
                "font-src 'self' data:",
                'fonts.gstatic.com *.cloudflare.com *.jsdelivr.net',
            ]),
            implode(' ', [
                "connect-src 'self'",
                'api.ipify.org *.cloudflare.com unpkg.com',
                'api.mapbox.com *.mapbox.com',
                'www.google.com www.gstatic.com',
                '*.opensid.my.id *.opendesa.id',
            ]),
            implode(' ', [
                "frame-src 'self' data:",
                // Google domains
                '*.google.com www.google.com',
                // Google static resources
                'www.gstatic.com',
            ]),
            "frame-ancestors 'self'",
            "object-src 'self' blob:",
            "base-uri 'self'",
        ]),

        // Prevent Flash from reading cross-domain policies
        'X-Permitted-Cross-Domain-Policies' => 'none',

        // Feature Permissions Policy - restrict browser features
        'Permissions-Policy' => implode(', ', [
            'accelerometer=()',
            'camera=()',
            'microphone=()',
        ]),

        // CORS & Cross-Origin Policies
        'Cross-Origin-Embedder-Policy' => 'same-origin',
        'Cross-Origin-Resource-Policy' => 'same-origin',
        'Cross-Origin-Opener-Policy'   => 'same-origin',

        // Clickjacking protection - only allow framing from same origin
        'X-Frame-Options' => 'SAMEORIGIN',
    ],

];
