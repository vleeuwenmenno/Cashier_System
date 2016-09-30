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
  $module->set_module( $_SESSION['usernaam'], 'Beheer');
  $query = "";
  //$lastquery = "";

  $subpage = process_input_overzichten();
  display_overzichten_page($subpage);
}

function display_overzichten_page($subpage)
{ 
  global $module;
  global $httpdir;

  //  echo $subpage;exit;
  display_header(); 
  echo '<body>';
  display_stylesheet();
  echo '<script src="'.$httpdir.'/php/jsfuncties.js"></script>';
  echo '<div class="screen">';
  display_beheer_menu(MOVERZICHTEN, $subpage);
  display_overzichten_main(MOVERZICHTEN, $subpage);
  echo '</div>';
  echo '</body>';
  echo '</html>';
}

function display_overzichten_main($menuoption, $subpage)
{
  display_overzichten_query();
  display_overzichten_result($subpage);
}

function display_overzichten_query()
{
  global $httpdir;
  global $begindag, $beginmaand, $beginjaar;
  global $einddag, $eindmaand, $eindjaar;

  echo '<div class="querybox">', "\n";
  //echo '<TABLE align="center" class="requesttable">';
  echo '<div class=queryinput>';
  echo '<TABLE class="requesttable">';  
  echo '<form action="', "${httpdir}", '/beheer/overzichten.php" method="post">', NL;
  echo "<TR><TD>Begin datum</TD>";
  echo "<TD>", display_select_dag("begindag", $begindag), "</TD>";   
  echo "<TD>", display_select_maand("beginmaand", $beginmaand), "</TD>";   
  echo "<TD>", display_select_jaar("beginjaar", $beginjaar), "</TD>";   
  echo "</TR>";
  echo "<TR><TD>Eind datum</TD>";
  echo "<TD>", display_select_dag("einddag", $einddag), "</TD>";   
  echo "<TD>", display_select_maand("eindmaand", $eindmaand), "</TD>";   
  echo "<TD>", display_select_jaar("eindjaar", $eindjaar), "</TD>";   
  echo "</TR>";
  echo '</TABLE>';
  echo '</div>';
  echo '<div class="querybutton">';
  echo '<input class=textval type="submit" name="submit" value="Voorraad" />';
  echo '<input class=textval type="submit" name="submit" value="Verkoop" />';
  echo '<input class=textval type="submit" name="submit" value="VoorraadMutaties" />';
  echo '<input class=textval type="submit" name="submit" value="Omzet" />';
  echo '</div>';
  echo '</FORM>';
  echo '</div>', "\n"; 

}

function display_overzichten_result($subpage)
{
  global $query, $message;

  if( $subpage == SMAIN )
  { 
     display_main_result();
  }
  elseif( $subpage == SMESSAGE )
  {
     display_message_result();     
  }
  elseif( $subpage == SVERKOOP )
  {
     display_verkoop_result();
  }
  elseif( $subpage == SVOORRAAD )
  {
     display_voorraad_result();
  }
  elseif( $subpage == SOMZET )
  {
     display_omzet_result();
  }
  elseif( $subpage == SMUTATIES )
  {
     display_mutaties_result();
  }
  elseif( $subpage == SAFDRUKKEN )
  {
     display_afdrukken_result(true);
  }
 
}

function display_message_result()
{
  global $message;
  echo '<div class=resultbox>';
  echo '<pre>', $message, '</pre>';
  echo '</div>';

}

function display_verkoop_result()
{
  global $httpdir;
  $fname = get_print_filename_nodate('overzicht');
  print_verkoop($fname);
  display_afdrukken_result(false);
}

function display_mutaties_result()
{  
  global $httpdir;
  $fname = get_print_filename_nodate('overzicht');
  print_mutaties($fname);
  display_afdrukken_result(false);
}

function display_voorraad_result()
{  
  global $httpdir;
  $fname = get_print_filename_nodate('overzicht');
  print_voorraad($fname);
  display_afdrukken_result(false);
}

function display_omzet_result()
{  
  global $httpdir;
  $fname = get_print_filename_nodate('overzicht');
  print_omzet($fname);
  display_afdrukken_result(false);
}

function display_afdrukken_result($print)
{
  global $httpdir;
  global $begindag, $beginmaand, $beginjaar, $einddag, $eindmaand, $eindjaar;
  $fname = get_print_filename_nodate('overzicht');
  if( $print){ copy_to_spool($fname, 1); }
  echo '<div class="resultbox">', "\n";
  echo '<form action="', "${httpdir}", '/beheer/overzichten.php" method="post">', NL;
  echo '<input class=textval type="submit" name="submit" value="Afdrukken" />';
  echo "<input type=hidden name=begindag value=$begindag />";
  echo "<input type=hidden name=beginmaand value=$beginmaand />";
  echo "<input type=hidden name=beginjaar value=$beginjaar />";
  echo "<input type=hidden name=einddag value=$einddag />";
  echo "<input type=hidden name=eindmaand value=$eindmaand />";
  echo "<input type=hidden name=eindjaar value=$eindjaar />";
  echo '</form>';
  echo '<PRE class=printbon>'; 
  include("$fname");
  echo '</PRE>';
  echo '</div>', "\n"; 
}

function process_input_overzichten()
{
  global $kassastatus;
  global $module;
  global $submit;
  global $query;
  global $artikelid;

  if( !isset( $submit ) )
  { 
     reset_form_variables();
     $subpage = SMAIN;
  }
  elseif( $submit == "VoorraadMutaties" )
  {
     if( check_input() == true )
     {
        $subpage = SMUTATIES;
     }
     else
     {
        $subpage = SMESSAGE;
     }
  }
  elseif( $submit == "Verkoop" )
  {
     if( check_input() )
     {
       $subpage = SVERKOOP;
     }
     else
     {
        $subpage = SMESSAGE;
     }
  }
  elseif( $submit == "Omzet" )
  {
     if( check_input() )
     {
       $subpage = SOMZET;
     }
     else
     {
        $subpage = SMESSAGE;
     }
  }
  elseif( $submit == "Voorraad" )
  {
        $subpage = SVOORRAAD;
  }
  elseif( $submit == "Afdrukken" )
  {
      $subpage = SAFDRUKKEN;
  }

  return $subpage;  

}

function reset_form_variables()
{
  global $begindag, $beginmaand, $beginjaar, $einddag, $eindmaand, $eindjaar;
  global $week, $maand;

  $begindag=date('d');
  $beginmaand = date('m');
  $beginjaar=date('Y');
  $einddag = date('d');
  $eindmaand = date('m');
  $eindjaar = date('Y');
}

function check_input()
{
  global $begindag, $beginmaand, $beginjaar, $einddag, $eindmaand, $eindjaar;
  global $week, $maand;
  global $message;

  if( $begindag !="" && $beginmaand !="" && $beginjaar!="" &&
      $einddag !=""  && $eindmaand !="" && $eindjaar!="" &&
      checkdate( $beginmaand, $begindag, $beginjaar) &&
      checkdate( $eindmaand, $einddag, $eindjaar) )
  {
      $retval = true;
  }
  else
  {
     $message = "Niet alle datumvelden gevuld of ongeldig datum";
     $retval = false;
  }

  return $retval;
}
?>