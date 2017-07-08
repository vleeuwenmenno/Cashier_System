<?php
include_once("../includes.php");

if ($_GET['initials'] != "" && $_GET['famName'] != ""
						   && $_GET['street'] != ""
						   && $_GET['city'] != ""
                           && $_GET['id'] != "")
{

	$db = new mysqli($config['SQL_HOST'], $config['SQL_USER'], $config['SQL_PASS'], $config['SQL_DB']);

	if($db->connect_errno > 0)
	{
		die('Unable to connect to database [' . $db->connect_error . ']');
	}

	$sql = "UPDATE customers SET initials='" . $_GET['initials'] . "', familyName='" . $_GET['famName'] . "', companyName='" . $_GET['comName'] . "', streetName='" . $_GET['street'] . "', city='" . $_GET['city'] . "', postalCode='" . $_GET['postalCode'] . "', phoneNumber='" . $_GET['pHome'] . "', mobileNumber='" . $_GET['pMobile'] . "', email='" . $_GET['email'] . "' WHERE customerId=" . $_GET['id'];

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
