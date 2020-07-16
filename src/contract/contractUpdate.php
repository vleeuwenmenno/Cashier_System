<?php
include_once("../includes.php");

if ($_GET['id'] != "" && $_GET['planningPeriod'] != ""
						   && $_GET['planningDay'] != ""
						   && $_GET['startDate'] != "")
{
	$json = json_encode($_SESSION['receipt']['items']);
	$db = new mysqli($config['SQL_HOST'], $config['SQL_USER'], $config['SQL_PASS'], $config['SQL_DB']);

	if($db->connect_errno > 0)
	{
		die('Unable to connect to database [' . $db->connect_error . ']');
	}

	$sql = "UPDATE contract SET planningPeriod='" . $_GET['planningPeriod'] . "', planningDay='" . $_GET['planningDay'] . "', startDate='" . $_GET['startDate'] . "', customerId='" . $_SESSION['receipt']['customer'] . "', items='" . urlencode($json) . "' WHERE contractId=" . $_GET['id'];

	if(!$result = $db->query($sql))
	{
		die('Er was een fout tijdens het verwerken van de contract gegevens. (' . $db->error . ')');
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
