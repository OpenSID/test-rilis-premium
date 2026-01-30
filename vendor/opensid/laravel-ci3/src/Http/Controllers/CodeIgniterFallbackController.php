<?php

namespace OpenSID\LaravelCI3\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class CodeIgniterFallbackController extends Controller
{
    public function handle(Request $request)
    {
        // Get global CI3 instance that was bootstrapped in public/index.php
        if (!isset($GLOBALS['CI3'])) {
            abort(500, 'CI3 instance not available');
        }
        
        $CI =& $GLOBALS['CI3'];
        
        // Capture output
        ob_start();
        
        try {
            // Manually trigger CI3 routing for this request
            $CI->router->_set_request(explode('/', trim($request->path(), '/')));
            
            // Run controller
            $class  = $CI->router->class;
            $method = $CI->router->method;
            
            if (class_exists($class)) {
                $controller = new $class();
                
                if (method_exists($controller, $method)) {
                    call_user_func_array([$controller, $method], $CI->uri->rsegments);
                } else {
                    show_404();
                }
            } else {
                show_404();
            }
            
            $output = ob_get_clean();
            return response($output);
            
        } catch (\Exception $e) {
            ob_end_clean();
            throw $e;
        }
    }
}
