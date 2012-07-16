<?php

namespace Lytro;

class UnknownPictureException extends \Exception {}

class Picture
{
    protected $filters;
    protected $path;
    protected $data;
    protected $size;

    public function __construct($file, $path)
    {
        if (!is_array($file)) {
            $file = $this->load($file);
        }

        $this->filters = array();
        $this->path = $path;
        $this->data = $file;
        $this->size = 600;
    }

    public function addFilter($filter)
    {
        $this->filters[]    = $filter;
    }

    public function setSize($size)
    {
        $this->size = $size;
    }

    public function display($ref = false)
    {
        if (!$ref) {
            $ref = $this->data['images'][0]['ref'];
        }

        $filePath = false;
        foreach ($this->data['images'] as $image) {
            if ($image['ref'] != $ref) {
                continue;
            }

            $filePath = sprintf('%s/%s.jpg', $this->path, $image['ref']);
        }

        if (!$filePath) {
            throw new UnknownPictureException('Picture file not found.');
        }

        $source = imagecreatefromjpeg($filePath);
        $image = imagecreatetruecolor($this->size, $this->size);
        imagecopyresampled($image, $source, 0, 0, 0, 0, $this->size, $this->size, imagesx($source), imagesy($source));
        imagedestroy($source);

        foreach ($this->filters as $filter) {
            call_user_func(array($filter, 'process'), $image);
        }

        header('Content-Type: image/jpeg');
        imagejpeg($image, null, 100);
    }

    public function getJson()
    {
        $this->data['size'] = $this->size;
        return json_encode($this->data);
    }

    public function getImages()
    {
        return $this->data['images'];
    }

    public function getLookupTable()
    {
        return $this->data['lookup'];
    }

    public function getHeight()
    {
        return $this->data['dimensions']['height'];
    }

    public function getWidth()
    {
        return $this->data['dimensions']['width'];
    }

    private function load($filePath)
    {
        return json_decode(file_get_contents($filePath), true);
    }
}