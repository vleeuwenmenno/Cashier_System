<?php
include_once("../includes.php");

if ($_GET['startDate'] != "" && $_GET['planningPeriod'] != "" && $_GET['planningDay'] != "" && $_GET['sendNow'] != "" && $_GET['directDebit'] != "")
{
	$json = json_encode($_SESSION['receipt']['items']);
	$db = new mysqli($config['SQL_HOST'], $config['SQL_USER'], $config['SQL_PASS'], $config['SQL_DB']);

	if ($_GET['startDate'] != "" || $_GET['startDate'] != " ")
	{
		$test_date = $_GET['startDate'];
		$test_arr  = explode('-', $test_date);
		if (count($test_arr) == 3) 
		{
			if (checkdate($test_arr[0], $test_arr[1], $test_arr[2])) 
			{ } 
			else 
			{
				die("Gegeven start datum is niet in een correct formaat." . $_GET['startDate']);
			}
		} 
		else 
		{ }
	}

	if($db->connect_errno > 0)
	{
		die('Unable to connect to database [' . $db->connect_error . ']');
	}


	$sn = $_GET['sendNow'] == "true" ? 1 : 0;
	$dd = $_GET['directDebit'] == "true" ? 1 : 0;
	$sql = "INSERT INTO contract (customerId, startDate, planningPeriod, planningDay, items, directDebit, sendOrderNow) VALUES ('" . $_SESSION['receipt']['customer'] . "', '" . $_GET['startDate'] . "', '" . $_GET['planningPeriod'] . "', '" . $_GET['planningDay'] . "', '" . urlencode($json) . "', '$dd', '$sn');";

	if(!$result = $db->query($sql))
	{
		die('Er was een fout tijdens het verwerken van de klant gegevens. (' . $db->error . ')');
	}
	else
	{
		$_SESSION['receipt']['old'] = null;
		$_SESSION['receipt']['old'] = $_SESSION['receipt'];
	
		$_SESSION['receipt']['id'] = 0;
		$_SESSION['receipt']['items'] = null;
		$_SESSION['receipt']['status'] = 'closed';
		$_SESSION['receipt']['customer'] = null;
		$_SESSION['receipt']['saved'] = false;
		unset($_SESSION['receipt']['order']);

		$last_id = mysqli_insert_id($db);
		die("OK " . $last_id);
	}
}
else
	die("Form is niet volledig ingevult, vul alle velden en verstuur hem opnieuw.");
?>
