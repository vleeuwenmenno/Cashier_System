<?php
include_once("../includes.php");

if (isset($_GET['customerId']))
{
    $res = Misc::sql("UPDATE receipt SET customerId='" . $_GET['customerId'] . "' WHERE receiptId='" . $_SESSION['receipt']['id'] . "';");
    if (is_bool($res) && $res == true)
    {
        $_SESSION['receipt']['customer'] =  $_GET['customerId'];
    }
    else
        echo $res;
}
