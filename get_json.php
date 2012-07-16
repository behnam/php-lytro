<?php

require sprintf('%s/application.php', dirname(__FILE__));

header('Content-Type: application/json');

$picture = $lytro->open(SAMPLE_FILENAME);
$picture->setSize(400);
print $picture->getJson();