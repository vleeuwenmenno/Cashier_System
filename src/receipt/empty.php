<?php
include_once("../includes.php");

if (isset($_GET['receiptId']))
{
    $_SESSION['receipt']['old'] = null;
    $_SESSION['receipt']['old'] = $_SESSION['receipt'];

    $_SESSION['receipt']['id'] = 0;
    $_SESSION['receipt']['items'] = null;
    $_SESSION['receipt']['status'] = 'closed';
    $_SESSION['receipt']['customer'] = null;

    if (!isset($_GET['save']))
    {
        $db = new mysqli($config['SQL_HOST'], $config['SQL_USER'], $config['SQL_PASS'], $config['SQL_DB']);

        if($db->connect_errno > 0)
        {
            die('Unable to connect to database [' . $db->connect_error . ']');
        }

        $sql = "DELETE FROM receipt WHERE receiptId='" . $_GET['receiptId'] . "'";

        if(!$result = $db->query($sql))
        {
            die('There was an error running the query [' . $db->error . ']');
        }
    }
}
?>
