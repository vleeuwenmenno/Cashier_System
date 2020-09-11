<?php
include_once("../includes.php");

if ($_GET['id'] != "")
{

	$db = new mysqli($config['SQL_HOST'], $config['SQL_USER'], $config['SQL_PASS'], $config['SQL_DB']);

	if($db->connect_errno > 0)
	{
		die('Unable to connect to database [' . $db->connect_error . ']');
	}

    Misc::sql("DELETE FROM contract WHERE contractId=" . $_GET['id']);
	Misc::sql("DELETE FROM log WHERE contractId=" . $_GET['id']);
	
	die("OK");
}
else
	die("Form is niet volledig ingevult, vul alle velden en verstuur hem opnieuw. 2");

?>
