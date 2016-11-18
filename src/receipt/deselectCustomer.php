<?php
include_once("../includes.php");

$res = Misc::sql("UPDATE receipt SET customerId='0' WHERE receiptId='" . $_SESSION['receipt']['id'] . "';");
if (is_bool($res) && $res == true)
{
    unset($_SESSION['receipt']['customer']);
}
else
    echo $res;
?>
