<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Laravel Session Driver for CodeIgniter 3
 * 
 * This driver bridges CI3 sessions with Laravel's session system
 */
class CI_Session_laravel_driver extends CI_Session_driver
{
    /**
     * Laravel session instance
     * @var mixed
     */
    protected $laravelSession;

    /**
     * Constructor
     *
     * @param array $params Configuration parameters
     * @return void
     */
    public function __construct(&$params)
    {
        parent::__construct($params);
        
        // Get Laravel session instance
        if (function_exists('app') && app()->bound('session')) {
            $this->laravelSession = app('session');
            
            // Start Laravel session if not started
            if (!$this->laravelSession->isStarted()) {
                $this->laravelSession->start();
            }
        }
    }

    /**
     * Open session
     *
     * @param string $save_path
     * @param string $name
     * @return bool
     */
    public function open($save_path, $name)
    {
        return TRUE;
    }

    /**
     * Close session
     *
     * @return bool
     */
    public function close()
    {
        return TRUE;
    }

    /**
     * Read session data
     *
     * @param string $session_id
     * @return string
     */
    public function read($session_id)
    {
        // Return empty string, data will be accessed directly from Laravel session
        return '';
    }

    /**
     * Write session data
     *
     * @param string $session_id
     * @param string $session_data
     * @return bool
     */
    public function write($session_id, $session_data)
    {
        // Laravel session handles persistence automatically
        return TRUE;
    }

    /**
     * Destroy session
     *
     * @param string $session_id
     * @return bool
     */
    public function destroy($session_id)
    {
        if ($this->laravelSession) {
            $this->laravelSession->flush();
        }
        
        return TRUE;
    }

    /**
     * Garbage collection
     *
     * @param int $maxlifetime
     * @return bool
     */
    public function gc($maxlifetime)
    {
        return TRUE;
    }
}
