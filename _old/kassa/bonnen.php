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
  $kassastatus = new KassaStatus();
  $kassastatus->update();
  $query = "";
  $lastquery = "";

  $subpage = process_input_bonnen();
  display_bonnen_page($subpage);
}

function process_input_bonnen()
{
  global $module;
  global $submit;
  global $query;
  global $bonid;

  if( !isset( $submit ) )
  {
     $subpage = SMAIN;
  }
  elseif( $submit == "Zoeken" )
  {
     $query = make_bon_query();
     $subpage = SMAIN;
  }
  elseif( $submit == "Reset" )
  {
      reset_form_variables();
      $subpage = SMAIN;
  }
  elseif( $submit == "Verwijderen" )
  {
      remove_bon($bonid);
      $subpage = SMAIN;
  }
  elseif( $submit == "Alles" )
  {
      //$query = "select * from bon";
      $query = "select klant.id as klantid, bon.id as id, bon.status as status, bon.datum as datum, bon.tijd as tijd, bon.betaalwijze as betaalwijze, bon.totaal as totaal, klant.postcode as postcode, klant.achternaam as achternaam from bon LEFT JOIN klant ON bon.klantid = klant.id order by bon.datum desc";
      $subpage = SMAIN;
  }
  return $subpage;  
}

function display_bonnen_page($subpage)
{ 
  global $module;
  global $httpdir;


  display_header(); 
  echo '<body>';
  display_stylesheet();
  echo '<script src="'.$httpdir.'/php/jsfuncties.js"></script>';
  echo '<div class="screen">';
  display_kassa_menu(MBONNEN, SMAIN);
  display_bonnen_main();
  echo '</div>';
  echo '</body>';
  echo '</html>';
}


function display_bonnen_main()
{
  global $module;
  display_bonnen_query();
  display_bonnen_result();
  // display resultaat gedeelte
}

function display_bonnen_query()
{
  global $httpdir;
  extract($_GET);
  extract($_POST);
  
  echo '<div class="querybox">', "\n";
  //echo '<TABLE align="center" class="requesttable">';
  echo '<div class=queryinput>';
  echo '<TABLE class="requesttable">';  
  echo '<form action="', "${httpdir}", '/kassa/bonnen.php" method="post">', NL;
  echo "<TR><TD>Bonid</TD><TD><input type=text name=idbon></TD></TR>";
  echo "<TR><TD>Postcode</TD><TD><input type=text name=postcode></TD><TD>";
  echo "<TR><TD>Achternaam</TD><TD><input type=text name=achternaam></TD><TD>";
  echo '</TABLE>';
  echo '</div>';
  echo '<div class="querybutton">';
  echo '<input type="submit" name="submit" value="Zoeken" />';
  echo '<input type="submit" name="submit" value="Reset" />';
  echo '<input type="submit" name="submit" value="Alles" />';
  echo '</div>';
  echo '</FORM>';
  echo '</div>', "\n"; 

}

function display_bonnen_result()
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

function display_result_header()
{
  echo "<TR><TH>Bonid</TH><TH>Status</TH><TH>Datum</TH><TH>Tijd</TH><TH>Naam</TH><TH>Postcode</TH><TH>Betaalwijze</TH>";
  echo "<TH>Totaal</TH>";
  echo "</TR>";
}

function display_result_row($row, $i)
{
    global $kassastatus;

    echo "<TR>";
    echo "<input type=hidden id=bonid$i value=$row->id>";
    echo "<TD>$row->id</TD>";
    echo "<TD>$row->status</TD>";
    echo "<TD>$row->datum</TD>";
    echo "<TD>$row->tijd</TD>";
 
    if( $row->klantid != -1 )
    {
      echo "<TD>$row->achternaam</TD>";
      echo "<TD>$row->postcode</TD>";
    }
    else
    {
      echo "<TD></TD><TD></TD>";
    }
    echo "<TD>$row->betaalwijze</TD>";
    echo '<TD>'.get_moneystr($row->totaal).'</TD>';
    if( $row->status == 'wacht' )
    //if( $row->status == 'wacht' || 
    //    ($kassastatus->status == GEOPEND && ($row->datum > $kassastatus->datum || ($row->datum == $kassastatus->datum && $row->tijd >= $kassastatus->tijd ) ) ) )
    {
      echo "<TD><input class=resultbutton type=button value=\"Verwijderen\" onClick=\"verwijderen_bon($i)\"</TD>";
      echo "<TD><input class=resultbutton type=button value=\"Openen\" onClick=\"openen_bon($i)\"</TD>";
    }
    else
    {
      echo "<TD> </TD>";
      echo "<TD> </TD>";
    }
    echo "</TR>";
    
}

function reset_form_variables()
{
  global $postcode, $achternaam, $idbon;

  $idbon="";
  $postcode = "";
  $achernaam="";
}


?>