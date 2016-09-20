<?php
$link = mysql_connect('oskaremilsson.se', '1dv610user', 'getmecoffee');
if (!$link) {
    die('Could not connect: ' . mysql_error());
}
echo 'Connected successfully';
