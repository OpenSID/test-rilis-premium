<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Custom Log Class untuk CodeIgniter 3
 * Override CI3 log_message() agar menulis ke Laravel logger
 */
class MY_Log extends CI_Log {

    /**
     * Write Log File
     *
     * @param   string  $level  The error level: 'error', 'debug' or 'info'
     * @param   string  $msg    The error message
     * @return  bool
     */
    public function write_log($level, $msg)
    {
        // Coba gunakan Laravel logger jika tersedia
        if (function_exists('app') && app()->bound('log')) {
            try {
                $logger = app('log');
                
                // Map CI3 log levels ke Laravel
                $levelMap = [
                    'error' => 'error',
                    'debug' => 'debug',
                    'info' => 'info',
                ];
                
                $laravelLevel = $levelMap[strtolower($level)] ?? 'info';
                
                // Tulis ke Laravel logger dengan prefix [CI3]
                $logger->{$laravelLevel}($msg);
                
                return TRUE;
            } catch (Exception $e) {
                // Jika gagal, fallback ke CI3 default logging
                return parent::write_log($level, $msg);
            }
        }
        
        // Fallback ke CI3 default logging jika Laravel belum tersedia
        return parent::write_log($level, $msg);
    }
}
