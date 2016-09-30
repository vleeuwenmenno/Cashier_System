<?php
include("include.php");
session_start();

if(!isset($_POST['user'])) 
{
  $modulenaam = get_modulenaam_locatie($_SERVER['REMOTE_ADDR'], 'Beheer' );
  if( $modulenaam != "ONBEKEND" )
  {
    display_login_page();
  }
  else
  {
     echo "Beheer is niet toegankelijk vanaf deze machine.";
     exit();
  }
}   
else 
{
  $retval = logincomtoday( $_POST['user'], $_POST['password'], 'Beheer' );
  if( $retval == true )
  { 
    $_SESSION['usernaam'] = $_POST['user'];
    $_SESSION['moduletype'] = 'Beheer';
    $module = new Module();
    $module->set_module($_SESSION['usernaam'], $_SESSION['moduletype']);

    header("Location: beheer.php");
    exit();
  }
  else
  {
    display_login_page();
  }
}

function display_login_page()
{ 
  global $modulenaam;
  global $httpdir;

  display_header("Login - $modulenaam"); 
  echo '<body>';
  display_stylesheet('Beheer');
  echo '<div class="screen">';
  echo '<script src="'.$httpdir.'/php/jsfuncties.js"></script>';
  display_login_menu();
  display_login_main();
  echo '</div>';
  echo '</body>';
  echo '</html>';
}

function display_login_main()
{
   global $httpdir;
   global $modulenaam;

  echo '<div class="mainbox">', NL;	
  echo "Beheer: $modulenaam", '<br>';
  echo '<form action="', "${httpdir}", '/beheer/login.php" method="post">', NL;
  echo 'MEDEWERKER:  <input type="text" name="user"><br>', NL;
  echo 'PASSWORD: <input type="password" name="password"><br>', NL;
  echo '<input type="submit" name="submit" value="LOGIN" />', NL;
  echo '</form>', NL;
  echo '</div>', "\n"; 
}

function display_login_menu()
{
  display_beheer_menu(MLOGIN,SMAIN);
}
?>