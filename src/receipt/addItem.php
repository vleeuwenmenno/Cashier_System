<?php
include_once("../includes.php");

if (isset($_GET['itemId']) && isset($_GET['itemCount']))
{
    if (array_key_exists($_GET['itemId'], $_SESSION['receipt']['items']))
        $_SESSION['receipt']['items'][$_GET['itemId']] += 1;
    else
        $_SESSION['receipt']['items'][$_GET['itemId']] = $_GET['itemCount'];

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