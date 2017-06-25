<?php
include_once("../includes.php");

if ($_GET['userId'] != "")
{
    if (Misc::sqlExists("userId", $_GET['userId'], "users"))
    {
        $result = Misc::sql('DELETE FROM users WHERE userId=' . $_GET['userId'] . ';');

        if ($result == 1)
            die('OK');
        else
            die('Er is iets fout gegaan tijdens het verwijderen van de gebruiker.');
    }
    else
    {
        die('Deze gebruiker bestaat niet!');
    }
}
