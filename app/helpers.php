<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * @param $path
 * @param string $active
 * @return mixed|string
 */
function active_class($path, $active = 'active')
{
    return call_user_func_array('Request::is', (array)$path) ? $active : '';
}

/**
 * @param $path
 * @return string
 */
function is_active_route($path)
{
    return call_user_func_array('Request::is', (array)$path) ? 'true' : 'false';
}

/**
 * @param $path
 * @return string
 */
function show_class($path)
{
    return call_user_func_array('Request::is', (array)$path) ? 'show' : '';
}

/**
 * @return string
 * @throws Exception
 */
function set_portal_theme()
{
    // there's only "light" or "dark" theme
    $defaultTheme = config('app.portalTheme', get_config_key('enums.web_portal_theme.LIGHT'));
    $defaultTheme = (!empty(Auth::user()->portal_theme)) ? Auth::user()->portal_theme : $defaultTheme;
    return (strtolower($defaultTheme) == strtolower(get_config_key('enums.web_portal_theme.DARK')))
        ? 'css_dark/app.css?version=1'
        : 'css_light/app.css?version=1';
}

/**
 * Used for when needed to get the KEY name of a config element (in dot notation)
 * @param string $key
 * @return string
 * @throws Exception
 */
function get_config_key($key = '')
{
    if (config()->has($key)) {
        $key = explode('.', $key);
        return end($key);
    } else {
        log::error('Trying to get a key from an Unknown config key: ' . $key);
    }
}
