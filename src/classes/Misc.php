<?php
class Misc
{
    public static function startsWith($haystack, $needle)
    {
        // search backwards starting from haystack length characters from the end
        return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== false;
    }

    public static function endsWith($haystack, $needle)
    {
        // search forward starting from end minus needle length characters
        return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== false);
    }

    public static function url_get_contents ($Url)
    {
        if (!function_exists('curl_init'))
        {
            echo 'CURL is not installed!';
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $Url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    function filter_bad_words($matches)
    {
		$replace = $_LANG_BAD_EN[$matches[0]];
		$replace = $_LANG_BAD_NL[$matches[0]];
		return isset($replace) ? $replace : $matches[0];
    }

    public static function sqlExists($item, $equals, $table)
    {
        $sql = "SELECT $item FROM $table WHERE $item='$equals';";
        global $config;

        $db = new mysqli($config['SQL_HOST'], $config['SQL_USER'], $config['SQL_PASS'], $config['SQL_DB']);

        if($db->connect_errno > 0)
        {
            die('Unable to connect to database [' . $db->connect_error . ']');
        }

        if(!$result = $db->query($sql))
        {
            die('Er was een fout tijdens het uitvoeren van deze query (' . $db->error . ') (' . $sql . ')');
        }

        $i = 0;
        while($row = $result->fetch_assoc())
        {
            $i++;
        }

        if ($i > 0)
            return true;
        else
            return false;
    }

	public static function sqlGet($what, $table, $something, $isSomething)
	{
		$sql = "SELECT $what FROM $table WHERE $something='$isSomething';";

		global $config;

		$db = new mysqli($config['SQL_HOST'], $config['SQL_USER'], $config['SQL_PASS'], $config['SQL_DB']);

		if($db->connect_errno > 0)
		{
			die('Unable to connect to database [' . $db->connect_error . ']');
		}

		if(!$result = $db->query($sql))
		{
			die('Er was een fout tijdens het uitvoeren van deze query (' . $db->error . ') (' . $sql . ')');
		}

		while($row = $result->fetch_assoc())
		{
			return $row;
		}
	}

	public static function sqlGetAll($what, $table)
	{
		$sql = "SELECT $what FROM $table;";

		global $config;

		$db = new mysqli($config['SQL_HOST'], $config['SQL_USER'], $config['SQL_PASS'], $config['SQL_DB']);

		if($db->connect_errno > 0)
		{
			die('Unable to connect to database [' . $db->connect_error . ']');
		}

		if(!$result = $db->query($sql))
		{
			die('Er was een fout tijdens het uitvoeren van deze query (' . $db->error . ') (' . $sql . ')');
		}

		while($row = $result->fetch_assoc())
		{
			return $row;
		}
	}

    public static function sqlUpdate($table, $what, $becomes, $something, $isSomething)
	{
		$sql = "UPDATE $table SET $what = $becomes WHERE $something = '$isSomething';";
		global $config;

		$db = new mysqli($config['SQL_HOST'], $config['SQL_USER'], $config['SQL_PASS'], $config['SQL_DB']);

		if($db->connect_errno > 0)
		{
			die('Unable to connect to database [' . $db->connect_error . ']');
		}

		if(!$result = $db->query($sql))
		{
			return ('Er was een fout tijdens het uitvoeren van deze query (' . $db->error . ') (' . $sql . ')');
		}
	}

    public static function sql($sql)
	{
        global $config;

		$db = new mysqli($config['SQL_HOST'], $config['SQL_USER'], $config['SQL_PASS'], $config['SQL_DB']);

		if($db->connect_errno > 0)
		{
			return 'Unable to connect to database [' . $db->connect_error . ']';
		}

		if(!$result = $db->query($sql))
		{
			return 'Er was een fout tijdens het uitvoeren van deze query (' . $db->error . ') (' . $sql . ')';
		}

        return true;
	}

	public static function crIsActive()
	{
		global $config;

		$ok = false;
		$thisIp = $_SERVER['REMOTE_ADDR'];

		$db = new mysqli($config['SQL_HOST'], $config['SQL_USER'], $config['SQL_PASS'], $config['SQL_DB']);

		if($db->connect_errno > 0)
		{
			die('Unable to connect to database [' . $db->connect_error . ']');
		}

		$sql = "SELECT * FROM cash_registers WHERE crStaticIP='$thisIp';";

		if(!$result = $db->query($sql))
		{
			die('Er was een fout tijdens het verwerken van de klant gegevens. (' . $db->error . ')');
		}

		while($row = $result->fetch_assoc())
		{
			if ($row['status'] == "LoggedOff")
				return false;
			else
				return true;
		}

		if (!$ok)
		{
			return false;
		}
	}

    public static function isApple()
    {
        //Detect special conditions devices
        $iPod    = stripos($_SERVER['HTTP_USER_AGENT'],"iPod");
        $iPhone  = stripos($_SERVER['HTTP_USER_AGENT'],"iPhone");
        $iPad    = stripos($_SERVER['HTTP_USER_AGENT'],"iPad");
        $Android = stripos($_SERVER['HTTP_USER_AGENT'],"Android");
        $webOS   = stripos($_SERVER['HTTP_USER_AGENT'],"webOS");

		//do something with this information
        if( $iPod || $iPhone )
        {
            return true;
        }
        else if($iPad)
        {
            return true;
        }
        else if($Android)
        {
            return false;
        }
        else if($webOS)
        {
            return false;
        }
        else
            return false;
    }

    public static function isMobile()
    {
        //Detect special conditions devices
        $iPod    = stripos($_SERVER['HTTP_USER_AGENT'],"iPod");
        $iPhone  = stripos($_SERVER['HTTP_USER_AGENT'],"iPhone");
        $iPad    = stripos($_SERVER['HTTP_USER_AGENT'],"iPad");
        $Android = stripos($_SERVER['HTTP_USER_AGENT'],"Android");
        $webOS   = stripos($_SERVER['HTTP_USER_AGENT'],"webOS");

		//do something with this information
        if( $iPod || $iPhone || $iPad || $Android || $webOS)
        {
            return true;
        }
        else
            return false;
    }

    public static function pwdCheck($pwd)
    {
        $error = "";

        if( strlen($pwd) < 8 )
            $error .= "Password too short!<br />";
        if( strlen($pwd) > 128 )
            $error .= "Dude, this password is way too long.<br />";
        if( strlen($pwd) < 8 )
            $error .= "Password too short!";
        if( !preg_match("#[0-9]+#", $pwd) )
            $error .= "Password must include at least one number!<br />";
        if( !preg_match("#[A-Z]+#", $pwd) )
            $error .= "Password must include at least one upper-case letter!<br />";
        if( !preg_match("#\W+#", $pwd) )
            $error .= "Password must include at least one symbol!<br />";
        if ($error == "")
            return "OK";
        else
            return $error;
    }

    public static function str_random($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    // calculates the result of an expression in infix notation
	public static function calculate($exp) {
        $exp = str_replace(',', '.', $exp);
		return Misc::calculate_rpn(Misc::mathexp_to_rpn($exp));
	}

	// calculates the result of an expression in reverse polish notation
	public static function calculate_rpn($rpnexp) {
		$stack = array();
		foreach($rpnexp as $item) {
			if (Misc::is_operator($item)) {
				if ($item == '+') {
					$j = array_pop($stack);
					$i = array_pop($stack);
					array_push($stack, $i + $j);
				}
				if ($item == '-') {
					$j = array_pop($stack);
					$i = array_pop($stack);
					array_push($stack, $i - $j);
				}
				if ($item == '*') {
					$j = array_pop($stack);
					$i = array_pop($stack);
					array_push($stack, $i * $j);
				}
				if ($item == '/') {
					$j = array_pop($stack);
					$i = array_pop($stack);
					array_push($stack, $i / $j);
				}
				if ($item == '%') {
					$j = array_pop($stack);
					$i = array_pop($stack);
					array_push($stack, $i % $j);
				}
			} else {
				array_push($stack, $item);
			}
		}
		return round($stack[0], 2);
	}

	// converts infix notation to reverse polish notation
	public static function mathexp_to_rpn($mathexp) {
		$precedence = array(
			'(' => 0,
			'-' => 3,
			'+' => 3,
			'*' => 6,
			'/' => 6,
			'%' => 6
		);

		$i = 0;
		$final_stack = array();
		$operator_stack = array();

		while ($i < strlen($mathexp)) {
			$char = $mathexp[$i];
			if (Misc::is_number($char)) {
				$num = Misc::readnumber($mathexp, $i);
				array_push($final_stack, $num);
				$i += strlen($num); continue;
			}
			if (Misc::is_operator($char)) {
				$top = end($operator_stack);
				if ($top && $precedence[$char] <= $precedence[$top]) {
					$oper = array_pop($operator_stack);
					array_push($final_stack, $oper);
				}
				array_push($operator_stack, $char);
				$i++; continue;
			}
			if ($char == '(') {
				array_push($operator_stack, $char);
				$i++; continue;
			}
			if ($char == ')') {
				// transfer operators to final stack
				do {
					$operator = array_pop($operator_stack);
					if ($operator == '(') break;
					array_push($final_stack, $operator);
				} while ($operator);
				$i++; continue;
			}
			$i++;
		}
		while ($oper = array_pop($operator_stack)) {
			array_push($final_stack, $oper);
		}
		return $final_stack;
	}

	public static function readnumber($string, $i) 
	{
		$number = '';

		while (Misc::is_number(substr($string, $i, 1)))
		{
			$number .= $string[$i];
			$i++;
		}
		return $number;
	}

	public static function is_operator($char) {
		static $operators = array('+', '-', '/', '*', '%');
		return in_array($char, $operators);
	}

	public static function is_number($char) 
	{
		return (($char == '.') || ($char >= '0' && $char <= '9'));
	}
}
?>
