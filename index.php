<?php

declare(strict_types=1);

@include_once __DIR__ . '/vendor/autoload.php';

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
