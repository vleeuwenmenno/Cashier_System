<?php
error_reporting(0);

if (isset($_GET['sum']))
{
    $sum = urldecode($_GET['sum']);
    $sum = str_replace(',', '.', $sum);
    $theSum = str_replace("(", "round(", str_replace(")", ", 2)", $sum));
    $result = eval('return ' . $theSum . ';');
    echo round($result, 2);
}

error_reporting(1);
