<?php

function get_artikel($artikelid)
{
    $dbserver = dbconnect();
    $query = sprintf("select * from artikel where id=$artikelid");
    $result = dbquery( $query );
    $row = mysql_fetch_object($result);
    mysql_free_result( $result);

    return $row;
}
  
function new_artikel()
{
    $dbserver = dbconnect();
    $query = "INSERT INTO artikel VALUES(NULL,'','','',0,0,0,'',0,0,0,0,'Nee')";
    $result = dbquery( $query );
    $artikelid = mysql_insert_id(); 
    dbclose($dbserver); 
    return get_artikel($artikelid);
}

function update_artikel()
{ 
    extract($_POST);
    extract($_GET);

    $inkoopint = get_moneyint( $inkoop );
    $prijsint = get_moneyint( $prijs );
    $dbserver = dbconnect();
    $query = "update artikel set categorie='$categorie',
                             merk='$merk',
                             type='$type',
                             marge='$marge',
                             omschrijving='$omschrijving',
                             inkoop=$inkoopint,
                             prijs=$prijsint,
                             eol='$eol' where id = $artikelid";
     dbquery($query);
     dbclose($dbserver);
} 

function update_voorraad()
{
   global $module;
   global $transactie, $aantal, $artikelid;
   $artikel = get_artikel($artikelid);
   $totaal = 0;
   if( $transactie == 'voorraad' )
   {
    $tmpaantal = $aantal;
    $query = "update artikel set voorraad=voorraad+$tmpaantal where id=$artikelid";
    $totaal = $aantal * $artikel->inkoop;
   }
   elseif( $transactie == 'rma' )
   {
     $tmpaantal = min($aantal, $artikel->rma);
     $query = "update artikel set rma=rma-$tmpaantal where id=$artikelid";
   }
   elseif( $transactie == 'voorraaddemo' )
   {
     $tmpaantal = min($aantal, $artikel->voorraad);
     $query = "update artikel set voorraad=voorraad-$tmpaantal, demo=demo+$tmpaantal where id=$artikelid";
   }
   elseif( $transactie == 'demovoorraad' )
   {
     $tmpaantal = min($aantal, $artikel->demo);
     $query = "update artikel set voorraad=voorraad+$tmpaantal, demo=demo-$tmpaantal where id=$artikelid";
   }
   $dbserver = dbconnect();
   dbquery($query);
 
   // pas voorraadlog aan   
   $query = "INSERT INTO voorraadlog VALUES(NULL,$module->userid,$module->moduleid,
             $artikelid,$artikel->inkoop,$artikel->prijs,'$transactie', $tmpaantal,$totaal,
             now(),now())";
   $result = dbquery( $query );
   dbclose($dbserver); 

   // nu nog voorraadlog bijwerken

}

function make_artikel_query()
{
   
   extract($_GET);  
   extract($_POST);  

   $query = "";
   if( isset($type) && $type!="")
   { 
     if( $query == "" ) { $query="type LIKE '%$type%'"; } 
     else { $query = $query." and type LIKE '%$type%'"; }
   }

   if( isset($artikelid) && $artikelid!="")
   { 
     if( $query == "" ) { $query="id = $artikelid"; } 
     else { $query = $query." and id = $artikelid"; }
   }
   
   if( isset($categorie) && $categorie != "") 
   {
      if( $query == "" ){ $query=$query."categorie LIKE '%$categorie%'"; }
      else { $query=$query. " and categorie LIKE '%$categorie%'"; }
   }

   if( isset($merk) && $merk != "") 
   {
      if( $query == "" ){ $query=$query."merk LIKE '%$merk%'"; }
      else { $query=$query." and merk LIKE '%$merk%'"; }
   }

   if( !isset($eol) || $eol == "")
   {
   	 if( $query != "" ){ $query=$query." and eol = 'Nee'"; }
   }
   else
   {
     if( $query != "" )
     { 
     	 $query=$query." and eol = '$eol'"; 
     }
     else
     {
     	 $query= " eol = '$eol'"; 
     }
   }

   if( $query != "" ){$query = "select * from artikel where ".$query; }

   return $query;
   
}

function verwijderen_categorie($categorieid)
{
  $dbserver = dbconnect();
  $query = "DELETE FROM categorie WHERE id=$categorieid";
  $result = dbquery( $query );
  dbclose($dbserver); 
}

function toevoegen_categorie($naam)
{
  $dbserver = dbconnect();
  $query = "SELECT * FROM categorie WHERE naam='$naam'";
  $result = dbquery( $query );
  if( mysql_num_rows($result) == 0 )
  {
    $query = "INSERT INTO categorie VALUES (NULL,'$naam')";
    $result = dbquery( $query );
    $categorieid = mysql_insert_id(); 

  }
  else
  {
     $row=mysql_fetch_object($result);
     $categorieid = $row->id;
  }

  dbclose($dbserver); 
  return $categorieid;

}

function verwijderen_merk($merkid)
{
  $dbserver = dbconnect();
  $query = "DELETE FROM merk WHERE id=$merkid";
  $result = dbquery( $query );
  dbclose($dbserver); 
}

function toevoegen_merk($naam)
{
  $dbserver = dbconnect();
  $query = "SELECT * FROM merk WHERE naam='$naam'";
  $result = dbquery( $query );
  if( mysql_num_rows($result) == 0 )
  {
    $query = "INSERT INTO merk VALUES (NULL,'$naam')";
    $result = dbquery( $query );
    $categorieid = mysql_insert_id(); 

  }
  else
  {
     $row=mysql_fetch_object($result);
     $categorieid = $row->id;
  }

  dbclose($dbserver); 
  return $categorieid;

}

?>