<?php

declare(strict_types=1);

@include_once __DIR__ . '/vendor/autoload.php';

use Kirby\Filesystem\F;

if (!function_exists('staticache')) {
    function staticache(string $root, ?string $requestUri = null)
    {
        // NOTE: root can not be from config or kirby would be loaded
        $requestUri = $requestUri ?? $_SERVER['REQUEST_URI'];
        if ($cache = F::read($root . '/' . $requestUri . '/index.html')) {
            // TODO: send cached headers?
            echo $cache;
            die;
        }
    }
}

Kirby::plugin('bnomei/staticache', [
    'options' => [
        'root' => function () {
            return kirby()->roots()->index() . '/static';
        },
        'message' => fn () => '<!-- static ' . date('c') . ' -->',
    ],
    'cacheTypes' => [
        'static' => 'Bnomei\StatiCache',
    ],
]);
