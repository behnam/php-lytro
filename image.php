<?php

require sprintf('%s/application.php', dirname(__FILE__));

$picture = $lytro->open(SAMPLE_FILENAME);
$picture->setSize(400);
$picture->addFilter(new Lytro\Filter\BlackWhite(-10));
// $picture->addFilter(new Lytro\Filter\Negative());
// $picture->addFilter(new Lytro\Filter\Retro());

if (isset($_GET['ref'])) {
    $picture->display($_GET['ref']);
} else {
    $picture->display();
}