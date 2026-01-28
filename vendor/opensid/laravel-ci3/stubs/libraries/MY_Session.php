<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * MY_Session - Extended Session Library
 * 
 * Override CI_Session methods to use Laravel session directly
 */
class MY_Session extends CI_Session
{
    /**
     * Laravel session instance
     * @var mixed
     */
    protected $laravelSession;
    
    /**
     * Singleton flag to prevent multiple initialization
     * @var bool
     */
    protected static $initialized = false;

    /**
     * Constructor
     *
     * @param array $params Configuration parameters
     * @return void
     */
    public function __construct($params = array())
    {
        // Get Laravel session instance
        if (function_exists('app') && app()->bound('session')) {
            $this->laravelSession = app('session');
            
            // CRITICAL: DO NOT start session here!
            // Laravel's StartSession middleware has already started the session
            
            // Register shutdown function to save Laravel session (only once)
            if (!self::$initialized) {
                register_shutdown_function(array($this, 'save_session'));
                self::$initialized = true;
            }
            
            // Don't call parent - we're completely replacing CI session with Laravel
            return;
        }
        
        // Fallback to parent if Laravel not available
        parent::__construct($params);
    }

    /**
     * Save Laravel session on shutdown
     */
    public function save_session()
    {
        if ($this->laravelSession && $this->laravelSession->isStarted()) {
            $this->laravelSession->save();
        }
    }

    /**
     * Set userdata
     */
    public function set_userdata($data, $value = NULL)
    {
        if ($this->laravelSession) {
            if (is_array($data)) {
                foreach ($data as $key => $val) {
                    $this->laravelSession->put($key, $val);
                }
            } else {
                $this->laravelSession->put($data, $value);
            }
        } else {
            parent::set_userdata($data, $value);
        }
    }

    /**
     * Get userdata
     */
    public function userdata($key = NULL)
    {
        if ($this->laravelSession) {
            if ($key === NULL) {
                return $this->laravelSession->all();
            }
            return $this->laravelSession->get($key);
        }
        
        return parent::userdata($key);
    }

    /**
     * Has userdata
     */
    public function has_userdata($key)
    {
        if ($this->laravelSession) {
            return $this->laravelSession->has($key);
        }
        
        return parent::has_userdata($key);
    }

    /**
     * Unset userdata
     */
    public function unset_userdata($key)
    {
        if ($this->laravelSession) {
            if (is_array($key)) {
                foreach ($key as $k) {
                    $this->laravelSession->forget($k);
                }
            } else {
                $this->laravelSession->forget($key);
            }
        } else {
            parent::unset_userdata($key);
        }
    }

    /**
     * Set flashdata
     */
    public function set_flashdata($data, $value = NULL)
    {
        if ($this->laravelSession) {
            if (is_array($data)) {
                foreach ($data as $key => $val) {
                    $this->laravelSession->flash($key, $val);
                }
            } else {
                $this->laravelSession->flash($data, $value);
            }
        } else {
            parent::set_flashdata($data, $value);
        }
    }

    /**
     * Get flashdata
     */
    public function flashdata($key = NULL)
    {
        if ($this->laravelSession) {
            if ($key === NULL) {
                return $this->laravelSession->all();
            }
            return $this->laravelSession->get($key);
        }
        
        return parent::flashdata($key);
    }

    /**
     * Destroy session
     */
    public function sess_destroy()
    {
        if ($this->laravelSession) {
            $this->laravelSession->flush();
            $this->laravelSession->regenerate(true);
        } else {
            parent::sess_destroy();
        }
    }

    /**
     * Regenerate session ID
     */
    public function sess_regenerate($destroy = FALSE)
    {
        if ($this->laravelSession) {
            $this->laravelSession->regenerate($destroy);
        } else {
            parent::sess_regenerate($destroy);
        }
    }

    /**
     * Magic getter - allows $this->session->key
     */
    public function __get($key)
    {
        if ($this->laravelSession) {
            return $this->laravelSession->get($key);
        }
        
        return parent::__get($key);
    }

    /**
     * Magic setter - allows $this->session->key = value
     */
    public function __set($key, $value)
    {
        if ($this->laravelSession) {
            $this->laravelSession->put($key, $value);
        } else {
            parent::__set($key, $value);
        }
    }
}
