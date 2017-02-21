<?php
include_once("../includes.php");

if (isset($_GET['sum']))
{
    $sum = urldecode($_GET['sum']);
    $sum = str_replace(',', '.', $sum);
    //$rsult = Misc::calculate($sum);

    $theSum = str_replace("(", "round(", str_replace(")", ", 2)", $sum));
    $result = eval('return ' . $theSum . ';');
    echo round($result, 2);
}
