<?php

namespace App\Helpers;

use Carbon\Carbon;

/**
 * Class DateHelper
 * Helper untuk format tanggal Indonesia
 */
class DateHelper
{
    protected static array $bulan = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    ];

    protected static array $bulanShort = [
        1 => '01', 2 => '02', 3 => '03', 4 => '04',
        5 => '05', 6 => '06', 7 => '07', 8 => '08',
        9 => '09', 10 => '10', 11 => '11', 12 => '12'
    ];

    protected static array $mediumBulan = [
        1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
        5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Ags',
        9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'
    ];

    /**
     * Format tanggal menjadi "d F Y"
     */
    public static function dateIndo(string $date): string
    {
        $dt = Carbon::parse($date);
        return $dt->format('d') . ' ' . self::$bulan[$dt->month] . ' ' . $dt->year;
    }

    /**
     * Format tanggal menjadi "d/m/Y"
     */
    public static function shortDateIndo(string $date): string
    {
        $dt = Carbon::parse($date);
        return $dt->format('d') . '/' . self::$bulanShort[$dt->month] . '/' . $dt->year;
    }

    /**
     * Format tanggal medium "d-M-Y"
     */
    public static function mediumDateIndo(string $date): string
    {
        $dt = Carbon::parse($date);
        return $dt->format('d') . '-' . self::$mediumBulan[$dt->month] . '-' . $dt->year;
    }

    /**
     * Format tanggal panjang "Hari, d F Y"
     */
    public static function longDateIndo(string $date): string
    {
        $dt = Carbon::parse($date);
        $days = [
            'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat',
            'Saturday' => 'Sabtu'
        ];
        $hari = $days[$dt->format('l')] ?? '';
        return $hari . ', ' . $dt->format('d') . ' ' . self::$bulan[$dt->month] . ' ' . $dt->year;
    }

    /**
     * Array bulan lengkap
     */
    public static function bulanArray(): array
    {
        $arr = [];
        foreach (range(1, 12) as $i) {
            $arr[] = [
                'urut' => $i,
                'nama_pendek' => self::$mediumBulan[$i],
                'nama_panjang' => self::$bulan[$i],
            ];
        }
        return $arr;
    }
}
