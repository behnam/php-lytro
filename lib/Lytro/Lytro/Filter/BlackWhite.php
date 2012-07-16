<?php

namespace Lytro\Filter;

class BlackWhite
{
    protected $contrast;

    public function __construct($contrast = -10)
    {
        $this->contrast = $contrast;
    }

    public function process($image)
    {
        imagefilter($image, IMG_FILTER_GRAYSCALE);
        imagefilter($image, IMG_FILTER_CONTRAST, $this->contrast);
    }
}