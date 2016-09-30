<?php

function get_klant( $klantid )
{
    $dbserver = dbconnect();
    $query = "select * from klant where id = $klantid";
    $result = dbquery($query);
    $row = mysql_fetch_object($result);
    mysql_free_result( $result);
    dbclose( $dbserver );
    return $row;

}

function new_klant()
{
  $dbserver = dbconnect();
  $query = "insert into klant values (NULL,'', '', '', '', '', '', '', '', '', '', 0, 0 )";
  $result = dbquery( $query );
  $klantid = mysql_insert_id();
  mysql_free_result();
  dbclose($dbserver);

   return get_klant( $klantid );

}

function update_klant()
{ 
    extract($_POST);
    extract($_GET);
    $dbserver = dbconnect();
    $query = "update klant set achternaam='$achternaam',
                             bedrijfsnaam='$bedrijfsnaam',
                             voorletters='$voorletters',
                             tussenvoegsel='$tussenvoegsel',
                             straat='$straat',
                             huisnr='$huisnr',
                             postcode='$postcode',
                             woonplaats='$woonplaats',
                             telefoon='$telefoon',
                             email ='$email',
                             debiteur='$debiteur',
                             eol='$eol' where id = $klantid";
     dbquery($query);
     dbclose($dbserver);
} 

function display_klanten_wijzigen($klantid)
{
  // get klant gegevens, if -1 nieuwe klanten  
  global $httpdir;
  global $module;

  if( $klantid == -1 )
  {
     $klant=new_klant();
  }
  else
  {  
     $klant=get_klant($klantid);
  }

  echo '<div class="mainbox">', "\n";
  if( $module->moduletype == 'Kassa' )
  {
    echo '<form action="', "${httpdir}", '/kassa/klanten.php" method="post">', NL;
  }
  else
  {
     echo '<form action="', "${httpdir}", '/beheer/klanten.php" method="post">', NL;
  }
  echo '<input type="hidden" name="klantid" value='. $klant->id . ' >';
  echo '<TABLE align="center" class="requesttable">';
  echo "<TR><TH>Klant</TH></TR>";

  echo '<TR><TD>Bedrijfsnaam</TD>';
  echo '<TD><input type"text" name="bedrijfsnaam" value="'.$klant->bedrijfsnaam . '" ><TD></TR>';

  echo '<TR><TD>Achternaam</TD>';
  echo '<TD><input type"text" name="achternaam" value="'.$klant->achternaam . '" ><TD></TR>';

  echo '<TR><TD>Tussenvoegsel</TD>';
  echo '<TD><input type"text" name="tussenvoegsel" value="'.$klant->tussenvoegsel . '" ><TD></TR>';

  echo '<TR><TD>Voorletter</TD>';
  echo '<TD><input type"text" name="voorletters" value="'.$klant->voorletters . '" ><TD></TR>';

  echo '<TR><TD>Straat</TD>';
  echo '<TD><input type"text" name="straat" value="'.$klant->straat . '" ><TD></TR>';

  echo '<TR><TD>Huisnr</TD>';
  echo '<TD><input type"text" name="huisnr" value="'.$klant->huisnr . '" ><TD></TR>';

  echo '<TR><TD>Postcode</TD>';
  echo '<TD><input type"text" name="postcode" value="'.$klant->postcode . '" ><TD></TR>';

  echo '<TR><TD>Woonplaats</TD>';
  echo '<TD><input type"text" name="woonplaats" value="'.$klant->woonplaats . '" ><TD></TR>';

  echo '<TR><TD>Telefoonnummer</TD>';
  echo '<TD><input type"text" name="telefoon" value="'.$klant->telefoon . '" ><TD></TR>';

  echo '<TR><TD>Emailadres</TD>';
  echo '<TD><input type"text" name="email" value="'.$klant->email . '" ><TD></TR>';

  echo '<TR><TD>Debiteur</TD><TD>';
  echo  display_select_toggle("debiteur", $klant->debiteur).'</TD></TR>';

  echo '<TR><TD>EOL</TD><TD>';
  echo display_select_toggle("eol", $klant->eol).'</TD></TR>';

  echo '</TABLE>';
  echo '<input type="submit" name="submit" value="Annuleren" />';
  echo '<input type="submit" name="submit" value="Opslaan" />';
  echo '</FORM>';
  echo '</div>', "\n"; 
}

function make_klant_query()
{
   extract($_GET);
   extract($_POST);
   
   $query = "";
   if( isset( $achternaam ) && $achternaam != "" )
   {
      $query = "achternaam LIKE '%$achternaam%'";
   }
   
   if( isset($bedrijfsnaam) && $bedrijfsnaam !="")
   { 
     if( $query == "" ) { $query="bedrijfsnaam LIKE '%$bedrijfsnaam%'"; } 
     else { $query = $query." and bedrijfsnaam LIKE '%$bedrijfsnaam%'"; }
   }
   
   if( isset($contactpersoon) && $contactpersoon != "") 
   {
      if( $query == "" ){ $query=$query."contactpersoon LIKE '%$contactpersoon%'"; }
      else { $query=$query. " and contactpersoon LIKE '%$contactpersoon%'"; }
   }

   if( isset($woonplaats) && $woonplaats != "") 
   {
      if( $query == "" ){ $query=$query."woonplaats LIKE '%$woonplaats%'"; }
      else { $query=$query." and woonplaats LIKE '%$woonplaats%'"; }
   }

   if( isset($postcode) && $postcode != "") 
   {
      if( $query == "" ){ $query=$query."postcode LIKE '%$postcode%'"; }
      else { $query=$query." and postcode LIKE '%$postcode%'"; }
   }

   if( $query != "" )
   {
      $query = "select * from klant where ".$query." order by achternaam, bedrijfsnaam";
   }

   return $query;
   
}

?>