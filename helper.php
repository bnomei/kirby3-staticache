<?php

if (!function_exists('staticache')) {
    function staticache(string $dir)
    {
        // static cache file
        $staticache = $dir . (
            !empty($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] . '/' : ''
        ) . 'index.html';
        // load static cache headers
        if (file_exists($staticache) && file_exists($staticache . '.json')) {
            foreach (json_decode(file_get_contents($staticache . '.json'), true) as $header) {
                header($header);
            }
        }
        // load static cache html
        if (file_exists($staticache)) {
            die(file_get_contents($staticache));
        }
    }
}
