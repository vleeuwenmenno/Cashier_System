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
  $module->set_module( $_SESSION['usernaam'], 'Kassa');
  $subpage = process_input_klanten();
  display_klanten_page($subpage);
}

function display_klanten_page($subpage)
{ 
  global $module;
  global $httpdir;

  display_header(); 
  echo '<body>';
  display_stylesheet();
  echo '<script type="text/javascript" src="'.$httpdir.'/php/jsfuncties.js"></script>';
  echo '<div class="screen">';
  display_kassa_menu(MKLANTEN, $subpage);
  display_klanten_main($subpage);
  echo '</div>';
  echo '</body>';
  echo '</html>';
}

function display_klanten_main($subpage)
{
  global $module;
  global $klantid;

  if( $subpage == SWIJZIGEN )
  {
    display_klanten_wijzigen($klantid);
  }
  else
  {
     display_klanten_query();
     display_klanten_result();
  }
}

function display_klanten_result()
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



function process_input_klanten()
{
  global $module;
  global $submit;
  global $query;
  global $klantid;
 
/*  echo $submit;
  echo $klantid;
  exit;    */
  if( !isset( $submit ) )
  {
        $query = "";
        $subpage = SMAIN;
  }
  elseif( $submit == "Zoeken" )
  {
     $query = make_klant_query();
     $_SESSION['lastquery'] = $query;
     $subpage = SMAIN;
  }
  elseif( $submit == "Reset" )
  {
      reset_form_variables();
      $query = "";
      $subpage = SMAIN;
  }
  elseif( $submit == "Wijzigen" )
  {
      $subpage = SWIJZIGEN;
  }
  elseif( $submit == "Opslaan" )
  {
      update_klant();
      $query = make_klant_query();
      $subpage = SMAIN;
  }
  elseif( $submit == "Annuleren" )
  {
      $query = $_SESSION['lastquery'];
      $subpage = SMAIN;
  }
  elseif( $submit == "Nieuw" )
  {
      $klantid = -1;
      $subpage = SWIJZIGEN;
  }

  return $subpage;  
}


function display_result_header()
{
  echo "<TR><TH>Voorletters</TH><TH>Achternaam</TH><TH>Bedrijfsnaam</TH>";
  echo "<TH>Straat</TH><TH>Huisnr</TH><TH>Woonplaats</TH>";
  echo "<TH>Telefoon</TH><TH>Email</TH></TR>";
}

function display_result_row($row, $i)
{
    echo "<TR>";
    echo '<input id="klantid'.$i. '" type=hidden value="'. $row->id . '"/>';
    echo "<TD>$row->voorletters $row->tussenvoegsel</TD>";
    echo "<TD>$row->achternaam</TD>";
    echo "<TD>$row->bedrijfsnaam</TD>";
    echo "<TD>$row->straat</TD>";
    echo "<TD>$row->huisnr</TD>";
    echo "<TD>$row->woonplaats</TD>";
    echo "<TD>$row->telefoon</TD>";
    echo "<TD>$row->email</TD>";
    echo '<TD><input class-resultbutton type="button" value="Wijzigen" onclick="wijzigen_klant('.$i.')"</TD>';
    echo "</TR>";
}

function reset_form_variables()
{
  global $achternaam, $bedrijfsnaam, $contactpersoon, $woonplaats;

  $achternaam="";
  $bedrijfsnaam = "";
  $contactpersoon ="";
  $woonplaats = "";
}

function display_klanten_query()
{
  global $httpdir;
  global $achternaam;
  global $bedrijfsnaam;
  global $woonplaats;
  global $fulltext;
  global $postcode;

  echo '<div class="querybox">', "\n";
  //echo '<TABLE align="center" class="requesttable">';
  echo '<div class=queryinput>';
  echo '<TABLE class="requesttable">';  
  echo '<form action="', "${httpdir}", '/kassa/klanten.php" name="result" method="post">', NL;
  echo "<TR><TD>Postcode</TD><TD><input type=text name=\"postcode\" value=\"$postcode\"></TD></TR>";
  echo "<TR><TD>Achternaam</TD><TD><input type=text name=\"achternaam\" value=\"$achternaam\"></TD></TR>";
  echo "<TR><TD>Bedrijfsnaam</TD><TD><input type=text name=\"bedrijfsnaam\" value=\"$bedrijfsnaam\"></TD></TR>";
  echo "<TR><TD>Woonplaats</TD><TD><input type=text name=\"woonplaats\" value=\"$woonplaats\"></TD></TR>";
  echo "</TD></TR>";
  echo '</TABLE>';
  echo '</div>';
  echo '<div class="querybutton">';
  echo '<input type="submit" name="submit" value="Zoeken" />';
  echo '<input type="submit" name="submit" value="Reset" />';
  echo '<input type="submit" name="submit" value="Nieuw" />';
  echo '</div>';
  echo '</FORM>';
  echo '</div>', "\n"; 

}
?>