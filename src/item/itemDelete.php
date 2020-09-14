<?php
include_once("../includes.php");

if ($_GET['id'] != "")
{

	$db = new mysqli($config['SQL_HOST'], $config['SQL_USER'], $config['SQL_PASS'], $config['SQL_DB']);

	if($db->connect_errno > 0)
	{
		die('Unable to connect to database [' . $db->connect_error . ']');
	}

	$sql = "DELETE FROM items WHERE nativeId=" . $_GET['id'];

	if(!$result = $db->query($sql))
	{
		die('Er was een fout tijdens het verwerken van dit item. (' . $db->error . ')');
	}
	else
	{
		$last_id = mysqli_insert_id($db);
		die("OK " . $last_id);
	}
}
else
	die("Form is niet volledig ingevult, vul alle velden en verstuur hem opnieuw. 10");

?>
