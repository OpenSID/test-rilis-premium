<?php

/**
 * Get hook router
 * 
 * @param array
 */
function getHooks($config = [])
{
    return OpenSID\Hook::getHooks($config);
}

/**
 * Get all routes
 * 
 * Auto-loads module routes before returning compiled routes
 * 
 * @return array
 */
function getRoutes()
{
    return OpenSID\Route::getRoutes();
}