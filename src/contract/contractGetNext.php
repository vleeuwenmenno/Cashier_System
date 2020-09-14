<?php
include_once("../includes.php");

if ($_GET['period'] != "")
{
    die(Calculate::calculateNextOrder($_GET['period'], $_GET['day'], new DateTime($_GET['start']), $_GET['nextTime'], $_GET['sendNow'])->format("Y-m-d"));
}
else
	die("Form is niet volledig ingevult, vul alle velden en verstuur hem opnieuw. 4");

?>
