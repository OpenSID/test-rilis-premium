<?php

namespace OpenSID\LaravelCI3;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CodeIgniterFallback
{
    /**
     * Handle incoming request - try CI3 if Laravel returns 404
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (app()->runningInConsole()) {
            return $next($request);
        }
        
        if ($request->route()?->getName()) {
            return $next($request);
        }
        
        $response = $next($request);
        
        if ($response->status() === 404) {
            $ci3Response = $this->tryCodeIgniter($request);
            if ($ci3Response !== null) {
                $ci3Response->setStatusCode(200);
                return config('app.debug') ? $this->applyDebugBar($ci3Response) : $ci3Response;
            }
        }
        
        return $response;
    }

    /**
     * Try to handle request with CodeIgniter 3
     *
     * @param Request $request
     * @return Response|null
     */
    protected function tryCodeIgniter(Request $request)
    {
        try {
            // Get path dari request, Laravel sudah clean dari /index.php
            $requestUri = $request->getRequestUri();
            $uriParts = explode('?', $requestUri);
            $path = trim($uriParts[0], '/');
            
            // Get global CI3 instance
            if (!isset($GLOBALS['CI3'])) {
                return null;
            }
            
            $CI =& $GLOBALS['CI3'];
            
            // Capture output
            ob_start();
            
            try {
                // Set up environment for CI3 request dengan path yang bersih
                $_SERVER['REQUEST_URI'] = $requestUri;
                $_SERVER['REQUEST_METHOD'] = $request->method();
                $_GET = $request->query->all();
                $_POST = $request->request->all();
                $_COOKIE = $request->cookies->all();
                $_FILES = $request->files->all();
                
                // Re-initialize URI and Router with new request
                $CI->uri = load_class('URI', 'core');
                $CI->router = load_class('Router', 'core');
                
                // Manually trigger routing by calling Router->_compile_routes()
                // This will match the current URI to routes
                if (method_exists($CI->router, '_compile_routes')) {
                    $CI->router->_compile_routes();
                }
                
                // Path sudah bersih dari Laravel (e.g., "internal_api/galeri")
                $pathSegments = explode('/', $path);
                $modulePath = config('ci3.modules_path', base_path('Modules'));
                $isModuleRoute = false;
                $moduleRouteMatch = null;
                
                // Try to match against module routes first
                if (!empty($pathSegments[0]) && is_dir($modulePath . '/' . ucfirst($pathSegments[0]))) {
                    $moduleRouter = new \OpenSID\ModuleRouter($modulePath);
                    $moduleRouteMatch = $moduleRouter->match($path);
                    
                    if ($moduleRouteMatch) {
                        $isModuleRoute = true;
                    } else {
                        $isModuleRoute = true;
                    }
                }
                
                // Get controller and method from module route or CI3 router
                if ($isModuleRoute && $moduleRouteMatch) {
                    // Parse module route target (e.g., 'ContohController@index')
                    $parsed = (new \OpenSID\ModuleRouter($modulePath))->parseTarget(
                        $moduleRouteMatch['target'],
                        $moduleRouteMatch['params']
                    );
                    
                    $directory = '';
                    $class = $parsed['controller'];
                    $method = $parsed['method'];
                    $params = $moduleRouteMatch['params'] ?? [];
                } elseif ($isModuleRoute) {
                    // Fallback: try to extract from path
                    $moduleName = ucfirst($pathSegments[0]);
                    $directory = '';
                    $class = $moduleName . 'Controller';
                    $method = $pathSegments[1] ?? 'index';
                    $params = array_slice($pathSegments, 2);
                } else {
                    // Use CI3 router for non-module routes
                    $directory = $CI->router->fetch_directory();
                    $class = $CI->router->fetch_class();
                    $method = $CI->router->fetch_method();
                    $params = array_slice($CI->uri->rsegments, 2);
                }
                
                // Load the controller file - support both CI3 path and module path
                $controllerFile = APPPATH . 'controllers/' . $directory . ucfirst($class) . '.php';
                
                // For module routes, check in Modules directory
                if ($isModuleRoute && !file_exists($controllerFile)) {
                    $moduleName = $moduleRouteMatch['module'] ?? ucfirst($pathSegments[0]);
                    $moduleControllerFile = $modulePath . '/' . $moduleName . '/Http/Controllers/' . ucfirst($class) . '.php';
                    
                    if (file_exists($moduleControllerFile)) {
                        $controllerFile = $moduleControllerFile;
                    }
                }
                
                // Also check in module if not found in ci3 app (for other cases)
                $moduleControllerFile = null;
                if (!file_exists($controllerFile) && !empty($class)) {
                    $modulePath = config('ci3.modules_path', base_path('Modules'));
                    
                    // First check if class matches a module name (e.g., class='contoh' in route 'contoh/index')
                    $potentialModule = str_replace('Controller', '', ucfirst($class));
                    if (is_dir($modulePath . '/' . $potentialModule)) {
                        // This is likely a module controller
                        // Look for {Module}Controller.php in Http/Controllers folder
                        $moduleControllerFile = $modulePath . '/' . $potentialModule . '/Http/Controllers/' . ucfirst($class) . '.php';
                        
                        if (file_exists($moduleControllerFile)) {
                            $controllerFile = $moduleControllerFile;
                        }
                    }
                }
                
                require_once $controllerFile;
                
                // Check controller class
                $controllerClass = ucfirst($class);
                if (!class_exists($controllerClass)) {
                    ob_end_clean();
                    return null;
                }
                
                
                // Call the method on existing CI instance but as the right controller class
                // Update router class/method info
                $CI->router->set_class($class);
                $CI->router->set_method($method);
                if (!empty($directory)) {
                    $CI->router->set_directory($directory);
                }
                
                // Instantiate the controller - this inherits from CI_Controller
                // so it will have access to all loaded CI resources
                $controller = new $controllerClass();
                
                // CRITICAL: Update global instance to this controller
                // This ensures get_instance() returns the actual controller instance
                $GLOBALS['CI3'] = $controller;
                
                // Assign MY_Session instance directly (don't use Loader to avoid ini_set error)
                if (!isset($controller->session)) {
                    
                    // Load MY_Session class files if not loaded
                    if (!class_exists('MY_Session', false)) {
                        require_once BASEPATH . 'libraries/Session/Session_driver.php';
                        require_once BASEPATH . 'libraries/Session/Session.php';
                        require_once APPPATH . 'libraries/MY_Session.php';
                    }
                    
                    // Assign session instance directly (bypasses Loader and parent constructor issues)
                    $controller->session = new \MY_Session();
                }
                
                // Check method exists
                if (!method_exists($controller, $method)) {
                    ob_end_clean();
                    return null;
                }
                
                $returnValue = null;
                try {
                    // Call the requested method and capture return value
                    $returnValue = call_user_func_array([$controller, $method], $params);
                } catch (\Exception $methodException) {
                    throw $methodException;
                }
                
                // CRITICAL: Save session after CI3 controller execution
                // This ensures any session changes made in CI3 are persisted
                // We use both methods to ensure session is saved:
                // 1. Through MY_Session if available
                if (isset($controller->session) && method_exists($controller->session, 'save_session')) {
                    $controller->session->save_session();
                }
                // 2. Directly through Laravel session
                if (app()->bound('session')) {
                    app('session')->save();
                }
                
                // Get output (either from echo or return)
                $output = ob_get_clean();
                
                // Handle different return types from CI3 controller
                // Priority: Response objects > View objects > String output > Buffered output
                
                // 1. If returned any Laravel Response (JsonResponse, RedirectResponse, etc.)
                if ($returnValue instanceof \Symfony\Component\HttpFoundation\Response) {
                    return $returnValue;
                }
                
                // 2. If returned a View object, render it
                if ($returnValue instanceof \Illuminate\View\View) {
                    $output = $returnValue->render();
                }
                
                // 3. If returned a string, use it as output
                if (is_string($returnValue) && !empty($returnValue)) {
                    $output = $returnValue;
                }
                
                // Return response
                if (!empty($output)) {
                    // Check if output is HTML
                    if (stripos($output, '<!DOCTYPE') !== false || stripos($output, '<html') !== false) {
                        // For HTML output, return as-is but cast to Illuminate Response
                        // which will trigger DebugBar injection via middleware
                        $response = response($output, 200)
                            ->header('Content-Type', 'text/html; charset=UTF-8');
                    } else {
                        // For JSON or other content
                        $response = response($output, 200);
                    }
                    
                    return $response;
                }
                return null;
                
            } catch (\Exception $e) {
                ob_end_clean();
                throw $e;
            }
            
        } catch (\Exception $e) {
            // Clean output buffer
            while (ob_get_level() > 0) {
                ob_end_clean();
            }
            
            // Log error untuk debugging
            if (config('app.debug')) {
                error_log('CodeIgniterFallback error: ' . $e->getMessage());
                error_log('Stack: ' . $e->getTraceAsString());
            }
            
            return null;
        }
    }

    /**
     * Execute CI3 directly (used by Laravel ci3.* routes).
     */
    public function handleCI3(Request $request): ?Response
    {
        return $this->tryCodeIgniter($request);
    }

    /**
     * Apply DebugBar - inject styles, scripts, and fix 404→200 display
     */
    private function applyDebugBar(Response $response): Response
    {
        try {
            // Ensure response has correct status
            if (!$response->isSuccessful()) {
                $response->setStatusCode(200);
            }
            
            // Override debugbar status metadata
            if (app()->bound('debugbar')) {
                $debugbar = app('debugbar');
                // Update the request collector's status if it exists
                if ($debugbar->hasCollector('request')) {
                    $requestCollector = $debugbar->getCollector('request');
                    if (method_exists($requestCollector, 'setStatusCode')) {
                        $requestCollector->setStatusCode(200);
                    }
                }
            }
            
            $content = $response->getContent();
            
            // Only inject if content is HTML
            if (!is_string($content) || (stripos($content, '<!DOCTYPE') === false && stripos($content, '<html') === false)) {
                return $response;
            }
            
            $debugbar = app('debugbar');
            
            // Log all available methods
            $allMethods = get_class_methods($debugbar);
            $assetRelated = array_filter($allMethods, fn($m) => stripos($m, 'asset') !== false || stripos($m, 'resource') !== false || stripos($m, 'collector') !== false);

            // Get DebugBar CSS and JS
            $headHtml = '';
            $footerHtml = '';
            
            // Try to get head HTML (CSS)
            if (method_exists($debugbar, 'getHeadHtml')) {
                try {
                    $head = $debugbar->getHeadHtml();
                    if (!empty($head)) {
                        $headHtml = (string)$head;
                    }
                } catch (\Exception $e) {
                }
            }
            
            // If no head HTML, create CSS link manually - DebugBar assets are available at /_debugbar/assets
            if (empty($headHtml)) {
                // Generate a cache-busting version number if possible
                $version = time() % 10000000;
                $headHtml = '<link rel="stylesheet" type="text/css" href="/_debugbar/assets/stylesheets?v=' . $version . '" data-turbolinks-eval="false" data-turbo-eval="false">' . "\n";
                $headHtml .= '<script src="/_debugbar/assets/javascript?v=' . $version . '" data-turbolinks-eval="false" data-turbo-eval="false"></script>' . "\n";
            }
            
            // Try to get JavascriptRenderer (JS)
            $rendered = false;
            
            // For barryvdh/laravel-debugbar, we need to use getJavascriptRenderer()
            if (method_exists($debugbar, 'getJavascriptRenderer')) {
                try {
                    $renderer = $debugbar->getJavascriptRenderer();
                    
                    // Get the renderer HTML
                    if (method_exists($renderer, 'render')) {
                        $html = $renderer->render();
                        if (!empty($html)) {
                            $footerHtml = $html;
                            $rendered = true;
                        }
                    } elseif (method_exists($renderer, '__toString')) {
                        $html = (string)$renderer;
                        if (!empty($html)) {
                            $footerHtml = $html;
                            $rendered = true;
                        }
                    }
                } catch (\Exception $e) {
                    // Debugbar not available
                }
            }
            
            // Inject CSS to head if available
            if (!empty($headHtml)) {
                $content = preg_replace('/<\/head>/i', $headHtml . '</head>', $content, 1);
            }
            
            // Inject JS to body if available
            if (!empty($footerHtml)) {
                $content = preg_replace('/<\/body>/i', $footerHtml . '</body>', $content, 1);
                $rendered = true;
            }
            
            // Inject JavaScript to fix debugbar status display from 404 to 200
            $fixDebugbarScript = <<<'JS'
<script>
(function() {
    // Remove badge if status is 200, otherwise fix display
    var fix = function() {
        try {
            // Check status and remove badge if 200
            document.querySelectorAll('.phpdebugbar-badge').forEach(function(b) {
                if (b.innerHTML.includes('200')) {
                    b.remove();
                } else if (b.innerHTML.includes('404')) {
                    b.innerHTML = b.innerHTML.replace(/404\s+Not\s+Found/g, '200 OK').replace(/404/g, '200');
                    b.remove();
                }
            });
            
            // Replace in tab title - direct text replacement
            document.querySelectorAll('.phpdebugbar-tab-title').forEach(function(t) {
                if (t.textContent.includes('404')) {
                    t.textContent = t.textContent.replace(/Request404/g, 'Request').replace(/404/g, '');
                }
            });
            
            // Replace status value - direct replacement
            document.querySelectorAll('[data-name="status"]').forEach(function(e) {
                if (e.textContent && e.textContent.includes('404')) {
                    e.textContent = e.textContent.replace(/404/g, '200');
                }
            });
        } catch (e) {}
    };
    
    // Run on DOMContentLoaded
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', fix);
    } else {
        fix();
    }
    
    // Also run after a small delay to catch late renders
    setTimeout(fix, 100);
})();
</script>
JS;
            
            $content = preg_replace('/<\/body>/i', $fixDebugbarScript . '</body>', $content, 1);

            
            if ($rendered) {
                $response->setContent($content);
            }
            
            return $response;
        } catch (\Exception $e) {
            return $response;
        }
    }

    /**
     * Get DebugBar head HTML (CSS assets)
     */
    private function getDebugBarHeadHtml(): string
    {
        try {
            $debugbar = app('debugbar');
            
            if (method_exists($debugbar, 'getHeadHtml')) {
                $head = $debugbar->getHeadHtml();
                if (!empty($head)) {
                    return (string)$head;
                }
            }
        } catch (\Exception $e) {
            // Debugbar not available
        }
        
        // Fallback: create CSS links manually
        $version = time() % 10000000;
        return '<link rel="stylesheet" type="text/css" href="/_debugbar/assets/stylesheets?v=' . $version . '" data-turbolinks-eval="false" data-turbo-eval="false">' . "\n"
             . '<script src="/_debugbar/assets/javascript?v=' . $version . '" data-turbolinks-eval="false" data-turbo-eval="false"></script>' . "\n";
    }

    /**
     * Get DebugBar footer HTML (JavaScript renderer)
     */
    private function getDebugBarFooterHtml(): string
    {
        try {
            $debugbar = app('debugbar');
            
            if (method_exists($debugbar, 'getJavascriptRenderer')) {
                $renderer = $debugbar->getJavascriptRenderer();
                
                if (method_exists($renderer, 'render')) {
                    $html = $renderer->render();
                    if (!empty($html)) {
                        return $html;
                    }
                } elseif (method_exists($renderer, '__toString')) {
                    $html = (string)$renderer;
                    if (!empty($html)) {
                        return $html;
                    }
                }
            }
        } catch (\Exception $e) {
            // Debugbar not available
        }
        
        return '';
    }

    /**
     * Get DebugBar fix script for CI3 routes
     * This fixes debugbar displaying 404 for successful CI3 responses
     */
    private function getDebugBarFixScript(): string
    {
        return <<<'JS'
<script>
    (function() {
        // Simplified fix for CI3 routes - replace 404 with 200 in debugbar
        function fixDebugBar() {
            try {
                // Fix all text content containing 404
                var allElements = document.querySelectorAll('*');
                allElements.forEach(function(elem) {
                    // Only check if element has direct text content
                    if (elem.childNodes.length > 0) {
                        elem.childNodes.forEach(function(node) {
                            if (node.nodeType === 3 && node.textContent) { // Text node
                                var text = node.textContent;
                                if (text.includes('404')) {
                                    // hapus phpdebugbar-badge jika ada
                                    var badge = document.querySelector('.phpdebugbar-badge');
                                    if (badge) {
                                        badge.remove();
                                    }

                                    node.textContent = text
                                        .replace(/404\s+Not\s+Found/gi, '')
                                        .replace(/Request200/g, 'Request')
                                        .replace(/404/g, '');
                                }
                            }
                        });
                    }
                });
            } catch (e) {
                console.debug('CI3 debugbar fix: ' + e.message);
            }
        }
        
        if (typeof document !== 'undefined') {
            // Run immediately
            fixDebugBar();
            
            // Run again after debugbar renders
            setTimeout(fixDebugBar, 100);
        }
    })();
</script>
JS;
    }
}
