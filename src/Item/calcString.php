<?php
include_once("../includes.php");

if (isset($_GET['sum']))
{
    $sum = urldecode($_GET['sum']);
    $sum = str_replace(',', '.', $sum);
    $rsult = Misc::calculate($sum);
    echo str_replace('.', ',', round($rsult, 2));
}