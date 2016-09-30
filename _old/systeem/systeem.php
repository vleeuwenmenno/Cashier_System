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
  $subpage = process_input_systeem();

  display_systeem_page($subpage);
}

function process_input_systeem()
{
  global $module;
  global $systeemid;
  global $naam;
    
  extract($_GET);
  extract($_POST);

  if( !isset( $submit ) )
  {
      $subpage = SMAIN;
  }
  elseif( $submit == "Annuleren" )
  {
      $subpage = SMAIN;
  }
  elseif( $submit == "Wijzigen" )
  {
      $subpage = SWIJZIGEN;
  }
  elseif( $submit == "NaamWijzigen" )
  {
      $subpage = SNAAM;
  }
  elseif( $submit == "Verwijderen" )
  {
      remove_systeem_item($systeemid,$systeemitemid );
      $subpage = SMAIN;
  }
  elseif( $submit == "Toevoegen" )
  {
       add_systeem_item($systeemid, $artikelid, $aantal);
       $subpage = SMAIN;
  }
  elseif( $submit == "Opslaan" )
  {
      update_systeem_item($systeemid, $systeemitemid, $omschrijving, $totaal);
      $subpage = SMAIN;
  }
  elseif( $submit == "NaamOpslaan" )
  {
      
      set_naam_systeem($systeemid, $naam);
      $subpage = SMAIN;
  }
  elseif ( $submit == "Open" )
  {
      if( $systeemid == -1 )
      {
         $systeem = new_systeem($systeemid);
         $systeemid=$systeem->id;
      }
      $subpage = SMAIN;
  }

  return $subpage;

}

function display_systeem_page($subpage)
{ 
  global $module;
  global $systeemid;
  global $httpdir;

  $systeem = get_systeem($systeemid);

  display_header("Systeem Nr: $systeem->naam"); 
  echo '<body>';
  display_stylesheet();
  echo '<script type="text/javascript" src="'.$httpdir.'/php/jsfuncties.js"></script>';
  echo '<div class="screen">';
  display_systeem_menu(MSYSTEEM,$subpage);
  display_systeem_main($subpage);
  echo '</div>';
  echo '</body>';
  echo '</html>'; 
}

function display_systeem_main($subpage)
{
  extract($_GET);
  extract($_POST);

  if( $subpage == SWIJZIGEN )
  {
     display_systeemitem_wijzigen($systeemid, $systeemitemid);
  }
  elseif( $subpage == SNAAM )
  {
     display_systeemnaam_wijzigen($systeemid);
  }
  else
  {
     display_systeem();
  }
}

function display_systeemitem($i, $systeemitem)
{
   echo "<TR>";
   echo "<input id=systeemitemid$i type=hidden value=$systeemitem->id />";
   echo"<TD>$systeemitem->aantal x</TD>";
   echo "<TD>$systeemitem->categorie</TD>";
   echo "<TD>$systeemitem->merk</TD>";
   echo "<TD>$systeemitem->type</TD>";
   echo "<TD></TD>";
   display_money($systeemitem->totaal);
   echo "<TD><input type=button value=\"Wijzigen\" onclick=\"wijzigen_systeemitem($i)\"</TD>";
   echo "<TD><input type=button value=\"Verwijderen\" onclick=\"verwijderen_systeemitem($i)\"</TD>";
   echo "</TR>";
   if( $systeemitem->omschrijving != '')
   { 
      echo "<TR><TD><TD><TD colspan=4>$systeemitem->omschrijving</TD></TR>";
   }
}

function display_systeemitem_wijzigen($systeemid, $systeemitemid)
{
  global $httpdir;

  $systeemitem = get_systeemitem($systeemitemid);
  $artikel = get_artikel($systeemitem->artikelid);

  echo '<div class="mainbox">', "\n";
  echo '<form action="', "${httpdir}", '/systeem/systeem.php" method="post">', NL;
  echo '<input type="hidden" name="systeemitemid" value='. $systeemitem->id . ' >';
  echo '<input type="hidden" name="systeemid" value='. $systeemid . ' >';
  echo '<TABLE align="center" class="requesttable">';
  echo "<TR><TH>Systeem Item</TH></TR>";
  echo "<TR><TD>Aantal</TD><TD>$systeemitem->aantal<TD></TR>";
  echo "<TR><TD>Categorie</TD><TD>$systeemitem->categorie<TD></TR>";
  echo "<TR><TD>Merk</TD><TD>$systeemitem->merk<TD></TR>";
  echo "<TR><TD>Type</TD><TD>$systeemitem->type<TD></TR>";
  echo "<TR colspan=3><TD>Omschrijving</TD><TD colspan=2><textarea name=\"omschrijving\" rows=1 cols=35>$systeemitem->omschrijving</textarea></TD></TR>";
  echo "<TR><TD>Prijs per stuk</TD>";
  display_moneyval($artikel->prijs);
  echo "</TR>";
  $totaalstr=get_moneystr($systeemitem->totaal);
  echo "<TR><TD></TD><TD><TD></TR>";
  echo "<TR col=2><TD>Totaal</TD><TD>&#8364</TD><TD> <input class=\"numval\" type=text name=totaal value=\"$totaalstr\" size=24></TD></TR>";
  echo "</TR>";

  echo '</TABLE>';
  echo '<input type="submit" name="submit" value="Annuleren" />';
  echo '<input type="submit" name="submit" value="Opslaan" />';
  echo '</FORM>';
  echo '</div>', "\n"; 
}

function display_systeemnaam_wijzigen($systeemid)
{
  global $httpdir;

  $systeem = get_systeem($systeemid);

  echo '<div class="mainbox">', "\n";
  echo '<form action="', "${httpdir}", '/systeem/systeem.php" method="post">', NL;
  echo "<input type=hidden name=systeemid id=systeemid value=$systeemid>";
  echo '<TABLE align="center" class="requesttable">';
  echo "<TR><TD>Naam</TD><TD> <input class=\"textval\" type=text id=naam value=\"$systeem->naam\" size=29></TD></TR>";
  echo '</TABLE>';
  echo '<input type="submit" name="submit" value="Annuleren" />';
  echo '<input type="button" name="submit" value="Opslaan" onClick="opslaan_systeemnaam()"/>';
  echo '</FORM>';
  echo '</div>', "\n"; 
}


function display_systeem()
{
  global $module;
  global $systeemid;
  global $httpdir;

  $systeem = get_systeem( $systeemid );
  echo '<div class="mainbox">', "\n";
  echo '<form name=systeemform ', "${httpdir}", '/systeem/systeem.php" method="post">', NL;
  echo "<input type=hidden id=systeemid value=$systeemid >";
      
  echo '<table align="center">';
  echo "<TR><TH colspan=8>Systeem: $systeem->naam</TH><TR>";
  display_empty_row(1);
  $dbserver = dbconnect();
  $query = "SELECT * from systeemitem where systeemid=$systeem->id"; 
  $result = dbquery($query);
  $aantalres = mysql_num_rows( $result );
  if( $aantalres == 0 )
  {
    mysql_free_result( $result);
    dbclose($dbserver);
    echo "<TR><TD>Geen artikelen geselecteerd</TD><TR>";
    echo "</table>";
  }
  else
  { 
    $i = 0;
    while ($systeemitem = mysql_fetch_object($result)) 
    {
      display_systeemitem($i, $systeemitem);
      $i++;
    }  
    mysql_free_result( $result);
    dbclose($dbserver);
    display_empty_row(1);
    echo "<TR>";
    display_empty_col(3);
    echo "<TD>Totaal<TD>";
    display_money($systeem->totaal);
    echo '</TR>';

    echo "</table>";
    echo "<BR>";
    echo '<input type="submit" name="submit" value="Sluiten" onclick="window.close()" />';
    echo '<input type="button" name="submit" value="Naam Wijzigen" onclick="wijzigen_systeemnaam()" />';
  }
  echo '</form>';
  echo '</div>', "\n"; 
}

?>

