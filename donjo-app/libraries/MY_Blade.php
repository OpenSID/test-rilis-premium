<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * MY_Blade - Laravel Blade Template Engine for CodeIgniter 3
 * 
 * Allows CI3 controllers to render Laravel Blade templates
 */
class MY_Blade
{
    /**
     * Render a Blade template
     *
     * @param string $view View name (without .blade.php)
     * @param array $data Data to pass to view
     * @param bool $return Whether to return the output or display it
     * @return string|void
     */
    public function render($view, $data = [], $return = false)
    {
        if (!function_exists('view')) {
            show_error('Laravel Blade not available. Make sure Laravel is bootstrapped.');
            return '';
        }

        try {
            // Use Laravel's view() helper to render Blade template
            $output = view($view, $data)->render();
            
            if ($return) {
                return $output;
            }
            
            // Output directly
            echo $output;
            
        } catch (\Exception $e) {
            show_error('Blade Error: ' . $e->getMessage());
        }
    }

    /**
     * Make a view instance (for advanced usage)
     *
     * @param string $view View name
     * @param array $data Data to pass
     * @return \Illuminate\View\View|null
     */
    public function make($view, $data = [])
    {
        if (!function_exists('view')) {
            show_error('Laravel Blade not available.');
            return null;
        }

        return view($view, $data);
    }

    /**
     * Check if a Blade view exists
     *
     * @param string $view View name
     * @return bool
     */
    public function exists($view)
    {
        if (!function_exists('view')) {
            return false;
        }

        try {
            return view()->exists($view);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Share data with all views
     *
     * @param string|array $key Key or array of data
     * @param mixed $value Value if $key is string
     * @return void
     */
    public function share($key, $value = null)
    {
        if (!function_exists('view')) {
            return;
        }

        if (is_array($key)) {
            view()->share($key);
        } else {
            view()->share($key, $value);
        }
    }
}
