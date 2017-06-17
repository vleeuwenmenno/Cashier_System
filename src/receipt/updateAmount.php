<?php
include_once('../includes.php');

if (isset($_GET['amount']) && isset($_GET['nativeId']))
{
    $_SESSION['receipt']['items'][$_GET['nativeId']]['count'] = $_GET['amount'];

    $json = json_encode($_SESSION['receipt']['items']);

    $db = new mysqli($config['SQL_HOST'], $config['SQL_USER'], $config['SQL_PASS'], $config['SQL_DB']);

    if($db->connect_errno > 0)
    {
        die('Unable to connect to database [' . $db->connect_error . ']');
    }

    $sql = "UPDATE receipt SET items='" . urlencode($json) . "' WHERE receiptId='" . $_SESSION['receipt']['id'] . "'";

    if(!$result = $db->query($sql))
    {
        die('There was an error running the query [' . $db->error . ']');
    }
}
 ?>
