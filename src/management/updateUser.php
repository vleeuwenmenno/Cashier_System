<?php
include_once("../includes.php");

if ($_GET['userId'] != "" && $_GET['userTheme'] != "")
{
    if (Misc::sqlExists("userId", $_GET['userId'], "users"))
    {
        $result = Misc::sql("UPDATE users SET userTheme='" . $_GET['userTheme'] . "' WHERE userId=" . $_GET['userId']);

        if ($result == 1)
            die('OK');
        else
            die('Er is iets fout gegaan tijdens het verwerken van de wijziging.');
    }
    else
    {
        die('Geen gebruiker gevonden met id ' . $_GET['userId']);
    }
}
else if ($_GET['userId'] != "" && $_GET['managementUser'] != "")
{
    if (Misc::sqlExists("userId", $_GET['userId'], "users"))
    {
        if ($_GET['managementUser'] == "true")
            $result = Misc::sql("UPDATE users SET managementUser=1 WHERE userId=" . $_GET['userId']);
        else if ($_GET['managementUser'] == "false")
            $result = Misc::sql("UPDATE users SET managementUser=0 WHERE userId=" . $_GET['userId']);

        if ($result == 1)
            die('OK');
        else
            die('Er is iets fout gegaan tijdens het verwerken van de wijziging.');
    }
    else
    {
        die('Geen gebruiker gevonden met id ' . $_GET['userId']);
    }
}
else if ($_GET['userId'] != "" && $_GET['nickName'] != "")
{
    if (Misc::sqlExists("userId", $_GET['userId'], "users"))
    {
        $result = Misc::sql("UPDATE `users` SET `nickName` = '" . urldecode($_GET['nickName']) . "' WHERE `users`.`userId` = '" . $_GET['userId'] . "';");

        if ($result == 1)
            die('OK');
        else
            die('Er is iets fout gegaan tijdens het verwerken van de wijziging.');
    }
    else
    {
        die('Geen gebruiker gevonden met id ' . $_GET['userId']);
    }
}
else if ($_GET['userId'] != "" && $_GET['username'] != "")
{
    if (Misc::sqlExists("userId", $_GET['userId'], "users"))
    {
        $result = Misc::sql("UPDATE `users` SET `username` = '" . urldecode($_GET['username']) . "' WHERE `users`.`userId` = '" . $_GET['userId'] . "';");

        if ($result == 1)
            die('OK');
        else
            die('Er is iets fout gegaan tijdens het verwerken van de wijziging.');
    }
    else
    {
        die('Geen gebruiker gevonden met id ' . $_GET['userId']);
    }
}
else if ($_GET['userId'] != "" && $_GET['pass'] != "")
{
    if (Misc::sqlExists("userId", $_GET['userId'], "users"))
    {
        $salt = Misc::str_random(128);
        $result = Misc::sql("UPDATE `users` SET `hash` = '" . strtoupper(hash("SHA512", urldecode($_GET['pass']) . $salt)) . "', `salt` = '" . $salt . "' WHERE `users`.`userId` = " . $_GET['userId'] . ";");

        if ($result == 1)
            die('OK');
        else
            die('Er is iets fout gegaan tijdens het verwerken van de wijziging.');
    }
    else
    {
        die('Geen gebruiker gevonden met id ' . $_GET['userId']);
    }
}

