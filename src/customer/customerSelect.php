<?php 
include_once("../includes.php");

if (isset($_GET['customerId']))
{
    $custId = $_GET['customerId'];
    global $config;

    $db = new mysqli($config['SQL_HOST'], $config['SQL_USER'], $config['SQL_PASS'], $config['SQL_DB']);

    if($db->connect_errno > 0)
    {
        die('Unable to connect to database [' . $db->connect_error . ']');
    }

    $sql = "SELECT * FROM customers WHERE customerId='$custId';";

    if(!$result = $db->query($sql))
    {
        die('Er was een fout tijdens het toevoegen van de klant. (' . $db->error . ')' . $sql);
    }

    while($row = $result->fetch_assoc())
    {
        if (isset($_SESSION['receipt']))
            $_SESSION['receipt']['customer'] = $row;
    }
}