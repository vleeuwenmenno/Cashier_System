<?php
include_once("../includes.php");

if ($_GET['intials'] != "" && $_GET['famName'] != ""
						   && $_GET['street'] != ""
						   && $_GET['city'] != ""
						   && $_GET['pHome'] != "")
{

	$db = new mysqli($config['SQL_HOST'], $config['SQL_USER'], $config['SQL_PASS'], $config['SQL_DB']);

	if($db->connect_errno > 0)
	{
		die('Unable to connect to database [' . $db->connect_error . ']');
	}

	$sql = "INSERT INTO customers (initials, familyName, companyName, streetName, city, phoneNumber, mobileNumber, email, postalCode) VALUES ('" . $_GET['intials'] . "', '" . $_GET['famName'] . "', '" . $_GET['comName'] . "', '" . $_GET['street'] . "', '" . $_GET['city'] . "', '" . $_GET['pHome'] . "', '" . $_GET['pMobile'] . "', '" . $_GET['email'] . "', '" . $_GET['postalCode'] . "');";

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
