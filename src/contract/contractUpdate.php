<?php
include_once("../includes.php");

if ($_GET['id'] != "" && $_GET['planningPeriod'] != ""
						   && $_GET['planningDay'] != ""
						   && $_GET['startDate'] != "")
{
	if (!isset($_SESSION['receipt']['items']))
	{
		$json = json_decode(urldecode(Misc::sqlGet("items", "contract", "contractId", $_GET['id'])['items']), true);
			
		$_SESSION['receipt'] = array();

		$_SESSION['receipt']['order'] = 1;
		$_SESSION['receipt']['status'] = "open";
		$_SESSION['receipt']['saved'] = 0;
		$_SESSION['receipt']['id'] = rand(0, 999999999);
		$_SESSION['receipt']['items'] = $json;
		$_SESSION['receipt']['customer'] = Misc::sqlGet("customerId", "contract", "contractId", $_GET['id'])['customerId'];
	}
	
	$json = json_encode($_SESSION['receipt']['items']);
	$db = new mysqli($config['SQL_HOST'], $config['SQL_USER'], $config['SQL_PASS'], $config['SQL_DB']);

	if($db->connect_errno > 0)
	{
		die('Unable to connect to database [' . $db->connect_error . ']');
	}

	$dd = $_GET['directDebit'] == "true" ? 1 : 0;
	$sql = "UPDATE contract SET directDebit='$dd', planningPeriod='" . $_GET['planningPeriod'] . "', planningDay='" . $_GET['planningDay'] . "', startDate='" . $_GET['startDate'] . "', customerId='" . $_SESSION['receipt']['customer'] . "', items='" . urlencode($json) . "' WHERE contractId=" . $_GET['id'];

	
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
	die("Form is niet volledig ingevult, vul alle velden en verstuur hem opnieuw. 5");

?>
