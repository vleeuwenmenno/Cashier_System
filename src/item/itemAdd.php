<?php
include_once("../includes.php");

if ($_GET['supplier'] != "" && $_GET['itemName'] != ""
						   && $_GET['priceExclVat'] != ""
                           && $_GET['priceModifier'] != "")
{

	$db = new mysqli($config['SQL_HOST'], $config['SQL_USER'], $config['SQL_PASS'], $config['SQL_DB']);

	if($db->connect_errno > 0)
	{
		die('Unable to connect to database [' . $db->connect_error . ']');
	}

	$sql = "INSERT INTO items (itemId, EAN, supplier, factoryId, itemName, itemCategory, itemStock, priceExclVat, priceModifier) VALUES ('" . $_GET['itemId'] . "', '" . $_GET['EAN'] . "', '" . $_GET['supplier'] . "', '" . $_GET['factoryId'] . "', '" . $_GET['itemName'] . "', '" . $_GET['itemCategory'] . "', '" . $_GET['itemStock'] . "', '" . $_GET['priceExclVat'] . "', '" . $_GET['priceModifier'] . "');";

	if(!$result = $db->query($sql))
	{
		die('Er was een fout tijdens het verwerken van de klant gegevens. (' . $db->error . ')');
	}
	else
	{
		$last_id = mysqli_insert_id($db);
		die("OK " . $last_id);
	}
}
else
die("Form is niet volledig ingevult, vul alle velden en verstuur hem opnieuw.");

?>
