<?php
include_once("../includes.php");

if ($_GET['itemId'] != "" && $_GET['itemStock'] != "")
{
	$db = new mysqli($config['SQL_HOST'], $config['SQL_USER'], $config['SQL_PASS'], $config['SQL_DB']);

	if($db->connect_errno > 0)
	{
		die('Unable to connect to database [' . $db->connect_error . ']');
	}

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
	$db = new mysqli($config['SQL_HOST'], $config['SQL_USER'], $config['SQL_PASS'], $config['SQL_DB']);

	if($db->connect_errno > 0)
	{
		die('Unable to connect to database [' . $db->connect_error . ']');
	}

    $query = "SELECT * FROM items WHERE EAN='" . $_GET['EAN'] . "'";
    if(!$results = $db->query($query))
	{
		die('Er was een fout tijdens het verwerken van de klant gegevens. (' . $db->error . ')');
	}

    $rows = mysqli_num_rows($results);
    if($rows > 1)
    {
        die("Er zijn meerdere artikelen gevonden met dezelfde EAN, gebruik artikel nummer om in te boeken.");
    }
    else if($rows == 1)
    {
        $sql = "UPDATE items SET itemStock='" . $_GET['itemStock'] . "' WHERE EAN=" . $_GET['EAN'];

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
}
else
	die("Form is niet volledig, vul ieder item en verstuur hem opnieuw.");

?>
