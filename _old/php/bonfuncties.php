<?php

function get_bon($bonid)
{
    $dbserver = dbconnect();
    $query = sprintf("select * from bon where id=$bonid");
    $result = dbquery( $query );
    $row = mysql_fetch_object($result);
    mysql_free_result( $result);

    return $row;
}
  
function new_bon()
{
    global $module;

    $dbserver = dbconnect();
    $query = sprintf("INSERT INTO bon VALUES (NULL,-1,'',now(),now(),'','','wacht','kontant',0,0,0,0,$module->userid,$module->moduleid)");
    $result = dbquery( $query );
    $bonid = mysql_insert_id();   
    dbclose($dbserver); 
    return get_bon($bonid);
}

function add_bon_item($bonid, $artikelid, $aantal, $transactie, $demo)
{
    global $module;

    // haal artikel op
    $artikel = get_artikel( $artikelid );
    // kijk gevraagd wel in voorraad is, anders maximum doen
    
    if( $transactie == 'verkoop' )
    {
        if( $demo == 0 )
        {
           $aantalbeschikbaar = $aantal;
        }
        else
        {
           $aantalbeschikbaar = min( $aantal, $artikel->demo );
        }
    } 
    else
    {
        $aantalbeschikbaar = $aantal;
    }

    $dbserver = dbconnect();

    if( $transactie == 'verkoop' )
    {
       $prijs=$artikel->prijs;
    }
    elseif( $transactie == 'retour' )
    {
       $prijs=-1*$artikel->prijs;
    }
    elseif( $transactie == 'rma' )
    {
       $prijs = 0;
    }
    $query = sprintf("INSERT INTO item VALUES (NULL,$bonid,$artikelid,
      $aantalbeschikbaar,$artikel->prijs,'$artikel->categorie', '$artikel->merk','$artikel->type',
      '$artikel->omschrijving', $demo,'$transactie', 
       $aantalbeschikbaar * $prijs )");
    $result = dbquery( $query );
    $itemid = mysql_insert_id(); 
       dbclose($dbserver); 
   
    set_voorraad( $artikelid, $aantalbeschikbaar, $transactie, $demo );
    set_totaal($bonid);
 
    return $itemid;
}

function add_bon_systeem($bonid, $systeemid)
{
    global $module;

    // echo $bonid, '<BR>';
    // echo $systeemid;exit;
    // Haal alle systeenitems op
    $naam = get_naam_systeem($systeemid);

    set_naam_bon($bonid, $naam);
    $dbserver = dbconnect();
    $query = "select * FROM systeemitem WHERE systeemid=$systeemid";
    $result = dbquery( $query );
    $i=0;
    $itemidlist = array();
    while($row = mysql_fetch_object($result))
    {
      $systeemitemlist[$i] = $row;
      $i++;
    };
    mysql_free_result($result);
    dbclose($dbserver); 

    // nu toevoegen aan bon en waardes goed zetten
    foreach( $systeemitemlist as $systeemitem )
    {
      $itemid = add_bon_item( $bonid, $systeemitem->artikelid, $systeemitem->aantal, 'verkoop', 0 );
      $item = get_item($itemid);
      if( $item->aantal == $systeemitem->aantal )
      {
        update_bon_item_systeemitem($itemid, $systeemitem->omschrijving, $systeemitem->totaal);
      }
      else
      {
        update_bon_item_systeemitem($itemid, $systeemitem->omschrijving, $item->totaal);
      }
   }
   set_totaal($bonid);
}


function update_bon_item_systeemitem($itemid, $omschrijving, $totaal)
{
  $dbserver = dbconnect();
  $query = "update item set omschrijving='$omschrijving', totaal=$totaal where id = $itemid";  
  $result = dbquery( $query );
  mysql_free_result($result);
  dbclose($dbserver); 
}

function remove_bon( $bonid )
{
    $dbserver = dbconnect();
    $query = "select id FROM item WHERE bonid=$bonid";
    $result = dbquery( $query );
    $i=0;
    $itemidlist = array();
    while($row = mysql_fetch_object($result))
    {
      $itemidlist[$i] = $row->id;
      $i++;
    };
    mysql_free_result($result);
    dbclose($dbserver); 
 
    foreach( $itemidlist as $value )
    {
      remove_bon_item( $bonid, $value );
    }

    $dbserver = dbconnect();
    $query = "DELETE FROM bon WHERE id=$bonid";
    $result = dbquery( $query );
    //mysql_free_result($result);
    dbclose($dbserver); 
}

function remove_bon_item($bonid, $itemid )
{
    $item = get_item($itemid);

    set_voorraad( $item->artikelid, -1*$item->aantal, $item->transactie, $item->demo);

    $dbserver = dbconnect();
    $query = sprintf("DELETE FROM item WHERE id=$itemid");
    $result = dbquery( $query );
    dbclose($dbserver); 
    set_totaal($bonid);
}

function get_item($itemid )
{

    $dbserver = dbconnect();
    $query = sprintf("SELECT * FROM item WHERE id=$itemid");
    $result = dbquery( $query );
    $row = mysql_fetch_object($result);
    dbclose($dbserver); 
    return $row;
}

function add_bon_klant($bonid, $klantid )
{
  $dbserver = dbconnect();
  $query = "update bon set klantid=$klantid where id = $bonid";  
  $result = dbquery( $query );
  mysql_free_result($result);
  dbclose($dbserver); 
}

function remove_bon_klant($bonid)
{
  $dbserver = dbconnect();
  $query = "update bon set klantid=-1 where id = $bonid";  
  $result = dbquery( $query );
  mysql_free_result($result);
  dbclose($dbserver); 
}

function set_voorraad($artikelid, $aantal, $transactie, $demo)
{
  $dbserver = dbconnect();
  
  if( $transactie == 'rma' )
  {
     $query = "update artikel set rma=rma+$aantal where id = $artikelid";  
  }
  elseif( $transactie == 'retour' )
  {/*DIT AANPASSEN VOOR DE VOORRAAD*/
      $query = "update artikel set retour=retour+$aantal, voorraad=voorraad+$aantal where id = $artikelid";  
  }
  elseif( $demo == 1)
  { 
    $query = "update artikel set demo=demo-$aantal where id = $artikelid";  
  }
  else
  {
    $query = "update artikel set voorraad=voorraad-$aantal where id = $artikelid";  
  }
  $result = dbquery( $query );
  dbclose($dbserver); 

}

function set_totaal($bonid)
{
    $dbserver = dbconnect();
    $query = sprintf("SELECT sum(totaal) as totaal FROM item WHERE bonid=$bonid");
    $result = dbquery( $query );
    $row = mysql_fetch_object($result);
    
    if( isset($row->totaal) )
    {
      $totaal = $row->totaal;
    }
    else
    {
      $totaal = 0;
    }
    mysql_free_result($result);
    $query = "update bon set totaal=$totaal where id = $bonid";  
    $result = dbquery( $query );
    dbclose($dbserver); 
}

function update_bon_item()
{
  extract( $_GET );
  extract ( $_POST );

  $totaalint = get_moneyint($totaal);

  $dbserver = dbconnect();
  $query = "update item set omschrijving='$omschrijving',totaal=$totaalint where id = $itemid";  
  $result = dbquery( $query );
  dbclose( $dbserver );
  
  set_totaal( $bonid );
 
}

/*function update_bon()
{
  extract( $_GET );
  extract ( $_POST );

  $totaalint = get_moneyint($totaal);

  $dbserver = dbconnect();
  $query = "update bon set omschrijving='$omschrijving',totaal=$totaalint where id = $itemid";  
  $result = dbquery( $query );
  dbclose( $dbserver );
  
  set_totaal( $bonid );
 
}*/


function make_bon_query()
{
   
   extract($_GET);  
   extract($_POST);  
   
   $query = "";
   if( isset($idbon) && $idbon != "")
   { 
     $query ="bon.id = $idbon";
   }
   
   if( isset($postcode) && $postcode != "") 
   {
      if( $query == "" ){ $query=" klant.postcode LIKE '%$postcode%'"; }
      else { $query=$query." and klant.postcode LIKE '%$postcode%'"; }
   }

   if( isset($achternaam) && $achternaam!="")
   {
      if( $query == "" ){ $query=" klant.achternaam LIKE '%$achternaam%'"; }
      else { $query=$query." and klant.achternaam LIKE '%$achternaam%'"; }
   }

 
   if( $query != "" )
   {
       $query = "select klant.id as klantid, bon.id as id, bon.status as status, bon.datum as datum, bon.tijd as tijd, bon.totaal as totaal, klant.postcode as postcode, klant.achternaam as achternaam from bon LEFT JOIN klant ON bon.klantid = klant.id where ".$query." order by bon.datum desc";
   }
   return $query;
   
}

function update_bon( $bonid, $naam, $betaalwijze, $kontant, $pin, $rekening )
{
  $dbserver = dbconnect();
  $query = "update bon set naam = '$naam', betaalwijze='$betaalwijze', kontant=$kontant, pin=$pin, 
              rekening=$rekening where id = $bonid";  
  $result = dbquery( $query );
  dbclose( $dbserver );
}

function set_betaald_bon( $bonid )
{
  $dbserver = dbconnect();
  $query = "update bon set status = 'betaald' , datum=now(), tijd=now() where id = $bonid";  
  $result = dbquery( $query );
  dbclose( $dbserver );
}

function terugnemen_bon( $bonid )
{
  $dbserver = dbconnect();
  $query = "update bon set status = 'wacht' , datum='', tijd='' where id = $bonid";  
  $result = dbquery( $query );
  dbclose( $dbserver );
}


function set_naam_bon( $bonid, $naam )
{
  $dbserver = dbconnect();
  $query = "update bon set naam='$naam' where id = $bonid";  
  $result = dbquery( $query );
  dbclose( $dbserver );
}

?>