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

  $subpage = process_input_artikelen();
  display_artikelen_page($subpage);
}

function display_artikelen_page($subpage)
{ 
  global $module;
  global $httpdir;

  //  echo $subpage;exit;
  display_header(); 
  echo '<body>';
  display_stylesheet();
  echo '<script src="'.$httpdir.'/php/jsfuncties.js"></script>';
  echo '<div class="screen">';
  display_beheer_menu(MARTIKELEN, $subpage);
  display_artikelen_main(MARTIKELEN, $subpage);
  echo '</div>';
  echo '</body>';
  echo '</html>';
}

function display_artikelen_main($menuoption, $subpage)
{
  global $artikelid;
  
  if( $subpage == SWIJZIGEN )
  {  
     display_artikelen_wijzigen($artikelid);
  }
  else
  {
    display_artikelen_query();
    display_artikelen_result();
  }

}

function display_artikelen_query()
{
  global $httpdir;
  global $type;
  global $merk;
  global $eol;
  global $categorie;
  global $fulltext;

  $displaytype = htmlentities(stripslashes($type));

  echo '<div class="querybox">', "\n";
  //echo '<TABLE align="center" class="requesttable">';
  echo '<div class=queryinput>';
  echo '<TABLE class="requesttable">';  
  echo '<form action="', "${httpdir}", '/beheer/artikelen.php" method="post" name="queryform" ID="queryform">', NL;
  echo "<TR><TD>Type</TD><TD><input class=text type=\"text\" name=\"type\" id=\"type\" value=\"$displaytype\" onkeypress=\"return submitViaEnter(event, 1)\"></TD></TR>";
  echo "<TR><TD>Merk</TD><TD>";
  display_select_merk($merk, 1);
  echo "</TD></TR>";
  echo "<TR><TD>Categorie</TD><TD>";
  display_select_categorie($categorie, 1);
  echo "</TD></TR>";
  echo '<TR><TD>EOL</TD><TD>';
  display_select_toggle_query("eol",$eol, 1);
  echo '</TD></TR>';
  echo '</TABLE>';
  echo '</div>';
  echo '<div class="querybutton">';
  echo '<input class=textval type="submit" name="submit" value="Zoeken"/>';
  echo '<input class=textval type="submit" name="submit" value="Reset" />';
  echo '<input class=textval type="submit" name="submit" value="Nieuw" />';
  echo '</div>';
  echo '</FORM>';
  echo '</div>', "\n";  

}

function display_artikelen_result()
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

function process_input_artikelen()
{
  global $kassastatus;
  global $module;
  global $submit;
  global $query, $type, $merk, $eol, $categorie;
  global $artikelid;

  if( !isset( $submit ) )
  { 
     reset_form_variables();
     $subpage = SMAIN;
  }
  elseif( $submit == "Zoeken" )
  {
     $query = make_artikel_query();

     $_SESSION['lastquery'] = $query;
     $_SESSION['qtype'] = $type;
     $_SESSION['qmerk'] = $merk;
     $_SESSION['qcategorie'] = $categorie;
     $_SESSION['qeol'] = $eol;
     
     $subpage = SMAIN;
  }
  elseif( $submit == "Reset" )
  {
      reset_form_variables();
      $subpage = SMAIN;
  }
  elseif( $submit == "Nieuw" )
  {
      $artikelid = -1;
      $subpage = SWIJZIGEN;
  }
  elseif( $submit == "Wijzigen" )
  {
     $subpage = SWIJZIGEN;
  }
  elseif( $submit == "Voorraad" )
  {
      update_voorraad();
      //reset_form_variables();
      //echo $_SESSION['lastquery'];exit;
      $type = $_SESSION['qtype'];
      $merk = $_SESSION['qmerk'];
      $eol = $_SESSION['qeol'];
      $categorie = $_SESSION['qcategorie'];
      $query = $_SESSION['lastquery'];
      $subpage = SMAIN;
  }
  elseif( $submit == "Opslaan")
  {
      update_artikel();
      $query = $_SESSION['lastquery'];
      $type = $_SESSION['qtype'];
      $merk = $_SESSION['qmerk'];
      $eol = $_SESSION['qeol'];
      $categorie = $_SESSION['qcategorie'];
      $subpage = SMAIN;
  }
  elseif( $submit == "Annuleren")
  {
      $query = $_SESSION['lastquery'];
      $type = $_SESSION['qtype'];
      $merk = $_SESSION['qmerk'];
      $eol = $_SESSION['qeol'];
      $categorie = $_SESSION['qcategorie'];
      $subpage = SMAIN;
  }

  return $subpage;  
}

function reset_form_variables()
{
  global $type, $categorie, $eol, $merk, $fulltext;

  $type="";
  $categorie = "";
  $eol="";
  $merk = "";
  $fulltext = "";
}

function display_result_header()
{
  echo "<TR><TH>Aantal</TH><TH>Categorie</TH><TH>Merk</TH><TH>Type</TH><TH></TH><TH>Inkoop</TH><TH></TH><TH>Prijs</TH>";
  echo "<TH>Voorraad</TH><TH>Demo</TH><TH>Rma</TH><TH>Retour</TH><TH>EOL</TH></TR>";
}

function display_result_row($row, $i)
{
    echo "<TR>";
    echo "<input type=hidden id=artikelid$i value=$row->id>";
    echo "<TD><input class=numval type=text size=2 value=1 id=aantal$i ></TD>";
    
    display_textval($row->categorie);
    display_textval($row->merk);
    display_textval(substr(stripslashes($row->type),0,30));

    display_moneyval($row->inkoop);
    display_moneyval($row->prijs);
    display_numval($row->voorraad);
    display_numval($row->demo);
    display_numval($row->rma);
    display_numval($row->retour);
    
    display_numval($row->eol);
    echo "<TD><input class=resultbutton type=button value=\"W\" onClick=\"wijzigen_artikel($i)\"</TD>";
    echo "<TD ><input class=resultbutton type=button value=\"V+\" onClick=\"voorraad_artikel($i,'voorraad')\"</TD>";
    echo "<TD><input class=resultbutton type=button value=\"Uit RMA\" onClick=\"voorraad_artikel($i,'rma')\"</TD>";
    echo "<TD><input class=resultbutton type=button value=\"V->D\" onClick=\"voorraad_artikel($i,'voorraaddemo')\"</TD>";
    echo "<TD><input class=resultbutton type=button value=\"D->V\" onClick=\"voorraad_artikel($i,'demovoorraad')\"</TD>";
    echo "</TR>";
}

function display_artikelen_wijzigen($artikelid)
{
  // get artikel gegevens, if -1 nieuwe artikel  
  global $httpdir;

  if( $artikelid == -1 )
  {
     $artikel=new_artikel();
  }
  else
  {  
     $artikel=get_artikel($artikelid);
  }

  echo '<div class="mainbox">', "\n";
  echo '<form action="', "${httpdir}", '/beheer/artikelen.php" method="post">', NL;
  echo '<input type="hidden" name="artikelid" value='. $artikel->id . ' >';
  echo '<TABLE align="center" class="requesttable">';
  echo "<TR><TH>Artikel</TH></TR>";

  echo '<TR><TD>Categorie</TD>';
  echo '<TD>';
  display_select_categorie($artikel->categorie, -1);
  echo '</TD></TR>';
 
  echo '<TR><TD>Merk</TD>';
  echo '<TD>';
  display_select_merk($artikel->merk, -1);
  echo '</TD></TR>';

  echo '<TR><TD>Type</TD>';
  echo '<TD><input class=textval type="text" name="type" value="'. htmlentities(stripslashes($artikel->type)) . '"> </TD></TR>';

  echo '<TR><TD>Omschrijving</TD>';
  echo '<TD><textarea class=textval name="omschrijving">'.$artikel->omschrijving. '</textarea></TD></TR>';

  echo '<TR><TD>Voorraad</TD>';
  echo '<TD class=numval>'.$artikel->voorraad .'</TD></TR>';

  echo '<TR><TD>Demo</TD>';
  echo '<TD class=numval>'.$artikel->demo .'</TD></TR>';
  
  echo '<TR><TD>RMA</TD>';
  echo '<TD class=numval>'.$artikel->rma .'</TD></TR>';

  echo '<TR><TD>Retour</TD>';
  echo '<TD class=numval>'.$artikel->retour .'</TD></TR>';

  $inkoopstr = get_moneystr($artikel->inkoop);
  echo '<TR><TD>Inkoop</TD>';
  echo '<TD>&#8364 <input class=numval type"text" name="inkoop" id="inkoop" value="' . $inkoopstr . '" onkeyup="return get_marge(0)"></TD></TR>';

  echo '<TR><TD>Marge (%)</TD>';
  echo '<TD>&#8364 <input class=numval type"text" name="marge" id="marge" value="' . $artikel->marge . '" onkeyup="return get_marge(1)"></TD></TR>';

  $prijsstr = get_moneystr($artikel->prijs);
  echo '<TR><TD>Prijs</TD>';
  echo '<TD>&#8364<input class=numval type"text" name="prijs" id="prijs" value="' . $prijsstr . '" onkeyup="return get_marge(2)"></TD></TR>';

  echo '<TR><TD>EOL</TD><TD>';
  display_select_toggle("eol", $artikel->eol, -1);
  echo '</TD></TR>';

  echo '</TABLE>';
  echo '<input type="submit" name="submit" value="Annuleren" />';
  echo '<input type="submit" name="submit" value="Opslaan" />';
  echo '</FORM>';
  echo '</div>', "\n"; 
}
?>