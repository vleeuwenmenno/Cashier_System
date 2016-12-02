<?php
include_once('../includes.php');

if (isset($_GET["modifier"]) && isset($_GET['global']) && isset($_GET["nativeId"]) && isset($_GET["priceExclVat"]))
{
    if ($_GET['global'] != "false")
    {
        $_SESSION['receipt']['items'][$_GET['nativeId']]['priceAPiece']['priceModifier'] = str_replace(',', '.', $_GET["modifier"]);
        $_SESSION['receipt']['items'][$_GET['nativeId']]['priceAPiece']['priceExclVat'] = number_format($_GET["priceExclVat"], 2, '.', '');
    }
    else
    {
        $_SESSION['receipt']['items'][$_GET['nativeId']]['priceAPiece']['priceModifier'] = str_replace(',', '.', $_GET["modifier"]);
        $_SESSION['receipt']['items'][$_GET['nativeId']]['priceAPiece']['priceExclVat'] = number_format($_GET["priceExclVat"], 2, '.', '');

        $sql = "UPDATE items SET priceExclVat='" . number_format($_GET["priceExclVat"], 2, '.', '') . "', priceModifier='" . str_replace(',', '.', $_GET["modifier"]) . "' WHERE nativeId = " . $_GET['nativeId'] . ";";
        $db = new mysqli($config['SQL_HOST'], $config['SQL_USER'], $config['SQL_PASS'], $config['SQL_DB']);

		if($db->connect_errno > 0)
		{
			die('Unable to connect to database [' . $db->connect_error . ']');
		}

		if(!$result = $db->query($sql))
		{
			die('Er was een fout tijdens het uitvoeren van deze query (' . $db->error . ') (' . $sql . ')');
		}
    }
}

 ?>
