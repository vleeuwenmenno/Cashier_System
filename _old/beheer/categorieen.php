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
  $subpage = process_input_categorieen();
  display_categorieen_page($subpage);
}

function display_categorieen_page($subpage)
{ 
  global $module;
  global $httpdir;


  display_header(); 
  echo '<body>';
  display_stylesheet();
  echo '<script type="text/javascript" src="'.$httpdir.'/php/jsfuncties.js"></script>';
  echo '<div class="screen">';
  display_beheer_menu(MCATEGORIEEN, $subpage);
  display_categorieen_main($subpage);
  echo '</div>';
  echo '</body>';
  echo '</html>';
}

function display_categorieen_main($subpage)
{
  global $module;
  global $klantid;
  extract($_GET);
  extract($_POST);
  
  display_categorieen_query();
  display_categorieen_result();
}

function display_categorieen_result()
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

function process_input_categorieen()
{
  global $module;
  global $submit;
  global $query;
  global $categorieid;
  global $naam;
  
  extract($_GET);
  extract($_POST);
  
  if( !isset( $submit ) )
  {
        $query = "";
        $subpage = SMAIN;
  }
  elseif( $submit == "Zoeken" )
  {
     $query = make_categorie_query();
     $_SESSION['lastquery'] = $query;
     $subpage = SMAIN;
  }
  elseif( $submit == "Alle Categorieen" )
  {
     $query = "select * from categorie order by naam";
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
      $categorieid = toevoegen_categorie($naam);
      $query = make_categorie_query();
      $subpage = SMAIN;
  }
  elseif( $submit == "Verwijderen" )
  {
      verwijderen_categorie($categorieid);
      unset($categorieid);
      $query = $_SESSION['lastquery'];
      $subpage = SMAIN;
  }

  return $subpage;  
}


function display_result_header()
{
  echo "<TR><TH>Categorie</TH></TR>";
}

function display_result_row($row, $i)
{
    echo "<TR>";
    echo '<input id="categorieid'.$i. '" type=hidden value="'. $row->id . '"/>';
    echo "<TD class=textval >$row->naam</TD>";
    echo '<TD><input class=resultbutton type="button" value="Ver" onclick="verwijderen_categorie('.$i.')"</TD>';
    echo "</TR>";
}

function reset_form_variables()
{
  global $naam;

  $naam="";
}

function display_categorieen_query()
{
  global $httpdir;
  global $naam;
 
  echo '<div class="querybox">', "\n";
  //echo '<TABLE align="center" class="requesttable">';
  echo '<div class=queryinput>';
  echo '<TABLE class="requesttable">';  
  echo '<form action="', "${httpdir}", '/beheer/categorieen.php" name="result" method="post">', NL;
  echo "<TR><TD class=textval>Naam</TD><TD><input type=text name=\"naam\" value=\"$naam\"></TD></TR>";
  echo '</TABLE>';
  echo '</div>';
  echo '<div class="querybutton">';
  echo '<input class=textval type="submit" name="submit" value="Zoeken" />';
  echo '<input class=textval type="submit" name="submit" value="Alle Categorieen" />';
  echo '<input class=textval type="submit" name="submit" value="Toevoegen" />';
  echo '<input class=textval type="submit" name="submit" value="Reset" />';
  echo '</div>';
  echo '</FORM>';
  echo '</div>', "\n"; 

}

function make_categorie_query()
{
   
   extract($_GET);  
   extract($_POST);  

   $query="";
   if( isset($naam) && $naam!="")
   { 
     $query="naam LIKE '%$naam%'"; 
   } 

   if( isset($categorieid) && $categorieid!="")
   { 
     if( $query == "" ) { $query="id = $categorieid"; } 
     else { $query = $query." and id = $categorieid"; }
   }
   
   if( $query != "") $query = "select * from categorie where ".$query." order by naam";
   return $query;
   
}

?>