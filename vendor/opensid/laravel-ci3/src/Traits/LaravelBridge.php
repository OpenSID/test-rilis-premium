<?php

namespace OpenSID\LaravelCI3\Traits;

/**
 * Trait to bridge Laravel helpers into CI3 controllers.
 */
trait LaravelBridge
{
    /** @var mixed */
    protected $laravel;
    /** @var mixed */
    protected $cache;
    /** @var mixed */
    protected $laravel_db;

    /**
     * Initialize Laravel bindings.
     */
    protected function initLaravelBridge(): void
    {
        $this->laravel    = function_exists('app') ? app() : null;
        $this->cache      = function_exists('cache') ? cache() : null;
        $this->laravel_db = function_exists('app') ? app('db') : null;
    }

    protected function config($key, $default = null)
    {
        return function_exists('config') ? config($key, $default) : $default;
    }

    protected function remember($key, $callback = null, $ttl = 3600)
    {
        if ($this->cache === null) {
            return null;
        }
        if ($callback === null) {
            return $this->cache->get($key);
        }
        return $this->cache->remember($key, $ttl, $callback);
    }

    protected function log($message, $level = 'info', $context = []): void
    {
        if (function_exists('logger')) {
            logger()->{$level}($message, $context);
        }
    }

    protected function user()
    {
        return function_exists('auth') ? auth()->user() : null;
    }

    protected function is_logged_in(): bool
    {
        return function_exists('auth') ? auth()->check() : false;
    }

    protected function redirect_to($url): void
    {
        header('Location: ' . $url);
        exit;
    }

    protected function json($data, $status = 200): void
    {
        $this->output
            ->set_status_header($status)
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
            ->_display();
        exit;
    }

    protected function validate($data, $rules, $messages = [])
    {
        $validator = function_exists('validator') ? validator($data, $rules, $messages) : null;
        if ($validator && $validator->fails()) {
            return $validator;
        }
        return $validator ? true : false;
    }

    protected function request($key = null, $default = null)
    {
        if (!function_exists('request')) {
            return $default;
        }
        if ($key === null) {
            return request();
        }
        return request($key, $default);
    }

    protected function store_file($path, $content, $disk = null)
    {
        if (!function_exists('app')) {
            return false;
        }
        $storage = app('filesystem')->disk($disk);
        return $storage->put($path, $content);
    }

    protected function get_file($path, $disk = null)
    {
        if (!function_exists('app')) {
            return null;
        }
        $storage = app('filesystem')->disk($disk);
        return $storage->get($path);
    }

    protected function flash($type, $message): void
    {
        $this->session->set_flashdata('flash_' . $type, $message);
        if (function_exists('session')) {
            session()->flash('flash_' . $type, $message);
        }
    }

    protected function get_flash($type)
    {
        return $this->session->flashdata('flash_' . $type);
    }
}
