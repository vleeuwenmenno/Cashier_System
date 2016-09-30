<?php
include("include.php");
session_start();

if( !isset( $_SESSION['usernaam'] ) )
{
  header("Location: login.php");
  exit();
}
else
{  
  $module = new Module();
  $module->set_module( $_SESSION['usernaam'],'Beheer');
  //$kassastatus = new KassaStatus();
  //$kassastatus->update();
  display_beheer_page();
}

function display_beheer_page()
{ 
  global $module;
  global $httpdir;
  // global $kassastatus;

  display_header(); 
  echo '<body>';
  display_stylesheet();
  echo '<script src="'.$httpdir.'/php/jsfuncties.js"></script>';
  echo '<div class="screen">';
  display_beheer_menu(MBEHEER, SMAIN);
  display_beheer_main();
  echo '</div>';
  echo '</body>';
  echo '</html>';
}

function display_beheer_main()
{
  global $module;

  echo '<div class="mainbox">', "\n";
  echo "BEHEER: $module->modulenaam <br>", "\n";
  //echo "BEHEERID: ${_SESSION['kassaid']} <br>", "\n";
//  echo "PASSWORD: ${_POST['password']} <br>", "\n";
  echo "IPADDRESS: $module->ipaddress <br>", "\n";
  echo "GEBRUIKER: $module->usernaam <br>", "\n";
  echo '</div>', "\n"; 
}
?>