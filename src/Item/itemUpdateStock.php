<?php
include_once("../includes.php");

$db = new mysqli($config['SQL_HOST'], $config['SQL_USER'], $config['SQL_PASS'], $config['SQL_DB']);

if($db->connect_errno > 0)
{
    die('Unable to connect to database [' . $db->connect_error . ']');
}

if ($_GET['itemId'] != "" && $_GET['itemStock'] != "")
{
	$sql = "UPDATE items SET itemStock='" . $_GET['itemStock'] . "' WHERE itemId=" . $_GET['itemId'];

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

if ($_GET['EAN'] != "" && $_GET['itemStock'] != "")
{
    $query = "SELECT * FROM items WHERE EAN='" . $_GET['EAN'] . "'";

    if(!$results = $db->query($query))
    {
        die('There was an error running the query [' . $db->error . ']');
    }

    $i = 0;
    while($row = $results->fetch_assoc())
    { $item = $row; $i++; }

    if($i > 1)
    {
        die("Er zijn meerdere artikelen gevonden met dezelfde EAN, gebruik artikel nummer om in te boeken.");
    }
    else if($i == 1)
    {
        $sql = "UPDATE items SET itemStock='" . round(Misc::calculate($item['itemStock'] . ' ' . $_GET['itemStock']), 2) . "' WHERE EAN=" . $_GET['EAN'];

        if(!$result = $db->query($sql))
        {
            die('Er was een fout tijdens het verwerken van de klant gegevens. (' . $db->error . ')');
        }
        else
        {
            die("OK " . $item['itemName']);
        }
    }
}
else
	die("Form is niet volledig, vul ieder item en verstuur hem opnieuw.");

echo 'EAN/Artikel nummer niet gevonden.';

?>
