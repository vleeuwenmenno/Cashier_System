<?php
include("../includes.php");

if ($_SESSION['login'])
{
    $login = $_SESSION['login'];

    $db = new mysqli($config['SQL_HOST'], $config['SQL_USER'], $config['SQL_PASS'], $config['SQL_DB']);

    if($db->connect_errno > 0)
    {
        die('Unable to connect to database [' . $db->connect_error . ']');
    }

    $sql = "SELECT * FROM `users` WHERE `username`=\"" . $login['user'] . "\"";

    if(!$result = $db->query($sql))
    {
        die('There was an error running the query [' . $db->error . ']');
    }

    while($row = $result->fetch_assoc())
    {
        if ($row['username'] == $login['user'])
        {
            if ($row['hash'] == strtoupper(hash('sha512', $login['pass'] . $row['salt'])))
            {
                echo 'Login accepted with username!';
                unset($_SESSION['login']);
                $_SESSION['result'] = "Login accepted with username";

                unset($row['hash']);
                unset($row['salt']);
                unset($row['recoverId']);

                $_SESSION['sessionId'] = Misc::str_random(32);
                //$_SESSION['lang'] = $row['lang'];

                $sql = "INSERT INTO `sessions` (`sessionId`, `userId`, `lastPing`, `validUntil`) VALUES ('" . $_SESSION['sessionId'] . "', '" . $row['userId'] . "', '" . date("Y-m-d H:i:s", strtotime('+0hour')) . "', '" . date("Y-m-d H:i:s", strtotime('+1 hour')) . "');";

                if(!$result = $db->query($sql))
                {
                    die('There was an error running the query [' . $db->error . ']');
                }

                echo '<br />Redirecting to ' . $_GET['r'] . '<br />';

                $_SESSION['login_ok'] = $row;
                header("Location: ../" . $_GET['r']);
            }
            else
            {
                echo 'Username or password incorrect. ERROR: 001';
                $_SESSION['result'] = "Username or password incorrect. ERROR: 001";
                header("Location: ../index.php?login");
            }
        }
        else
        {
            echo 'Username or password incorrect.  ERROR: 002';
            $_SESSION['result'] = "Username or password incorrect.  ERROR: 002";
            header("Location: ../index.php?login");
        }
    }

    $sql = "SELECT * FROM `users` WHERE `email`=\"" . $login['user'] . "\"";

    if(!$result = $db->query($sql))
    {
        die('There was an error running the query [' . $db->error . ']');
    }

    while($row = $result->fetch_assoc())
    {
        if ($row['email'] == $login['user'])
        {
            if ($row['hash'] == strtoupper(hash('sha512', $login['pass'] . $row['salt'])))
            {
                echo 'Login accepted with email!';
                unset($_SESSION['login']);
                $_SESSION['result'] = "Login accepted with email";

                unset($row['hash']);
                unset($row['salt']);
                unset($row['recoverId']);

                //$_SESSION['lang'] = $row['lang'];
                $_SESSION['sessionId'] = Misc::str_random(32);

                $sql = "INSERT INTO `sessions` (`sessionId`, `userId`, `lastPing`, `validUntil`) VALUES ('" . $_SESSION['sessionId'] . "', '" . $row['userId'] . "', '" . date("Y-m-d H:i:s", strtotime('+0hour')) . "', '" . date("Y-m-d H:i:s", strtotime('+1 hour')) . "');";

                if(!$result = $db->query($sql))
                {
                    die('There was an error running the query [' . $db->error . '] line 99');
                }

                echo '<br />Redirecting to ' . $_GET['r'] . '<br />';

                $_SESSION['login_ok'] = $row;
                header("Location: ../" . $_GET['r']);
            }
            else
            {
                echo 'Username or password incorrect.  ERROR: 003';
                $_SESSION['result'] = "Username or password incorrect.  ERROR: 003";
                header("Location: ../index.php?login");
            }
        }
        else
        {
            echo 'Username or password incorrect.  ERROR: 004';
            $_SESSION['result'] = "Username or password incorrect.  ERROR: 004";
            header("Location: ../index.php?login");
        }
    }

    echo 'Username or password incorrect.  ERROR: 005';
    $_SESSION['result'] = "Username or password incorrect.  ERROR: 005";
    header("Location: ../index.php?login");
}
?>