<?php

require sprintf('%s/application.php', dirname(__FILE__));

$picture = $lytro->open(SAMPLE_FILENAME);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">

    <title>Lytro: Image Viewer</title>

    <link rel="stylesheet" type="text/css" href="static/css/lytro.css" />
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
    <script type="text/javascript" src="static/js/lytro.js"></script>
</head>
<body>

    <div class="wrapper">
        <h1>Lytro: Image Viewer</h1>
        <p class="lead">This is a proof of concept image reader and viewer for Lytro photos, written in PHP.</p>
        <p>Partially thanks to <a href="https://github.com/nrpatel/lfptools">lfptools</a> by <strong>nrpatel</strong> who reverse-engineered the LPF file format.<br />This PHP version might help more people getting familiar with the format.</p>

        <div class="lytro">
            <div class="picture"><img src="image.php" alt="" /></div>
            <p class="caption">This is a sample picture</p>
        </div>
    </div>

</body>
</html>