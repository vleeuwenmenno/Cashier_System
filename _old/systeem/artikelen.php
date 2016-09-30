<?php
include("include.php");
session_start();

if( !isset( $_SESSION['usernaam'] ) )
{
  echo "Sessie beeindigd, opnieuw inloggen";
  exit();
}
else
{  
  $module = new Module();
  $module->set_module( $_SESSION['usernaam'], $_SESSION['moduletype']);
  $query = "";
  $lastquery = "";

  process_input_artikelen();
  display_artikelen_page();
}

function display_artikelen_page()
{ 
  global $module;
  global $httpdir;
  global $systeemid;

  display_header("Systeem: $systeemid"); 
  echo '<body>';
  display_stylesheet();
  echo '<script src="'.$httpdir.'/php/jsfuncties.js"></script>';
  echo '<div class="screen">';
  display_systeem_menu(MARTIKELEN, SMAIN);
  display_artikelen_main(MARTIKELEN);
  echo '</div>';
  echo '</body>';
  echo '</html>';
}

function display_artikelen_main()
{
  global $module;
  display_artikelen_query();
  display_artikelen_result();
  // display resultaat gedeelte
}

function display_artikelen_query()
{
  global $httpdir;
  global $type;
  global $merk;
  global $categorie;
  global $systeemid;

  echo '<div class="querybox">', "\n";
  //echo '<TABLE align="center" class="requesttable">';
  echo '<div class=queryinput>';
  echo '<TABLE class="requesttable">';  
  echo '<form action="', "${httpdir}", '/systeem/artikelen.php" method="post">', NL;
  echo "<input type=hidden name=systeemid value=$systeemid>";
  echo "<TR><TD>Type</TD><TD><input type=\"text\" name=\"type\" value=\"$type\"></TD></TR>";
  echo "<TR><TD>Merk</TD><TD>";
  display_select_merk($merk);
  echo "</TD></TR>";
  echo "<TR><TD>Categorie</TD><TD>";
  display_select_categorie($categorie);
  echo "</TD></TR>";
  echo '</TABLE>';
  echo '</div>';
  echo '<div class="querybutton">';
  echo '<input type="submit" name="submit" value="Zoeken" />';
  echo '<input type="submit" name="submit" value="Reset" />';
  echo '</div>';
  echo '</FORM>';
  echo '</div>', "\n"; 

}

function display_artikelen_result()
{
  global $query;
  global $systeemid;

  echo '<div class="resultbox">', "\n";
  echo "<input type=hidden id=systeemid value=$systeemid>";
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
  echo '</div>', "\n"; 

}

function process_input_artikelen()
{
  global $module;
  global $submit;
  global $query;

  if( !isset( $submit ) )
  {
     $subpage = SMAIN;
  }
  elseif( $submit == "Zoeken" )
  {
     $query = make_artikel_query();
     $subpage = SMAIN;
  }
  elseif( $submit == "Reset" )
  {
      reset_form_variables();
      $subpage = SMAIN;
  }

  return $subpage;  
}

function reset_form_variables()
{
  global $type, $categorie, $eol, $merk;

  $type="";
  $categorie = "";
  $eol="";
  $merk = "";
}

function display_result_header()
{
  echo "<TR><TH>Aantal</TH><TH>Categorie</TH><TH>Merk</TH><TH>Type</TH>";
  echo "<TH>Voorraad</TH><TH><TH colspan=2>Prijs</TH>";
  
}

function display_result_row($row, $i)
{
    echo "<TR>";
    echo "<input type=hidden id=artikelid$i value=$row->id>";
    echo "<TD><input type=text size=3 value=1 id=aantal$i ></TD>";
    echo "<TD>$row->categorie</TD>";
    echo "<TD>$row->merk</TD>";
    echo "<TD>$row->type</TD>";
    echo "<TD>$row->voorraad</TD>";
    display_moneyval($row->prijs);
    echo "<TD><input type=button value=\"Toevoegen\" onClick=\"toevoegen_systeem_artikel($i)\"</TD>";
    echo "</TR>";
    
}
?>