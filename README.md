# Kirby3 Staticache

![Release](https://flat.badgen.net/packagist/v/bnomei/kirby3-staticache?color=ae81ff)
![Downloads](https://flat.badgen.net/packagist/dt/bnomei/kirby3-staticache?color=272822)
[![Twitter](https://flat.badgen.net/badge/twitter/bnomei?color=66d9ef)](https://twitter.com/bnomei)

Kirby 3 plugin to cache html output statically on demand

## Commercial Usage

> <br>
> <b>Support open source!</b><br><br>
> This plugin is free but if you use it in a commercial project please consider to sponsor me or make a donation.<br>
> If my work helped you to make some cash it seems fair to me that I might get a little reward as well, right?<br><br>
> Be kind. Share a little. Thanks.<br><br>
> &dash; Bruno<br>
> &nbsp; 

| M | O | N | E | Y |
|---|----|---|---|---|
| [Github sponsor](https://github.com/sponsors/bnomei) | [Patreon](https://patreon.com/bnomei) | [Buy Me a Coffee](https://buymeacoff.ee/bnomei) | [Paypal dontation](https://www.paypal.me/bnomei/15) | [Hire me](mailto:b@bnomei.com?subject=Kirby) |

## Installation

- unzip [master.zip](https://github.com/bnomei/kirby3-staticache/archive/master.zip) as folder `site/plugins/kirby3-staticache` or
- `git submodule add https://github.com/bnomei/kirby3-staticache.git site/plugins/kirby3-staticache` or
- `composer require bnomei/kirby3-staticache`


## Setup Cache

### Cache configuration

Staticache is a cache driver that can be activated for the page cache:

**site/config/config.php**
```php
return [
  'cache' => [
    'pages' => [
      'active' => true,
      'type' => 'static'
    ]
  ],
  // other options
];
```

You can also use the cache [ignore rules](https://getkirby.com/docs/guide/cache#caching-pages) to skip pages that should not be cached.

**site/config/config.php**
```php
return [
  'cache' => [
    'pages' => [
      'active' => true,
      'type' => 'static',
      'ignore' => function ($page) {
        return $page->template()->name() === 'blog';
      }
    ]
  ],
  // other options
];
```
### Cache duration

Kirby will automatically purge the cache when changes are made in the Panel.

## Setup Server

You can use any of these ways to make the static cache load.

### PHP

Add these lines before your kirby render method. Please note that using one of the native server config rules will be a tiny bit faster (and safer).

**index.php**
```php
<?php
    // load static cache file if it exists
    $staticache = __DIR__ . '/static/';
    if (!empty($_SERVER['REQUEST_URI'])) {
        $staticache .= $_SERVER['REQUEST_URI'] . '/';
    }
    $staticache .= 'index.html';
    if (file_exists($staticache)) {
        echo file_get_contents($staticache);
        die();
    }

    require __DIR__ . '/kirby/bootstrap.php';
    
    echo (new Kirby)->render();
```

### Apache htaccess rules

Add the following lines to your Kirby htaccess file, directly after the RewriteBase rule.

```
RewriteCond %{DOCUMENT_ROOT}/static/%{REQUEST_URI}/index.html -f [NC]
RewriteRule ^(.*) %{DOCUMENT_ROOT}/static/%{REQUEST_URI}/index.html [L]
```

### Nginx

Standard php nginx config will have this location block for all requests

```
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
```
change it to add `/static/$uri/index.html` before last `/index.php` fallback.

```
    location / {
        try_files $uri $uri/ /static/$uri/index.html /index.php?$query_string;
    }
```

## Settings

| bnomei.staticache. | Default           | Description                  |            
|--------------------|-------------------|------------------------------|
| root               | `function(){...}` | callback to set the root     |
| message            | `function(){...}` | callback to return a message |

## Disclaimer

This plugin is provided "as is" with no guarantee. Use it at your own risk and always test it yourself before using it in a production environment. If you find any issues, please [create a new issue](https://github.com/bnomei/kirby3-recently-modified/issues/new).

## License

[MIT](https://opensource.org/licenses/MIT)

It is discouraged to use this plugin in any project that promotes racism, sexism, homophobia, animal abuse, violence or any other form of hate speech.

## Credits

- based on the idea by [Bastian Allgeier](https://getkirby.com/plugins/getkirby)
- extended version by [Bruno Meilick](https://getkirby.com/plugins/bnomei)
