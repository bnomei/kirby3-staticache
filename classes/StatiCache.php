<?php

namespace Bnomei;

use Kirby\Cache\FileCache;
use Kirby\Filesystem\F;
use Kirby\Toolkit\Str;

class StatiCache extends FileCache
{
    public function __construct(array $options = [])
    {
        // force root to be a public folder
        $options['root'] = kirby()->root('index') . '/static';
        // a prefix would not work with REQUEST_URI
        $options['prefix'] = null;

        parent::__construct($options);
    }

    protected function file(string $key): string
    {
        // get extension and remove it from key
        $extension = F::extension($key);
        $key = str_replace('.' . $extension, '', $key);

        // get language and remove if from key
        $language = null;
        if (kirby()->multilang()) {
            $language = kirby()->language();
            if (Str::endsWith($key, '.' . $language->code())) {
                $key = str_replace('.' . $language->code(), '', $key);
            }
        }

        // homepage defaults to root (maybe of language)
        if ($key === 'home') {
            $key = '';
        }

        // prefix with language if any
        if ($language) {
            $key = $language->path() . '/' . $key;
        }

        return $this->root . '/' . $key . '/index.' . $extension;
    }

    public function retrieve(string $key)
    {
        return F::read($this->file($key));
    }

    public function set(string $key, $value, int $minutes = 0): bool
    {
        $file = $this->file($key);
        $headers = headers_list();
        if (!empty($headers)) {
            file_put_contents($file . '.json', json_encode($headers));
        }
        return F::write($file, $value['html'] . $this->message());
    }

    protected function message()
    {
        $message = option('bnomei.staticache.message');
        if (!is_string($message) && is_callable($message)) {
            $message = $message();
        }

        return $message;
    }
}
