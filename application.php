<?php

require 'lib/Lytro/AutoLoader.php';

$lytro = new Lytro\Engine(array(
    'photo_dir' => sprintf('%s/photos/original', dirname(__FILE__)),
    'cache_dir' => sprintf('%s/photos/cache', dirname(__FILE__))
));

define('SAMPLE_FILENAME', 'IMG_0012-stk.lfp');