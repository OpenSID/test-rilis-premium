<?php

namespace OpenSID\LaravelCI3\Services;

use Illuminate\Contracts\Support\Responsable;

/**
 * Simple CI3 View Renderer
 */
class CI3ViewRenderer implements Responsable
{
    protected $view;
    protected $data;

    public function __construct($view, array $data = [])
    {
        $this->view = $view;
        $this->data = $data;
    }

    public static function make($view, array $data = [])
    {
        return new static($view, $data);
    }

    public function render()
    {
        // Get CI3 instance - try multiple ways
        $ci = $this->getCI3Instance();
        
        if ($ci && isset($ci->load)) {
            try {
                return $ci->load->view($this->view, $this->data, true);
            } catch (\Exception $e) {
                // Fall through to direct file loading
            }
        }

        // Fallback: load view file directly
        return $this->loadViewDirect();
    }

    private function getCI3Instance()
    {
        // Try Laravel container first
        try {
            $ci = app('ci');
            if (method_exists($ci, 'getInstance')) {
                return $ci->getInstance();
            }
            return $ci;
        } catch (\Exception $e) {
            // Try global CI3 instance
            return isset($GLOBALS['CI3']) ? $GLOBALS['CI3'] : null;
        }
    }

    private function loadViewDirect()
    {
        $viewPath = APPPATH . 'views/' . $this->view . '.php';
        
        if (!file_exists($viewPath)) {
            throw new \RuntimeException("View file not found: {$this->view}");
        }

        extract($this->data);
        ob_start();
        include $viewPath;
        return ob_get_clean();
    }

    public function __toString()
    {
        try {
            return $this->render();
        } catch (\Exception $e) {
            return 'View Error: ' . $e->getMessage();
        }
    }

    public function toResponse($request)
    {
        return response($this->render());
    }

    public function with($key, $value = null)
    {
        if (is_array($key)) {
            $this->data = array_merge($this->data, $key);
        } else {
            $this->data[$key] = $value;
        }
        return $this;
    }
}