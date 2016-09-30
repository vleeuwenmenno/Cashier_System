<?php
include("include.php");
session_start();

if(!isset($_POST['user'])) 
{
  $retval = logincomtoday( $_POST['user'], $_POST['password'], 'Kassa' );
  if( $retval == true )
  { 
    $_SESSION['usernaam'] = $_POST['user'];
    $_SESSION['moduletype'] = 'Kassa';
    $module = new Module();
    $module->set_module($_SESSION['usernaam'], 'Kassa');

    $kassastatus = new KassaStatus();
    $kassastatus->update();

    header("Location: kassa.php");
    exit();
  }
  else
  {
    $modulenaam = $_SESSION['modulenaam'];
    display_login_page();
  }
}

function display_login_page()
{ 
  global $httpdir;
  global $modulenaam;

  display_header("Login - $modulenaam"); 
  echo '<body>';
  display_stylesheet('Kassa');
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
  global $modulenaam;
  global $httpdir;

  echo '<div class="mainbox">', NL;	
  echo "KASSA: $modulenaam", '<br>';
  echo '<form action="', "${httpdir}", '/kassa/login.php" method="post">', NL;
  echo 'MEDEWERKER:  <input type="text" name="user"><br>', NL;
  echo 'PASSWORD: <input type="password" name="password"><br>', NL;
  echo '<input type="submit" name="submit" value="LOGIN" />', NL;
  echo '</form>', NL;
  echo '</div>', "\n"; 
}

function display_login_menu()
{
  display_kassa_menu(MLOGIN,SMAIN);
}
?>