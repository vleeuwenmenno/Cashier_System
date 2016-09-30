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
  $subpage = process_input_bon();

  display_bon_page($subpage);
}

function process_input_bon()
{
  global $module;
  global $bonid;
    
  extract($_GET);
  extract($_POST);
  
  // echo $submit;exit;

  if( !isset( $submit ) )
  {
      $subpage = SMAIN;
  }
  elseif( $submit == "Wijzigen" )
  {
      $subpage = SWIJZIGEN;
  }
  elseif( $submit == "Annuleren" )
  {
      remove_bon($bonid);
      $subpage = SSLUITEN;
  }
  elseif( $submit == "Wijzigen Annuleren" )
  {
      $subpage = SMAIN;
  }
  elseif( $submit == "Betalen" )
  {
      $bon = get_bon($bonid);
      
      if( $betaalwijze == "pin" )
      {
         $pinb = $bon->totaal;
         $rekeningb = 0;
         $kontantb = 0;
      }
      elseif( $betaalwijze == "kontant" )
      {
         $kontantb = $bon->totaal;
         $rekeningb = 0;
         $pinb = 0;
      }
      elseif( $betaalwijze == "rekening" )
      {
         $rekeningb = $bon->totaal;
         $kontantb = 0;
         $pinb = 0;
      }
      else
      {
         $rekeningb = 0;
         $pinb = get_moneyint($pin);
         $kontantb = get_moneyint($kontant);
      }
       
      update_bon($bonid, $naam, $betaalwijze, $kontantb, $pinb, $rekeningb);
      set_betaald_bon($bonid);
      print_bon();
      $subpage = SRESULTAAT;
  }
  elseif( $submit == "Terugnemen" )
  {
      terugnemen_bon($bonid);
      $subpage = SMAIN;	   
  }
  elseif( $submit == "Opslaan" )
  {
      update_bon_item();
      $subpage = SMAIN;	   
  }
  elseif( $submit == "Afdrukken" )
  {
      print_bon();
      $subpage = SRESULTAAT;	   
  }
  elseif( $submit == "Opslaan Bon" )
  {
      $bon = get_bon($bonid);
   
      if( $betaalwijze == "pin" )
      {
         $pinb = $bon->totaal;
         $rekeningb = 0;
         $kontantb = 0;
      }
      elseif( $betaalwijze == "kontant" )
      {
         $kontantb = $bon->totaal;
         $rekeningb = 0;
         $pinb = 0;
      }
      elseif( $betaalwijze == "rekening" )
      {
         $rekeningb = $bon->totaal;
         $kontantb = 0;
         $pinb = 0;
      }
      else
      {
         $rekeningb = 0;
         $pinb = get_moneyint($pin);
         $kontantb = get_moneyint($kontant);
      }
       
      update_bon($bonid, $naam, $betaalwijze, $kontantb, $pinb, $rekeningb);
      $subpage = SMAIN;
  }
  elseif( $submit == "Verwijderen" )
  {
      remove_bon_item($bonid, $itemid);
      $subpage = SMAIN;	   
  }
  elseif ( $submit == "Toevoegen" )
  {
      if( isset( $artikelid ) )
      { 
         add_bon_item( $bonid, $artikelid, $aantal, $transactie, $demo );
      }
      if ( isset( $systeemid ) )
      {
         add_bon_systeem( $bonid,$systeemid);
      }
      if( isset( $klantid ) )
      {
         add_bon_klant( $bonid, $klantid );
      }
      $subpage = SMAIN;
  }
  elseif( $submit == "Open" )
  {   
      if( $bonid == -1 )
      {    
         $bon = new_bon();
         $bonid=$bon->id;
      }
      //echo $bonid; echo $systeemid;exit;

      if( isset( $artikelid ) )
      { 
         add_bon_item( $bonid, $artikelid, $aantal, $transactie, $demo );
      }
      if ( isset( $systeemid ) )
      {
         add_bon_systeem( $bonid,$systeemid);
      }
      if( isset( $klantid ) )
      {
         add_bon_klant( $bonid, $klantid );
      }
      $subpage = SMAIN;

  } 
  return $subpage;

}

function display_bon_page($subpage)
{ 
  global $module;
  global $bonid;
  global $httpdir;

  if( $subpage == SSLUITEN )
  {
   
    display_sluiten();
  }
  else
  {
    $bon=get_bon($bonid);

    display_header("Bon Nr: $bonid - ".ucfirst($bon->status)); 
    echo '<body>';
    display_stylesheet();
    echo '<script type="text/javascript" src="'.$httpdir.'/php/jsfuncties.js"></script>';
    echo '<div class="screen">';
    display_bon_menu(MBON,$subpage);
    display_bon_main($subpage);
    echo '</div>';
    echo '</body>';
    echo '</html>'; 
  }
}

function display_bon_main($subpage)
{
  extract($_GET);
  extract($_POST);
  if( $subpage == SWIJZIGEN )
  {
     display_bon_item_wijzigen($bonid, $itemid);
  }
  elseif( $subpage ==  SRESULTAAT )
  {
     display_bon_result();
  }
  else
  {
     display_bon();
  }
}

function display_bon_item($i, $item)
{
   echo "<TR>";
   if( $item->transactie == 'verkoop' )
   {
     echo"<TD></TD>";
   }
   else
   {
     echo"<TD>$item->transactie</TD>";
   }
   
   echo "<input id=itemid$i type=hidden value=$item->id />";
   echo"<TD>$item->aantal x</TD>";
   echo "<TD>$item->categorie</TD>";
   echo "<TD>$item->merk</TD>";
   $displaytype = stripslashes($item->type);
   echo "<TD>",substr($displaytype,0,20),"</TD>";
   echo "<TD></TD>";
   display_money($item->totaal);
   echo "<TD><input class=resultbutton type=button value=\"Wijzigen\" onclick=\"wijzigen_bon_item($i)\"</TD>";
   echo "<TD><input class=resultbutton type=button value=\"Verwijderen\" onclick=\"verwijderen_bon_item($i)\"</TD>";
   echo "</TR>";
   if( $item->omschrijving != '')
   { 
      echo "<TR><TD><TD><TD colspan=4>$item->omschrijving</TD></TR>";
   }
}

function display_bon_item_wijzigen($bonid, $itemid)
{
  // get klant gegevens, if -1 nieuwe klanten  
  global $httpdir;

  $item = get_item($itemid);

  echo '<div class="mainbox">', "\n";
  echo '<form action="', "${httpdir}", '/bon/bon.php" method="post">', NL;
  echo '<input type="hidden" id="itemid" name="itemid" value='. $item->id . ' >';
  echo '<input type="hidden" id="bonid" name="bonid" value='. $bonid . ' >';
  echo '<TABLE align="center" class="requesttable">';
  echo "<TR><TH>Bon Item</TH></TR>";

  echo "<TR><TD>Transactie</TD><TD>$item->transactie<TD></TR>";
  echo "<TR><TD>Aantal</TD><TD>$item->aantal<TD></TR>";
  echo "<TR><TD>Categorie</TD><TD>$item->categorie<TD></TR>";
  echo "<TR><TD>Merk</TD><TD>$item->merk<TD></TR>";
  echo "<TR><TD>Omschrijving</TD><TD colspan=5><textarea type=text name=\"omschrijving\" rows=2 cols=31>$item->omschrijving</textarea></TD></TR>";
  echo "<TR><TD>Type</TD><TD>$item->type<TD></TR>";
  echo "<TR><TD>Prijs</TD>";
  display_money($item->prijs);
  echo "</TR>";
  echo "<TR><TD></TD><TD><TD></TR>";
  $totaalstr = get_moneystr( $item->totaal );
  echo "<TR><TD>Totaal</TD><TD>&#8364</TD><TD><input align=right class=\"numval\" type=text name=totaal value=\"$totaalstr\"></TD></TR>";
  echo "</TR>";

  echo '</TABLE>';
  echo '<input class=resultbutton type="submit" name="submit" value="Wijzigen Annuleren" />';
  echo '<input class=resultbutton type="submit" name="submit" value="Opslaan" />';
  echo '</FORM>';
  echo '</div>', "\n"; 
}

function display_bon()
{
  global $module;
  global $bonid;
  global $httpdir;

  $bon = get_bon( $bonid );
  echo '<div class="mainbox">', "\n";

  echo '<form name=bonform class=bonform ', "${httpdir}", '/bon/bon.php" method="post">', NL;
  echo "<input type=hidden id=bonid name=bonid value=$bonid >";
  echo "Omschrijving: <input type=text id=naam name=naam value=\"$bon->naam\" >";
  echo '<BR>';
  if( $bon->klantid != -1 )
  { 
    $klant = get_klant( $bon->klantid );
    echo '<input type="hidden" id="klantid" value='. $klant->id . ' >';
    echo '<TABLE align="center">';
    echo "<TR><TH>Klantgegevens</TH></TR>";
    if( $klant->bedrijfsnaam != "" )
    {
      echo "<TR><TD>Bedrijfsnaam</TD><TD>$klant->bedrijfsnaam</TD></TR>";
      if( $klant->achternaam != "" )
      {
        echo "<TR><TD>Contactpersoon</TD><TD>$klant->achternaam $klant->voorletters  $klant->tussenvoegsel<TD></TR>";
      }
    }
    else
    {
       echo "<TR><TD>Naam</TD><TD>$klant->achternaam $klant->voorletters  $klant->tussenvoegsel<TD></TR>";
    
    }   
    echo "<TR><TD>Adres</TD><TD>$klant->straat $klant->huisnr</TD></TR>";
    echo "<TR><TD>Postcode</TD><TD>$klant->postcode</TD></TR>";
    echo "<TR><TD>Woonplaats</TD><TD>$klant->woonplaats</TD></TR>";
    echo '</TABLE>';
    echo '<BR>';
  }
  echo '<table class=resulttable align="center">';
  $dbserver = dbconnect();
  $query = "SELECT * from item where bonid=$bon->id"; 
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
    while ($item = mysql_fetch_object($result)) 
    {
      display_bon_item($i, $item);
      $i++;
    }  
    mysql_free_result( $result);
    dbclose($dbserver);
    display_empty_row(2);
    
    $exbtw = floor($bon->totaal/BTW + 0.5);
    $btw = $bon->totaal - $exbtw;

    echo "<TR>";
    display_empty_col(4);
    echo "<TD>Ex. BTW<TD>";
    display_money($exbtw);
    echo '</TR>';
  
    echo "<TR>";
    display_empty_col(4);
    echo "<TD>BTW 19%<TD>";
    display_money($btw);
    echo '</TR>';

    echo "<TR>";
    display_empty_col(4);
    echo "<TD>Totaal<TD>";
    display_money($bon->totaal);
    echo "<TD>";
    display_select_betaalwijze($bon->betaalwijze);
    echo "</TD>";
    echo '</TR>';

    if($bon->betaalwijze != 'pin en kontant')
    {
      echo '<TR style="visibility:hidden" id="pinrow" >';
      display_empty_col(5);
      echo "<TD>Pin<TD>";
      echo "<TD>&#8364</TD><TD>";
      echo '<input align=right class="numval" type="text" name="pin" id=pin value="0.00">';
      echo '</TD></TR>';
      echo '<TR style="visibility:hidden" id="kontantrow">';
      display_empty_col(5);
      echo "<TD>Kontant<TD>";
      echo "<TD>&#8364</TD><TD>";
      echo '<input align=right class="numval" type="text" name="kontant" id=kontant value="0.00">';
      echo '</TD></TR>';
    }
    else
    {
      $pinstr = get_moneystr($bon->pin);
      $kontantstr = get_moneystr($bon->kontant);
      echo '<TR id="kontantrow" >';
      display_empty_col(5);
      echo "<TD>Pin<TD>";
      echo "<TD>&#8364</TD><TD>";
      echo "<input align=right class=numval type=text name=pin id=pin value=\"$pinstr\">";
      echo '</TD></TR>';
      echo '<TR id="kontantrow">';
      display_empty_col(5);
      echo "<TD>Kontant<TD>";
      echo "<TD>&#8364</TD><TD>";
      echo "<input align=right class=numval type=text name=kontant id=kontant value=\"$kontantstr\">";
      echo '</TD></TR>';
    }
  }
  echo "</table>";
  echo '<input class=resultbutton type="submit" name="submit" value="Annuleren" />';
  echo '<input class=resultbutton type="submit" name="submit" value="Sluiten" onclick="window.close()" />';
  echo '<input class=resultbutton type="submit" name="submit" value="Opslaan Bon"/>';
  if( $module->moduletype == 'Kassa' )
  {
    $kassastatus = new KassaStatus();
    $kassastatus->update();
    if( $kassastatus->status == GEOPEND)
    {
      if( $aantalres != 0 ) { echo '<input class=resultbutton type="submit" name="submit" value="Betalen" />';}
    }
    /*else
    {
      echo '<input class=resultbutton type="submit" name="submit" value="Afdrukken"/>';
    }*/
  }
  
  echo '</form>';
  echo '</div>', "\n"; 
}

function display_bon_result()
{
  global $module;
  global $bonid;
  global $rootdir;
  global $rootdirwin;

  $fname = get_print_filename('bon');
  nieuwe_print_bon($bonid, $fname, 'screen');
  echo '<div class="mainbox">', "\n";
  echo "<input type=hidden name=bonid id=bonid value=$bonid >";
  echo '<PRE class=printbon>'; 
  include($fname);
  echo '</PRE>';
  echo '<input type="button" class=resultbutton name="submit" value="Sluiten" onclick="window.close()" />';
  echo '<input type="button" class=resultbutton name="submit" value="Afdrukken" onclick="afdrukken_bon()"/>';
  /* echo '<input type="button" class=resultbutton name="submit" value="Terugnemen" onclick="terugnemen_bon()" />'; */
  echo '</div>', "\n"; 
  delete_print_file($fname);
}

function print_bon()
{
  global $bonid;
  global $rootdir;
  
  $fname = get_print_filename('bon');
  nieuwe_print_bon($bonid, $fname, 'printer');
  copy_to_spool($fname, 1);
  copy_to_backup($fname);
  delete_print_file($fname);
	
}

?>

