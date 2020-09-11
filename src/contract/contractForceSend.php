<?php
include_once("../includes.php");

if ($_GET['id'])
{
	Misc::sqlUpdate("contract", "sendOrderNow", 1, "contractId", $_GET['id']);
	die("Contract staat ingepland " . $last_id);
}
else
	die("Form is niet volledig ingevult, vul alle velden en verstuur hem opnieuw. 3");

?>
