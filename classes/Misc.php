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

    function filter_bad_words($matches)
    {
		$replace = $_LANG_BAD_EN[$matches[0]];
		$replace = $_LANG_BAD_NL[$matches[0]];
		return isset($replace) ? $replace : $matches[0];
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
}
?>