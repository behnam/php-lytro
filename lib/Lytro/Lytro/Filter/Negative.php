<?php

namespace Lytro\Filter;

class Negative
{
    public function process($image)
    {
        imagefilter($image, IMG_FILTER_NEGATE);
    }
}