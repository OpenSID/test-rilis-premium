<?php

use Carbon\Carbon;

if (!function_exists('tgl_indo')) {
    /**
     * Format tanggal ke format Indonesia: 2025-11-04 -> 04 November 2025
     *
     * @param string|Carbon $tanggal
     * @return string
     */
    function tgl_indo($tanggal)
    {
        if (!$tanggal) {
            return '';
        }

        if ($tanggal instanceof Carbon) {
            $tgl = $tanggal;
        } else {
            $tgl = Carbon::parse($tanggal);
        }

        $bulan = [
            1  => 'Januari',
            2  => 'Februari',
            3  => 'Maret',
            4  => 'April',
            5  => 'Mei',
            6  => 'Juni',
            7  => 'Juli',
            8  => 'Agustus',
            9  => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        return $tgl->day . ' ' . $bulan[(int) $tgl->month] . ' ' . $tgl->year;
    }
}

if (!function_exists('tgl_mysql')) {
    /**
     * Konversi tanggal dari format Indonesia ke MySQL: 04-11-2025 -> 2025-11-04
     *
     * @param string $tanggal
     * @return string|null
     */
    function tgl_mysql($tanggal)
    {
        if (!$tanggal) {
            return null;
        }

        try {
            return Carbon::createFromFormat('d-m-Y', $tanggal)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }
}

if (!function_exists('bulan_indo')) {
    /**
     * Ambil nama bulan Indonesia dari angka
     *
     * @param int $bulan
     * @return string
     */
    function bulan_indo($bulan)
    {
        $nama = [
            1  => 'Januari',
            2  => 'Februari',
            3  => 'Maret',
            4  => 'April',
            5  => 'Mei',
            6  => 'Juni',
            7  => 'Juli',
            8  => 'Agustus',
            9  => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        return $nama[$bulan] ?? '';
    }
}
