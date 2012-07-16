<?php

namespace Lytro;

class CacheDirectoryNotFound extends \Exception {}

class Cache
{
    protected $directory = './cache';
    protected $filePath;
    protected $cache = array();

    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }

    public function setDirectory($directory)
    {
        $fileName = basename($this->filePath);
        $fileName = substr($fileName, 0, strrpos($fileName, '.'));

        $this->directory = sprintf('%s/%s', $directory, $fileName);

        if (!file_exists($this->directory)) {
            if (!@mkdir($this->directory)) {
                throw new CacheDirectoryNotFound(sprintf('Directory "%s" does not exist and could not be created.', $this->directory));
            }
        }
    }

    public function isCached()
    {
        $cacheFile = sprintf('%s/cache.json', $this->directory);

        if (file_exists($cacheFile)) {
            return true;
        }

        return false;
    }

    public function getPicture()
    {
        $cachePath = sprintf('%s/cache.json', $this->directory);

        if (isset($this->cache['cache'])) {
            return new Picture($this->cache['cache'], $this->directory);
        }

        return new Picture($cachePath, $this->directory);
    }

    public function storeCache()
    {
        $vendorContent = $this->cache['meta']['picture']['accelerationArray'][0]['vendorContent'];

        $cache = array();
        $cache['generator'] = 'php-nytro';
        $cache['date'] = date('r');
        $cache['camera'] = 'Nytro (http://nytro.com)';
        $cache['version'] = $this->cache['meta']['version'];
        $cache['lookup'] = $this->cache['lookup'];
        $cache['dimensions'] = $vendorContent['displayParameters']['displayDimensions']['value'];
        $cache['images'] = array();

        foreach ($vendorContent['imageArray'] as $image) {
            $cache['images'][] = array(
                'focus' => $image['lambda'],
                'ref' => str_replace('sha1-', '', $image['imageRef']),
            );
        }

        $json = json_encode($cache);
        file_put_contents(sprintf('%s/cache.json', $this->directory), $json);

        $this->cache['cache'] = $cache;
    }

    public function storeLookupTable($table)
    {
        $json = json_encode($table);
        file_put_contents(sprintf('%s/lookup.json', $this->directory), $json);

        $this->cache['lookup'] = $table;
    }

    public function storeMetaData($data)
    {
        $json = json_encode($data);
        file_put_contents(sprintf('%s/metadata.json', $this->directory), $json);

        $this->cache['meta'] = $data;
    }

    public function storeImages($images)
    {
        if (!is_array($images) ) {
            return false;
        }

        foreach ($images as $sha1 => $data) {
            file_put_contents(sprintf('%s/%s.jpg', $this->directory, $sha1), $data);
        }
    }
}