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
  global $bonid;

  display_header("Bon Nr: $bonid"); 
  echo '<body>';
  display_stylesheet();
  echo '<script src="'.$httpdir.'/php/jsfuncties.js"></script>';
  echo '<div class="screen">';
  display_bon_menu(MARTIKELEN, SMAIN);
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
  global $bonid;
  global $eol;

  $displaytype = htmlentities(stripslashes($type));
  echo '<div class="querybox">', "\n";
  //echo '<TABLE align="center" class="requesttable">';
  echo '<div class=queryinput>';
  echo '<TABLE class="requesttable">';  
  echo '<form action="', "${httpdir}", '/bon/artikelen.php" method="post">', NL;
  echo "<input type=hidden name=bonid id=bonid value=$bonid>";
  echo "<TR><TD>Type</TD><TD><input type=\"text\" name=\"type\" value=\"$displaytype\" onkeypress=\"return submitViaEnter(event, 2)\"></TD></TR>";
  echo "<TR><TD>Merk</TD><TD>";
  display_select_merk($merk, 2);
  echo "</TD></TR>";
  echo "<TR><TD>Categorie</TD><TD>";
  display_select_categorie($categorie,2);
  echo "</TD></TR>";
  echo '<TR><TD>EOL</TD><TD>';
  display_select_toggle_query("eol", $eol, 2);
  echo '</TD></TR>';

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
  global $bonid;

  echo '<div class="resultbox">', "\n";
  echo "<input type=hidden id=bonid value=$bonid>";
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
  echo "<TR><TH>Aantal</TH><TH>Categorie</TH><TH>Merk</TH><TH>Type</TH><TH>EOL</TH><TH>Prijs</TH>";
  echo "<TH>Voorraad</TH><TH>Demo</TH><TH>Uit demo</TH><TH>Verkoop</TH>";
  echo "<TH>RMA</TH><TH>Retour</TH></TR>";
}

function display_result_row($row, $i)
{
    echo "<TR>";
    echo "<input type=hidden id=artikelid$i value=$row->id>";
    echo "<TD><input class=numval type=text size=3 value=1 id=aantal$i ></TD>";
    echo "<TD>$row->categorie</TD>";
    echo "<TD>$row->merk</TD>";
    $displaytype = stripslashes($row->type);
    echo "<TD>$displaytype</TD>";
    echo "<TD>$row->eol</TD>";
    $prijs = get_moneystr($row->prijs);
    echo "<TD>&#8364 $prijs</TD>";
    echo "<TD>$row->voorraad</TD>";
    echo "<TD>$row->demo</TD>";
    if( $row->demo == 0 ) 
    { 
      echo "<TD> <input type=hidden id=demo$i value=off ></TD>"; 
    }
    else 
    {
      echo "<TD><input type=checkbox id=demo$i value=1></TD>";   
    }
    echo "<TD><input type=button class=resultbutton value=\"VER\" onClick=\"toevoegen_bon_artikel($i,'verkoop')\"</TD>";
    echo "<TD><input type=button class=resultbutton value=\"RMA\" onClick=\"toevoegen_bon_artikel($i,'rma')\"</TD>";
    echo "<TD><input type=button class=resultbutton value=\"RET\" onClick=\"toevoegen_bon_artikel($i,'retour')\"</TD>";
    echo "</TR>";
    
}
?>