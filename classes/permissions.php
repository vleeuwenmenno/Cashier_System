<?php

class Permissions
{
    public static function checkSession($r, $rt = true)
    {
        global $config;

        $db = new mysqli($config['SQL_HOST'], $config['SQL_USER'], $config['SQL_PASS'], $config['SQL_DB']);

        if($db->connect_errno > 0)
        {
            die('Unable to connect to database [' . $db->connect_error . ']');
        }

        $sql = "DELETE FROM sessions WHERE now() > validUntil;";

        if(!$result = $db->query($sql))
        {
            die('There was an error running the query [' . $db->error . ']');
        }

        if($db->connect_errno > 0)
        {
            die('Unable to connect to database [' . $db->connect_error . ']');
        }

        $sql = "SELECT * FROM `sessions` WHERE `sessionId`=\"" . $_SESSION['sessionId'] . "\"";

        if(!$result = $db->query($sql))
        {
            die('There was an error running the query [' . $db->error . ']');
        }

        while($row = $result->fetch_assoc())
        {
            $time_diff = strtotime($row['validUntil']) - strtotime('+0hour');

            if (($time_diff / 60) <= 0)
            {
                $sql = "DELETE FROM sessions WHERE sessionId='" . $_SESSION['sessionId'] . "';";

                if(!$result = $db->query($sql))
                {
                    die('There was an error running the query [' . $db->error . ']');
                }

                unset($_SESSION['sessionId']);
                unset($_SESSION['login_ok']);

                $_SESSION['result'] = $_LANG['SESSION_EXP'];

				if ($rt)
					header("Location: index.php?login&r =" . $r);
				else
					return false;
            }
            else if (($time_diff / $config['timeout']) > 0)
            {
                $sqls = "UPDATE sessions SET lastPing = '" . date('Y/m/d H:i:s') . "' WHERE sessionId = '" . $_SESSION['sessionId'] . "'";

                if(!$results = $db->query($sqls))
                {
                    die('There was an error running the query [' . $db->error . ']');
                }

                $i = 1;

				return true;
            }
        }

        if ($i == 0)
        {
            unset($_SESSION['sessionId']);
            unset($_SESSION['login_ok']);

            $_SESSION['result']                 = $_LANG['PLEASE_LOGIN'];

			if ($rt)
				header("Location: index.php?login&r =" . $r);
			else
				return false;
        }
    }
}

?>