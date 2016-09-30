<?php
include("include.php");
session_start();

error_reporting(E_WARNING);
if( !isset( $_SESSION['usernaam'] ) )
{
  header("Location: login.php");
  exit();
}
else
{  
  $module = new Module();
  $module->set_module( $_SESSION['usernaam'],'Kassa');

  $kassastatus = new KassaStatus();
  $kassastatus->update();

  if( $kassastatus->status == GESLOTEN )
  { 
    $subpage = process_input_gesloten();
    display_gesloten_page($subpage);
  }
  else
  {
    $subpage = process_input_geopend();
    display_geopend_page($subpage);
  }
}

// SLUITEN
function display_gesloten_page($subpage)
{ 
  global $module;
  global $httpdir;

  display_header(); 
  echo '<body>';
  display_stylesheet();
  echo '<script src="'.$httpdir.'/php/jsfuncties.js"></script>';
  echo '<div class="screen">';
  display_kassa_menu(MKASSA, $subpage);
  display_gesloten_main($subpage);
  echo '</div>';
  echo '</body>';
  echo '</html>';
}

function process_input_gesloten()
{
  global $kassastatus;
  global $module;
  global $kasinnieuw;
  global $commentaar;
  global $submit;
  global $controle;

  if( !isset( $submit ) )
  {
        $subpage = SMAIN;
  }
  if( $submit == "Openen")
  {
        $subpage = SOPENEN;
  }

  elseif( $submit == "Invoeren" )
  {
      $_SESSION['kasinnieuwtmp'] = get_moneyint($kasinnieuw);
      if( $controle == "on" )
      {
         $_SESSION['controletmp'] = 1;
      }
      else
      {
         $_SESSION['controletmp'] = 0;
      }
      $_SESSION['commentaartmp'] = $commentaar;
      $subpage = SBEVESTIGEN;
  }
  elseif( $submit == "Bevestigen" )
  {
    // Maak nieuwe entry in kassalog    
    $kasinnieuw = $_SESSION['kasinnieuwtmp'];
    $controle = $_SESSION['controletmp'];
    $commentaar = $_SESSION['commentaartmp'];
    open_kassa($module->userid, $module->moduleid, $kasinnieuw, $controle, $commentaar );
    $kassastatus->update();
    $subpage = SRESULTAAT;
    
  }
  elseif( $submit == "Annuleren" )
  {
      $subpage = SMAIN;
  }
  elseif( $submit == "Overzicht" )
  {
      $subpage = SOVERZICHT;
  }
  elseif( $submit == "Afdrukken" )
  {
      overzicht_afdrukken();
      $subpage = SOVERZICHT;
  }
 
  return $subpage;
}


function display_gesloten_main($subpage)
{
  switch($subpage)
  {
    case SMAIN:
      display_gesloten();
    break;
    case SOPENEN:
      display_form_openen_invoeren();
    break;
    case SBEVESTIGEN:
       display_form_openen_bevestigen();
    break;
    case SOVERZICHT:
       display_overzicht();
    break;
    case SRESULTAAT:
      display_geopend();
    break; 
  }
}

function display_overzicht()
{	
  global $kassastatus;
  global $module;
  global $rootdir;
  global $httpdir;

  $fname = get_print_filename('sluiting');
  nieuwe_print_overzicht($kassastatus, "$fname");
  echo '<div class="mainbox">', "\n";
  echo '<form action="', "${httpdir}", '/kassa/kassa.php" method="post">', NL;

//  echo "<input type=hidden name=bonid id=bonid value=$bonid >";
  echo '<input type="submit" name="submit" value="Afdrukken"/>';
  echo '<PRE class=printbon>'; 
  include("$fname");
  echo '</PRE>';
  //echo '<input type="button" name="submit" value="Sluiten" onclick="window.close()" />';
  echo '</form>';
  echo '</div>', "\n"; 
  
  delete_print_file("$fname");
}

function display_opening()
{	
  global $kassastatus;
  global $module;
  global $rootdir;
  global $httpdir;

  $fname = get_print_filename('opening');
  nieuwe_print_opening($kassastatus, "$fname");
  echo '<div class="mainbox">', "\n";
  echo '<form action="', "${httpdir}", '/kassa/kassa.php" method="post">', NL;

//  echo "<input type=hidden name=bonid id=bonid value=$bonid >";
 // echo '<input type="submit" name="submit" value="Afdrukken"/>';
  echo '<PRE class=printbon>'; 
  include("$fname");
  echo '</PRE>';
  //echo '<input type="button" name="submit" value="Sluiten" onclick="window.close()" />';
  echo '</form>';
  echo '</div>', "\n"; 
  
  delete_print_file("$fname");
}

function overzicht_afdrukken()
{	
  global $kassastatus;
  global $module;
  global $rootdir;

  $fname = get_print_filename('sluiting');
  nieuwe_print_overzicht($kassastatus, $fname);
  copy_to_spool($fname,1);
  copy_to_backup($fname);
  delete_print_file("$fname");
}

function opening_afdrukken()
{	
  global $kassastatus;
  global $module;
  global $rootdir;

  $fname = get_print_filename('opening');
  nieuwe_print_opening($kassastatus, $fname);
  copy_to_spool($fname,1);
  copy_to_backup($fname,1);
  delete_print_file("$fname");
}



function display_form_sluiten_bevestigen()
{
  global $kassastatus;
  global $module;
  global $httpdir;

  global $controle;
  global $commentaar;

  $kasgeld = $_SESSION['kasgeldtmp'];
  $kasgeldstr = get_moneystr( $kasgeld );
  $afromen = $_SESSION['afromentmp'];
  $afromenstr = get_moneystr($afromen);

  $kasuit = $kasgeld - $afromen;
  $_SESSION['kasuittmp'] = $kasuit;
  $kasuitstr= get_moneystr( $kasuit );
  
  $verschiltotaal = $_SESSION['verschiltotaaltmp'];
  $verschiltotaalstr = get_moneystr( $verschiltotaal );

  echo '<div class="mainbox">', "\n";
  echo '<form action="', "${httpdir}", '/kassa/kassa.php" method="post">', NL;
  echo '<TABLE align="center" class="requesttable">';
  echo "<TR><TH>Kassa Sluiten</TH></TR>";
  echo "<TR><TD>User</TD><TD>$module->usernaam</TD></TR>";
  echo "<TR><TD>Kassa</TD><TD>$module->modulenaam</TD></TR>";
  echo "<TR><TD></TD></TR>";
  echo "<TR><TD></TD></TR>";      
  echo "<TR><TD>Totaal kasgeld</TD><TD>&#8364 ${kasgeldstr}</TD></TR>";
  echo "<TR><TD>Afromen</TD><TD>&#8364 ${afromenstr} </TD></TR>";
  echo "<TR><TD>Kas uit</TD><TD>&#8364 ${kasuitstr}</TD></TR>";
  echo "<TR><TD></TD></TR>";
  echo "<TR><TD></TD></TR>";      
  echo "<TR><TD>Kasverschil</TD><TD>&#8364 ${verschiltotaalstr}</TD></TR>";
  echo "<TR><TD>Controle</TD><TD>${_SESSION['controletmp']} </TD></TR>";     
  echo "<TR><TD>Commentaar</TD><TD>${commentaar}</TD></TR>";
  echo '</TABLE>';
  echo '<input type="submit" name="submit" value="Annuleren" />';
  echo '<input type="submit" name="submit" value="Bevestigen" />';
  echo '</FORM>';
  echo '</div>', "\n"; 
}


function display_gesloten()
{
  global $module;
  global $kassastatus;
  global $httpdir;

  $kasuitstr = get_moneystr( $kassastatus->kasuit);

  echo '<div class="mainbox">', "\n";
  echo '<form action="', "${httpdir}", '/kassa/kassa.php" method="post">', NL;
  echo '<TABLE align="center" class="requesttable">';
  echo "<TR><TH>Kassa Gesloten</TH></TR>";
  echo "<TR><TD>User</TD><TD>$kassastatus->usernaam</TD></TR>";
  echo "<TR><TD>Kassa</TD><TD>$module->modulenaam</TD></TR>";
  echo "<TR><TD>Datum</TD><TD>$kassastatus->datum</TD></TR>";
  echo "<TR><TD>Tijd</TD><TD>$kassastatus->tijd</TD></TR>";
  echo "<TR><TD></TD></TR>";
  echo "<TR><TD></TD></TR>";      
  echo "<TR><TD>Kasuit</TD><TD>&#8364 ${kasuitstr}</TD></TR>";
  echo "<TR><TD>Controle</TD><TD>$kassastatus->controle</TD></TR>";     
  echo "<TR><TD>Commentaar</TD><TD>$kassastatus->commentaar</TD></TR>";     
  echo "<TR><TD></TD></TR>";
  echo "<TR><TD></TD></TR>";      
  echo '</TABLE>';
  echo '<BR>';
  echo '<input type="submit" name="submit" value="Openen" />';
  echo '<input type="submit" name="submit" value="Overzicht" />';
  echo '</FORM>';
  echo '</div>', "\n"; 
 
}

// het gedeelte voor een geopende kassa
function display_geopend_main($subpage)
{
  switch($subpage)
  {
    case SMAIN:
      display_geopend();
    break;
    case SAFROMEN:
      display_form_sluiten_afromen();
    break;
    case SBEVESTIGEN:
      display_form_sluiten_bevestigen();
    break; 
    case SRESULTAAT:
      display_gesloten();
    break; 
    case SSLUITEN:
      display_form_sluiten_invoeren();
    break;
    case SOPENING:
      display_opening();
    break; 
  }
}

function process_input_geopend()
{
  global $kassastatus;
  global $module;
  global $submit;
  global $kasgeld;
  global $pinbon;
  global $oprekening;
  global $afromen;
  global $controle;
  global $commentaar;


  if( !isset( $submit ) )
  {
    $subpage = SMAIN;
  }
  elseif( $submit == "Invoeren" )
  {
      $_SESSION['kasgeldtmp'] = get_moneyint($kasgeld);
      $_SESSION['pinbontmp'] = get_moneyint($pinbon);
      $_SESSION['oprekeningtmp'] = get_moneyint($oprekening);
      $subpage = SAFROMEN;
  }
  elseif( $submit == "Afromen" )
  {
      if( $controle == "on" )
      {
         $_SESSION['controletmp'] = 1;
      }
      else
      {
         $_SESSION['controletmp'] = 0;
      }

      $_SESSION['afromentmp'] = get_moneyint($afromen);

      if( isset( $commentaarin ) )
      {
        $_SESSION['commentaartmp'] = $commentaar;
      }
      else
      {
        $_SESSION['commentaartmp'] = "";
      }
      $subpage = SBEVESTIGEN;
  }

  elseif( $submit == "Bevestigen" )
  {
    // Maak nieuwe entry in kassalog    
    $kasuit = $_SESSION['kasuittmp'];
    $kasgeld = $_SESSION['kasgeldtmp'];
    $pinbon = $_SESSION['pinbontmp'];
    $oprekening = $_SESSION['oprekeningtmp'];
    $afromen = $_SESSION['afromentmp'];
    $controle = $_SESSION['controletmp'];
    $kasverschil = $_SESSION['verschiltotaaltmp'];
    sluit_kassa($module->userid, $module->moduleid, $kassastatus->kasin, $kasuit, $kasgeld,
                $afromen, $pinbon, $oprekening, $kasverschil, $controle, $commentaar);
    $kassastatus->update();
    $subpage = SRESULTAAT;
    
  }
  elseif( $submit == "Annuleren" )
  {
      $subpage = SMAIN;
  }
  elseif( $submit == "Sluiten" )
  {
      $subpage = SSLUITEN;
  }
  elseif( $submit == "Afdrukken" )
  {
      opening_afdrukken();
      $subpage = SOPENING;
  }

  return $subpage;  
}

function display_form_sluiten_invoeren()
{
  global $kassastatus;
  global $httpdir;

  $omzetpin = get_omzet($kassastatus->datum, $kassastatus->tijd, "", "", PIN);
  $_SESSION['omzetpintmp']=$omzetpin;
  $omzetkontant = get_omzet($kassastatus->datum, $kassastatus->tijd, "", "", KONTANT);	
  $_SESSION['omzetkontanttmp']=$omzetkontant;
  $omzetrekening = get_omzet($kassastatus->datum, $kassastatus->tijd, "", "", REKENING);	
  $_SESSION['omzetrekeningtmp']=$omzetrekening;
  $omzettotaal = get_omzet($kassastatus->datum, $kassastatus->tijd, "", "", TOTAAL);	
  $_SESSION['omzettotaaltmp']=$omzettotaal;
  // !! nieuwe kasin is oude kasuit	 
  $kasin = $kassastatus->kasin;
  $_SESSION['$kasintmp']=$kasin;

  $omzetpinstr = get_moneystr( $omzetpin );
  $omzetkontantstr = get_moneystr( $omzetkontant );
  $omzetrekeningstr = get_moneystr( $omzetrekening );
  $omzettotaalstr = get_moneystr( $omzettotaal );
  $kasinstr = get_moneystr( $kasin );
  
      
  echo '<div class="mainbox">', "\n";
  echo '<form action="', "${httpdir}", '/kassa/kassa.php" method="post">', NL;
  echo '<TABLE align="center" class="requesttable">';
  echo "<TR><TH>Invoeren</TH></TR>";
  echo "<TR><TD>Openingsdatum:</TD><TD>$kassastatus->datum</TD></TR>";
  echo "<TR><TD>Openingtijd:</TD><TD>$kassastatus->tijd</TD></TR>";
  echo "<TR><TD>Geopend door:</TD><TD>$kassastatus->usernaam</TD></TR>";
  echo "<TR><TD></TD></TR>";
  echo "<TR><TD></TD></TR>";      
  echo "<TR><TD>Omzet</TD><TD>&#8364 ${omzettotaalstr}</TD></TR>";
  echo "<TR><TD>Kontant</TD><TD>&#8364 ${omzetkontantstr}</TD></TR>";
  echo "<TR><TD>Pin</TD><TD>&#8364 ${omzetpinstr}</TD></TR>";
  echo "<TR><TD>Op rekening</TD><TD>&#8364 ${omzetrekeningstr}</TD></TR>";
  echo "<TR><TD>Kas in</TD><TD>&#8364 ${kasinstr} </TD></TR>";
  echo "<TR><TD></TD></TR>";
  echo "<TR><TD></TD></TR>";      
  echo '<TR><TD>Totaal kasgeld</TD><TD>&#8364 <input type="text" name="kasgeld"></TD></TR>';
  echo '<TR><TD>Pinbon</TD><TD>&#8364 <input type="text" name="pinbon"></TD></TR>';     
  echo '<TR><TD>Op rekening</TD><TD>&#8364 <input type="text" name="oprekening"></TD></TR>';     
  echo "<TR><TD></TD></TR>";
  echo "<TR><TD></TD></TR>";      
  echo '</TABLE>';
  echo '<input type="submit" name="submit" value="Invoeren" />';
  echo '</FORM>';
  echo '</div>', "\n"; 
 
}

function display_form_sluiten_afromen()
{
  global $kassastatus;
  global $httpdir;

  $pinbon = $_SESSION['pinbontmp'];
  $oprekening = $_SESSION['oprekeningtmp'];
  $kasgeld = $_SESSION['kasgeldtmp'];
  $omzetpin = $_SESSION['omzetpintmp'];
  $omzetrekening = $_SESSION['omzetrekeningtmp'];
  $omzetkontant = $_SESSION['omzetkontanttmp'];
  $omzettotaal = $_SESSION['omzettotaaltmp'];
  $kasin = $kassastatus->kasin;

  $verschilpin = $pinbon - $omzetpin;
  $verschilkontant = $kasgeld - $kasin - $omzetkontant;
  $verschilrekening = $oprekening - $omzetrekening;
  $totaal = $pinbon + $kasgeld - $kasin + $oprekening;	
  $verschiltotaal = $pinbon + $kasgeld - $kasin + $oprekening - $omzettotaal;	
  $kontant = $kasgeld - $kasin;

  $pinbonstr = get_moneystr( $pinbon );
  $oprekeningstr = get_moneystr( $oprekening );
  $kasgeldstr = get_moneystr( $kasgeld );
  $omzetpinstr = get_moneystr( $omzetpin );
  $omzetrekeningstr = get_moneystr( $omzetrekening );
  $omzetkontantstr = get_moneystr( $omzetkontant );
  $omzettotaalstr = get_moneystr( $omzettotaal );
  $kasinstr = get_moneystr( $kasin );
  $kontantstr = get_moneystr( $kontant );
  $verschilpinstr = get_moneystr( $verschilpin );
  $verschilrekeningstr = get_moneystr( $verschilrekening );
  $verschiltotaalstr = get_moneystr( $verschiltotaal );
  $totaalstr = get_moneystr( $totaal );
  $_SESSION['verschiltotaaltmp'] = $verschiltotaal;
  $verschilkontantstr = get_moneystr( $verschilkontant );

  echo '<div class="mainbox">', "\n";
  echo '<form action="', "${httpdir}", '/kassa/kassa.php" method="post">', NL;
  echo '<TABLE align="center" class="requesttable">';
  echo "<TR><TH>Overzicht Kassa</TH></TR>";
  echo "<TR><TD></TD></TR>";
  echo "<TR><TD></TD></TR>";      
  echo "<TR><TD>Totale inkomsten</TD><TD>&#8364 ${totaalstr}</TD></TR>";
  echo "<TR><TD>Totale omzet</TD><TD>&#8364 ${omzettotaalstr}</TD></TR>";
  echo "<TR><TD>Kasverschil</TD><TD>&#8364 ${verschiltotaalstr} </TD></TR>";
  echo "<TR><TD></TD></TR>";
  echo "<TR><TD></TD></TR>";      
  echo "<TR><TD></TD></TR>";
  echo "<TR><TD></TD></TR>";      
  echo "<TR><TD>Kasgeld - Kas in</TD><TD>&#8364 ${kontantstr}</TD></TR>";
  echo "<TR><TD>Totale omzet kontant</TD><TD>&#8364 ${omzetkontantstr}</TD></TR>";
  echo "<TR><TD>Kasverschil kontant</TD><TD>&#8364 ${verschilkontantstr} </TD></TR>";
  echo "<TR><TD></TD></TR>";
  echo "<TR><TD></TD></TR>";      
  echo "<TR><TD>Pinbon</TD><TD>&#8364 ${pinbonstr}</TD></TR>";
  echo "<TR><TD>Totale omzet pin</TD><TD>&#8364 ${omzetpinstr}</TD></TR>";
  echo "<TR><TD>Kasverschil pin</TD><TD>&#8364 ${verschilpinstr} </TD></TR>";
  echo "<TR><TD></TD></TR>";
  echo "<TR><TD></TD></TR>";      
  echo "<TR><TD>Op rekening</TD><TD>&#8364 ${oprekeningstr}</TD></TR>";
  echo "<TR><TD>Totale omzet rekening</TD><TD>&#8364 ${omzetrekeningstr}</TD></TR>";
  echo "<TR><TD>Kasverschil rekening</TD><TD>&#8364 ${verschilrekeningstr} </TD></TR>";
  echo "<TR><TD></TD></TR>";
  echo "<TR><TD></TD></TR>";      
  echo "<TR><TD>Totaal kasgeld</TD><TD>&#8364 ${kasgeldstr} </TD></TR>";
  echo '<TR><TD>Afromen</TD><TD>&#8364 <input type="text" name="afromen"></TD></TR>';
  echo "<TR><TD></TD></TR>";
  echo "<TR><TD></TD></TR>";      
  echo '<TR><TD>controle</TD><TD><input type="checkbox" name="controle"></TD></TR>';     
  echo '<TR><TD>Commentaar</TD><TD><textarea name="commentaar" rows="2" cols="40"></textarea></TD></TR>';     
  echo "<TR><TD></TD></TR>";
  echo "<TR><TD></TD></TR>";      
  echo '</TABLE>';
  echo '<input type="submit" name="submit" value="Annuleren" />';
  echo '<input type="submit" name="submit" value="Afromen" />';
  echo '</FORM>';
  echo '</div>', "\n"; 
}

// OPENEN GEDEELTE
function display_geopend_page($subpage)
{ 
  global $module;
  global $httpdir;

  display_header(); 
  echo '<body>';
  display_stylesheet();
  echo '<script src="'.$httpdir.'/php/jsfuncties.js"></script>';
  echo '<div class="screen">';
  display_kassa_menu(MKASSA, $subpage);
  display_geopend_main($subpage);
  echo '</div>';
  echo '</body>';
  echo '</html>';
}


function display_form_openen_invoeren()
{
  global $kassastatus;
  global $httpdir;

  $kasuitstr = get_moneystr($kassastatus->kasuit);	 
         
  echo '<div class="mainbox">', "\n";
  echo '<form action="', "${httpdir}", '/kassa/kassa.php" method="post">', NL;
  echo '<TABLE align="center" class="requesttable">';
  echo "<TR><TH>Vorige sluiting</TH></TR>";
  echo "<TR><TD>Datum</TD><TD>$kassastatus->datum</TD></TR>";
  echo "<TR><TD>Tijd</TD><TD>$kassastatus->tijd</TD></TR>";
  echo "<TR><TD>User</TD><TD>$kassastatus->usernaam</TD></TR>";
  echo "<TR><TD>Kas uit</TD><TD>&#8364 ${kasuitstr}</TD></TR>";
  echo "<TR><TD></TD></TR>";
  echo "<TR><TD></TD></TR>";      
  echo "<TR><TD>Nieuwe kas in</TD><TD>&#8364 <input type=\"text\" name=\"kasinnieuw\" value=\"${kasuitstr}\"></TD></TR>";
  echo '<TR><TD>Controle</TD><TD><input type="checkbox" name="controle"></TD></TR>';     
  echo '<TR><TD>Commentaar</TD><TD><textarea name="commentaar" rows="2" cols="40"></textarea></TD></TR>'; 
  echo '</TABLE>';
  echo '<input type="submit" name="submit" value="Invoeren" />';
  echo '</FORM>';
  echo '</div>', "\n"; 
 
}

function display_form_openen_bevestigen()
{
  global $module;
  global $commentaar;
  global $httpdir;

  $controle = $_SESSION['controletmp'];
  $kasinnieuw = $_SESSION['kasinnieuwtmp'];
  $kasinnieuwstr = get_moneystr($kasinnieuw);
   

  echo '<div class="mainbox">', "\n";
  echo '<form action="', "${httpdir}", '/kassa/kassa.php" method="post">', NL;
  echo '<TABLE align="center" class="requesttable">';
  echo "<TR><TH>Kassa Openen</TH></TR>";
  echo "<TR><TD>Kassa</TD><TD>$module->modulenaam </TD></TR>";
  echo "<TR><TD>User</TD><TD>$module->usernaam </TD></TR>";
  echo "<TR><TD>Kasin</TD><TD>&#8364 ${kasinnieuwstr}</TD></TR>";
  echo "<TR><TD>Controle</TD><TD>${controle}</TD></TR>";
  echo "<TR><TD>Commentaar</TD><TD>$commentaar</TD></TR>";
  echo '</TABLE>';
  echo '<BR>';
  echo '<input type="submit" name="submit" value="Annuleren" />';
  echo '<input type="submit" name="submit" value="Bevestigen" />';
  echo '</FORM>';
  echo '</div>', "\n"; 
 
}

function display_geopend()
{
  global $module;
  global $kassastatus;
  global $httpdir;

  $kassastatus->update();	
  $kasinstr = get_moneystr($kassastatus->kasin);	
       
  echo '<div class="mainbox">', "\n";
  echo '<form action="', "${httpdir}", '/kassa/kassa.php" method="post">', NL;
  echo '<TABLE align="center" class="requesttable">';
  echo "<TR><TH>Kassa Geopend</TH></TR>";
  echo "<TR><TD>Kassa</TD><TD>$module->modulenaam </TD></TR>";
  echo "<TR><TD>User</TD><TD>$module->usernaam </TD></TR>";
  echo "<TR><TD>Datum</TD><TD> $kassastatus->datum </TD></TR>";
  echo "<TR><TD>Tijd</TD><TD> $kassastatus->tijd </TD></TR>";
  echo "<TR><TD>Kasin</TD><TD>&#8364 ${kasinstr}</TD></TR>";
  echo '</TABLE>';
  echo '<BR>';
  echo '<input type="submit" name="submit" value="Sluiten" />';
  echo '<input type="submit" name="submit" value="Afdrukken" />';
  echo '</form>';
  echo '</div>', "\n"; 
 
}


?>