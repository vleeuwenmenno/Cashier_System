<?php
include('includes.php');
Permissions::checkSession(basename($_SERVER['REQUEST_URI']));

$db = new mysqli($config['SQL_HOST'], $config['SQL_USER'], $config['SQL_PASS'], $config['SQL_DB']);

if($db->connect_errno > 0)
{
    die('Unable to connect to database [' . $db->connect_error . ']');
}

$sql = "DELETE FROM sessions WHERE sessionId='" . $_SESSION['sessionId'] . "'";

if(!$result = $db->query($sql))
{
    die('There was an error running the query [' . $db->error . ']');
}

session_unset();
session_destroy();

header("Location: index.php");
?>