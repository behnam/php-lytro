<?php

namespace Lytro\Filter;

class Retro
{
    protected $contrast;
    protected $red;
    protected $green;
    protected $blue;

    public function __construct($contrast = -5, $red = -5, $green = 5, $blue = -15)
    {
        $this->contrast = $contrast;
        $this->red = $red;
        $this->green = $green;
        $this->blue = $blue;
    }

    public function process($image)
    {
        imagefilter($image, IMG_FILTER_COLORIZE, $this->red, $this->green, $this->blue);
        imagefilter($image, IMG_FILTER_CONTRAST, $this->contrast);
    }
}