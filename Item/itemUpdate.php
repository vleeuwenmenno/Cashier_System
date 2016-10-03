<?php
include_once("../includes.php");

if ($_GET['itemId'] != "" && $_GET['supplier'] != ""
						   && $_GET['factoryId'] != ""
						   && $_GET['itemName'] != ""
						   && $_GET['priceExclVat'] != ""
                           && $_GET['priceModifier'] != "")
{

	$db = new mysqli($config['SQL_HOST'], $config['SQL_USER'], $config['SQL_PASS'], $config['SQL_DB']);

	if($db->connect_errno > 0)
	{
		die('Unable to connect to database [' . $db->connect_error . ']');
	}

	$sql = "UPDATE items SET EAN='" . $_GET['EAN'] . "', supplier='" . $_GET['supplier'] . "', factoryId='" . $_GET['factoryId'] . "', itemName='" . $_GET['itemName'] . "', itemCategory='" . $_GET['itemCategory'] . "', itemStock='" . $_GET['itemStock'] . "', priceExclVat='" . $_GET['priceExclVat'] . "', priceModifier='" . $_GET['priceModifier'] . "' WHERE itemId=" . $_GET['itemId'];

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
	die("Form is niet volledig, vul ieder item en verstuur hem opnieuw.");

?>
