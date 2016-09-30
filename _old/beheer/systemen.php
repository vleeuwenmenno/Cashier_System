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
  $lastquery = "";

  $subpage = process_input_systemen();
  display_systemen_page($subpage);
}

function display_systemen_page($subpage)
{ 
  global $module;
  global $httpdir;

  display_header(); 
  echo '<body>';
  display_stylesheet();
  echo '<script src="'.$httpdir.'/php/jsfuncties.js"></script>';
  echo '<div class="screen">';
  display_beheer_menu(MSYSTEMEN, SMAIN);
  display_systemen_main();
  echo '</div>';
  echo '</body>';
  echo '</html>';
}

function display_systemen_main()
{
  $query = "select * from systeem";

  echo '<div class="mainbox">', "\n";
  if( $query == "" )
  {
    echo "Geen systemen gedefinieerd";
  }
  else
  {
    $dbserver = dbconnect();
    $result = dbquery($query);
    if( mysql_num_rows($result) == 0 )
    {
       echo "Geen systemen gedefinieerd"; 
    }
    else
    {
      $i=0;
      echo '<TABLE align="center" class="requesttable">';
      display_result_header();
      while ($row = mysql_fetch_object($result)) 
      { 
         display_result_row($row, $i);
         $i++;
      }
      echo '</TABLE>'; 
    }
    mysql_free_result($result);
    dbclose($dbserver); 
  }

  echo "<br><input type=button value=Nieuw onClick=\"open_systeem(-1);\"";
  echo '</div>', "\n"; 

}

function process_input_systemen()
{
  global $module;
  global $submit;
  global $query;

  $subpage = SMAIN;
  
  return $subpage;  
}

function display_result_row($row, $i)
{
    echo "<TR>";
    echo "<input type=hidden id=systeemid$i value=$row->id>";
    echo "<TD>$row->naam</TD>";
    display_moneyval($row->totaal);
    echo "<TD><input type=button value=\"Openen\" onClick=\"open_systeem($i)\"</TD>";
    echo "</TR>";
    
}

function display_result_header()
{
  echo "<TR><TH>Systeemnaam</TH><TH></TH><TH>Totaal</TH><TR>";
}
?>