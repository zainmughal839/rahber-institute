<?php

// app/Helpers/helpers.php
if (!function_exists('is_route_active')) {
    function is_route_active($routes, $output = 'active')
    {
        return in_array(Route::currentRouteName(), (array) $routes) ? $output : '';
    }
}
