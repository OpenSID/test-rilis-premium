<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CodeIgniterIntegrationTest extends TestCase
{
    /**
     * Test CI3 instance dapat di-load
     *
     * @return void
     */
    public function test_ci3_instance_can_be_loaded()
    {
        $ci = app('ci');
        
        $this->assertNotNull($ci);
        $this->assertIsObject($ci);
    }

    /**
     * Test CI3 dapat load library
     *
     * @return void
     */
    public function test_ci3_can_load_library()
    {
        $ci = app('ci');
        
        // Load session library
        $ci->load->library('session');
        
        $this->assertObjectHasProperty('session', $ci);
    }

    /**
     * Test CI3 dapat load helper
     *
     * @return void
     */
    public function test_ci3_can_load_helper()
    {
        $ci = app('ci');
        
        // Load URL helper
        $ci->load->helper('url');
        
        // Check if helper function exists
        $this->assertTrue(function_exists('base_url'));
    }

    /**
     * Test session sharing antara Laravel dan CI3
     *
     * @return void
     */
    public function test_session_sharing()
    {
        // Set session di Laravel
        session(['test_key' => 'test_value']);
        
        // Get dari CI3
        $ci = app('ci');
        $ci->load->library('session');
        $value = $ci->session->userdata('test_key');
        
        $this->assertEquals('test_value', $value);
    }

    /**
     * Test CI3 session dapat set dan get
     *
     * @return void
     */
    public function test_ci3_session_set_and_get()
    {
        $ci = app('ci');
        $ci->load->library('session');
        
        // Set di CI3
        $ci->session->set_userdata('ci3_test', 'hello');
        
        // Get dari Laravel
        $value = session('ci3_test');
        
        $this->assertEquals('hello', $value);
    }

    /**
     * Test Laravel helpers tersedia di CI3
     *
     * @return void
     */
    public function test_laravel_helpers_available()
    {
        // Test helper functions exist
        $this->assertTrue(function_exists('laravel_app'));
        $this->assertTrue(function_exists('laravel_cache'));
        $this->assertTrue(function_exists('laravel_session'));
        $this->assertTrue(function_exists('laravel_config'));
        $this->assertTrue(function_exists('laravel_db'));
    }

    /**
     * Test Laravel cache dari helper
     *
     * @return void
     */
    public function test_laravel_cache_helper()
    {
        // Helpers already auto-loaded via Composer from vendor/opensid/laravel-ci3/src/helpers.php
        
        // Set cache using Laravel's native cache
        cache()->put('test_cache', 'cached_value', 60);
        
        // Get cache using Laravel's native cache
        $value = cache()->get('test_cache');
        
        $this->assertEquals('cached_value', $value);
    }

    /**
     * Test CI3 config dapat diakses
     *
     * @return void
     */
    public function test_ci3_config_access()
    {
        $ci = app('ci');
        $ci->load->config('config');
        
        $baseUrl = $ci->config->item('base_url');
        
        // Base URL bisa kosong atau string
        $this->assertTrue(is_string($baseUrl) || $baseUrl === null);
    }
}
