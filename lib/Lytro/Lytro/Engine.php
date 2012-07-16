<?php

namespace Lytro;

class PictureNotFoundException extends \Exception {}

class Engine
{
    protected $settings = array(
        'photo_dir' => './picture',
        'cache_dir' => './cache',
    );

    public function __construct($settings = array())
    {
        $this->settings = array_merge($this->settings, $settings);
    }

    public function open($fileName)
    {
        $filePath = sprintf('%s/%s', $this->settings['photo_dir'], $fileName);

        if (!file_exists($filePath)) {
            throw new PictureNotFoundException(sprintf('Failed to load "%s".', $filePath));
        }

        $cache = new Cache($filePath);
        $cache->setDirectory($this->settings['cache_dir']);

        if ($cache->isCached()) {
            return $cache->getPicture();
        }

        try {
            $reader = new FileReader($filePath);
        } catch(\Exception $e) {
            throw $e;
        }

        $cache->storeLookupTable($reader->getLookupTable());
        $cache->storeMetaData($reader->getMetaData());
        $cache->storeImages($reader->getImages());
        $cache->storeCache();

        return $cache->getPicture();
    }
}