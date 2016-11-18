<?php
include_once("../includes.php");

if (isset($_GET['customerId']))
{
    $_SESSION['receipt']['customer'] =  $_GET['customerId'];
}
