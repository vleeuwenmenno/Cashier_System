<?php

function get_systeem($systeemid)
{
    $dbserver = dbconnect();
    $query = sprintf("select * from systeem where id=$systeemid");
    $result = dbquery( $query );
    $row = mysql_fetch_object($result);
    mysql_free_result( $result);

    return $row;
}
  
function new_systeem()
{
    global $module;

    $dbserver = dbconnect();
    $query = "INSERT INTO systeem VALUES (NULL,'Nieuw',0)";
    $result = dbquery( $query );
    $systeemid = mysql_insert_id();   
    dbclose($dbserver); 
    return get_systeem($systeemid);
}

function add_systeem_item($systeemid, $artikelid, $aantal)
{
    global $module;

    // haal artikel op
    $artikel = get_artikel( $artikelid );
    // kijk gevraagd wel in voorraad is, anders maximum doen
    
    echo $systeemid;
    $dbserver = dbconnect();

    $totaal=$aantal * $artikel->prijs;
    
    $query = "INSERT INTO systeemitem VALUES (NULL,$systeemid,$artikelid,
      '$artikel->categorie', '$artikel->merk','$artikel->type',
      '$artikel->omschrijving', $aantal,$totaal )";
    $result = dbquery( $query );
    // $systeemid = mysql_insert_id(); 
       
    set_totaal_systeem($systeemid);

    dbclose($dbserver); 
}

function remove_systeem( $systeemid )
{
    $dbserver = dbconnect();
    $query = "DELETE FROM systeemitem WHERE systeemid=$systeemid";
    $result = dbquery( $query );
    $query = "DELETE FROM systeem WHERE id=$systeemid";
    $result = dbquery( $query );
    mysql_free_result();
    dbclose($dbserver); 
}

function remove_systeem_item($systeemid, $systeemitemid )
{
    $dbserver = dbconnect();
    $query = "DELETE FROM systeemitem WHERE id=$systeemitemid";
    $result = dbquery( $query );
    dbclose($dbserver); 
    set_totaal_systeem($systeemid);
}

function get_systeemitem($systeemitemid )
{
    $dbserver = dbconnect();
    $query = sprintf("SELECT * FROM systeemitem WHERE id=$systeemitemid");
    $result = dbquery( $query );
    $row = mysql_fetch_object($result);
    dbclose($dbserver); 
    return $row;
}

function set_totaal_systeem($systeemid)
{
    $dbserver = dbconnect();
    $query = "SELECT sum(totaal) as totaal FROM systeemitem WHERE systeemid=$systeemid";
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
    $query = "update systeem set totaal=$totaal where id = $systeemid";  
    $result = dbquery( $query );
    dbclose($dbserver); 
}

function update_systeem_item($systeemid, $systeemitemid, $omschrijving, $totaal)
{
 
  $totaalint = get_moneyint($totaal);

  $dbserver = dbconnect();
  $query = "update systeemitem set omschrijving='$omschrijving',totaal=$totaalint where id = $systeemitemid";  
  $result = dbquery( $query );
  dbclose( $dbserver );
  
  set_totaal_systeem( $systeemid );
 
}

function set_naam_systeem($systeemid, $naam)
{
    $dbserver = dbconnect();
    $query = "update systeem set naam='$naam' where id = $systeemid";  
    $result = dbquery( $query );
    dbclose($dbserver); 
}

function get_naam_systeem($systeemid)
{
    $dbserver = dbconnect();
    $query = "select naam from systeem where id = $systeemid";  
    $result = dbquery( $query );
    $row = mysql_fetch_object($result);
    dbclose($dbserver); 
    return $row->naam;
}
?>