<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Simple MY_Session - Bridges CI3 session with Laravel
 */
class MY_Session extends CI_Session
{
    protected $laravelSession;

    public function __construct($params = array())
    {
        // Use Laravel session if available
        if (function_exists('app') && app()->bound('session')) {
            $this->laravelSession = app('session');
            if (!$this->laravelSession->isStarted()) {
                $this->laravelSession->start();
            }
            return;
        }
        
        // Fallback to parent CI session
        parent::__construct($params);
    }

    // Intercept CI session methods and use Laravel session
    
    public function userdata($key = NULL)
    {
        if ($this->laravelSession) {
            return $key === NULL ? $this->laravelSession->all() : $this->laravelSession->get($key);
        }
        return parent::userdata($key);
    }

    public function set_userdata($data, $value = NULL)
    {
        if ($this->laravelSession) {
            if (is_array($data)) {
                foreach ($data as $k => $v) {
                    $this->laravelSession->put($k, $v);
                }
            } else {
                $this->laravelSession->put($data, $value);
            }
            return $this;
        }
        return parent::set_userdata($data, $value);
    }

    public function unset_userdata($key)
    {
        if ($this->laravelSession) {
            $this->laravelSession->forget($key);
            return $this;
        }
        return parent::unset_userdata($key);
    }

    public function has_userdata($key)
    {
        if ($this->laravelSession) {
            return $this->laravelSession->has($key);
        }
        return parent::has_userdata($key);
    }

    public function flashdata($key = NULL)
    {
        if ($this->laravelSession) {
            return $key === NULL ? $this->laravelSession->get('_flash', []) : $this->laravelSession->get("_flash.{$key}");
        }
        return parent::flashdata($key);
    }

    public function set_flashdata($data, $value = NULL)
    {
        if ($this->laravelSession) {
            if (is_array($data)) {
                foreach ($data as $k => $v) {
                    $this->laravelSession->flash($k, $v);
                }
            } else {
                $this->laravelSession->flash($data, $value);
            }
            return $this;
        }
        return parent::set_flashdata($data, $value);
    }

    public function sess_destroy()
    {
        if ($this->laravelSession) {
            $this->laravelSession->flush();
            return;
        }
        parent::sess_destroy();
    }

    public function sess_regenerate($destroy = FALSE)
    {
        if ($this->laravelSession) {
            $this->laravelSession->regenerate($destroy);
            return;
        }
        parent::sess_regenerate($destroy);
    }
}
