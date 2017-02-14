<?php
include_once("../includes.php");

if ($_GET['nickname'] != "" && $_GET['managementUser'] != "" && $_GET['userTheme'] != "" && $_GET['pass'] != "" && $_GET['username'] != "")
{
    if (!Misc::sqlExists("username", $_GET['username'], "users"))
    {
        $salt = Misc::str_random(128);
        $result = Misc::sql('INSERT INTO `users` (`userId`, `username`, `nickName`, `hash`, `salt`, `userTheme`, `managementUser`) VALUES ("' . rand(0, 2000) . '", "' . $_GET['username'] . '", "'. $_GET['nickname'] . '", "' . strtoupper(hash("SHA512", $_GET['pass'] . $salt)) . '", "' . $salt . '", "' . $_GET['userTheme'] . '", "' . $_GET['managementUser'] .'");');

        if ($result == 1)
            die('OK');
        else
            die('Er is iets fout gegaan tijdens het verwerken van de nieuwe gebruiker.');
    }
    else
    {
        die('Deze gebruikersnaam is al in gebruik.');
    }
}
