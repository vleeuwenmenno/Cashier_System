<?php
include_once("../includes.php");

if ($_GET['nativeId'] != ""  && $_GET['priceExclVat'] != ""
                           && $_GET['priceModifier'] != "")
{

	$db = new mysqli($config['SQL_HOST'], $config['SQL_USER'], $config['SQL_PASS'], $config['SQL_DB']);

	if($db->connect_errno > 0)
	{
		die('Unable to connect to database [' . $db->connect_error . ']');
	}

	$sql = "UPDATE items SET EAN='" . $_GET['EAN'] . "', supplier='" . $_GET['supplier'] . "', factoryId='" . $_GET['factoryId'] . "', itemName='" . $_GET['itemName'] . "', itemCategory='" . $_GET['itemCategory'] . "', itemStock='" . $_GET['itemStock'] . "', priceExclVat='" . $_GET['priceExclVat'] . "', priceModifier='" . $_GET['priceModifier'] . "' WHERE nativeId=" . $_GET['nativeId'] . ';';

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
{
	//echo "'".$_GET['nativeId']. "'";
	//echo "'".$_GET['priceExclVat']. "'";
	//echo "'".$_GET['priceModifier']. "'";

die("Form is niet volledig ingevult, vul alle velden en verstuur hem opnieuw. (Let op! Artikel nummer moet ingevult zijn.) 11");
}

?>
