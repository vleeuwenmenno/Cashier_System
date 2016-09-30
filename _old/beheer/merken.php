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
  $subpage = process_input_merken();
  display_merken_page($subpage);
}

function display_merken_page($subpage)
{ 
  global $module;
  global $httpdir;

  display_header(); 
  echo '<body>';
  display_stylesheet();
  echo '<script type="text/javascript" src="'.$httpdir.'/php/jsfuncties.js"></script>';
  echo '<div class="screen">';
  display_beheer_menu(MMERKEN, $subpage);
  display_merken_main($subpage);
  echo '</div>';
  echo '</body>';
  echo '</html>';
}

function display_merken_main($subpage)
{
  global $module;
  global $klantid;

  display_merken_query();
  display_merken_result();
}

function display_merken_result()
{
  global $query;

  echo '<div class="resultbox">', "\n";
  if( $query == "" )
  {
    echo "Geen resultaten";
  }
  else
  {
    $dbserver = dbconnect();
    $result = dbquery($query);
    if( mysql_num_rows($result) == 0 )
    {
       echo "Geen resultaten"; 
    }
    else
    {
      $i=0;
      echo '<TABLE align="center" class="resulttable">';
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
  echo '</div>', "\n"; 

}

function process_input_merken()
{
  global $module;
  global $submit;
  global $query;
  global $merkid;
  global $naam;
  
  extract($_GET);
  extract($_POST);
  
  //echo $submit;exit;
 
  if( !isset( $submit ) )
  {
        $query = "";
        $subpage = SMAIN;
  }
  elseif( $submit == "Zoeken" )
  {
     $query = make_merk_query();
     $_SESSION['lastquery'] = $query;
     $subpage = SMAIN;
  }
  elseif( $submit == "Alle Merken" )
  {
  	 $query = "select * from merk order by naam";
     $_SESSION['lastquery'] = $query;
     $subpage = SMAIN;
  }
  elseif( $submit == "Reset" )
  {
      reset_form_variables();
      $query = "";
      $subpage = SMAIN;
  }
  elseif( $submit == "Toevoegen" )
  {
      $merkid = toevoegen_merk($naam);
      $query = make_merk_query();
      $subpage = SMAIN;
  }
  elseif( $submit == "Verwijderen" )
  {
      verwijderen_merk($merkid);
      unset($merkid);
      $query = $_SESSION['lastquery'];
      $subpage = SMAIN;
  }

  return $subpage;  
}


function display_result_header()
{
  echo "<TR><TH>Merk</TH></TR>";
}

function display_result_row($row, $i)
{
    echo "<TR>";
    echo '<input id="merkid'.$i. '" type=hidden value="'. $row->id . '"/>';
    echo "<TD class=textval >$row->naam</TD>";
    echo '<TD><input class=resultbutton type="button" value="Ver" onclick="verwijderen_merk('.$i.')"</TD>';
    echo "</TR>";
}

function reset_form_variables()
{
  global $naam;

  $naam="";
}

function display_merken_query()
{
  global $httpdir;
  global $naam;
 
  echo '<div class="querybox">', "\n";
  //echo '<TABLE align="center" class="requesttable">';
  echo '<div class=queryinput>';
  echo '<TABLE class="requesttable">';  
  echo '<form action="', "${httpdir}", '/beheer/merken.php" name="result" method="post">', NL;
  echo "<TR><TD class=textval>Naam</TD><TD><input type=text name=\"naam\" value=\"$naam\"></TD></TR>";
  echo '</TABLE>';
  echo '</div>';
  echo '<div class="querybutton">';
  echo '<input class=textval type="submit" name="submit" value="Zoeken" />';
  echo '<input class=textval type="submit" name="submit" value="Alle Merken" />';
  echo '<input class=textval type="submit" name="submit" value="Toevoegen" />';
  echo '<input class=textval type="submit" name="submit" value="Reset" />';
  echo '</div>';
  echo '</FORM>';
  echo '</div>', "\n"; 

}

function make_merk_query()
{
   
   extract($_GET);  
   extract($_POST);  

   $query="";
   if( isset($naam) && $naam!="")
   { 
     $query="naam LIKE '%$naam%'"; 
   } 

   if( isset($merkid) && $merkid!="")
   { 
     if( $query == "" ) { $query="id = $merkid"; } 
     else { $query = $query." and id = $merkid"; }
   }
   
   if( $query != "") $query = "select * from categorie where ".$query." order by naam";
   return $query;
   
}

?>