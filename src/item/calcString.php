<?php
//include_once("../includes.php"); //Better not load this as this allows the user to use SESSION variable and the defined classes while injecting php code

if (isset($_GET['sum']))
{
    $sum = urldecode($_GET['sum']);
    $sum = str_replace(',', '.', $sum);
    //$rsult = Misc::calculate($sum); // This doesn't round the number with every () so it is not good for currency calculations

    $theSum = str_replace("(", "round(", str_replace(")", ", 2)", $sum));
    $result = eval('return ' . $theSum . ';');
    echo round($result, 2);
}
