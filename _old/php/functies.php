<?php

// functies algemeen 

function logincomtoday( $user, $password, $module )
{

  $dbserver = dbconnect();

  $query = sprintf("SELECT id, role FROM user WHERE naam LIKE BINARY '%s' AND password LIKE BINARY '%s'", 
                   mysql_real_escape_string( $user ),
                   mysql_real_escape_string( $password ) );
                   
  $result = dbquery( $query );
  
  $retval = false;

  if( mysql_num_rows( $result ) != 0 )
  {
    $row = mysql_fetch_assoc($result);
    if( $row["role"] == 'Beheerder' )
    {
      $retval = true;
    }
    elseif( $module == 'Kassa' && $row["role"] == 'Medewerker' )
    {
      $retval = true;
    }
  }
  mysql_close($dbserver); 
    
  return $retval;
  
}

function get_omzet($begindatum, $begintijd, $einddatum, $eindtijd, $betaalwijze)
{
  switch( $betaalwijze )
  {
      case PIN: $fieldname = "pin"; break;
      case KONTANT: $fieldname = "kontant"; break;
      case REKENING: $fieldname = "rekening"; break;
      case TOTAAL: $fieldname = "totaal"; break;
  }
  
  $dbserver = dbconnect(DBHOST, DBUSER, DBPASSWORD);
 
  if( $einddatum == "" )
  {
     $query = "SELECT sum($fieldname) AS totaal FROM bon WHERE datum >= '$begindatum' AND tijd >= '$begintijd' AND status='betaald'";
  }
  else
  {
       $query = "SELECT sum($fieldname) AS totaal FROM bon 
                 WHERE datum >= '$begindatum' AND tijd >= '$begintijd'
                 AND datum <= '$einddatum' AND tijd < '$eindtijd' AND status='betaald'";
  }

  $result = dbquery( $query );

  if( mysql_num_rows( $result ) != 0 )
  {
    $tmpval = mysql_fetch_assoc($result);
    $retval = $tmpval['totaal'];
   }
  else
  {
    $retval = -1;
  }

  return $retval;
}

function get_inkoop($begindatum, $begintijd, $einddatum, $eindtijd)
{
  switch( $betaalwijze )
  {
      case PIN: $fieldname = "pin"; break;
      case KONTANT: $fieldname = "kontant"; break;
      case REKENING: $fieldname = "rekening"; break;
      case TOTAAL: $fieldname = "totaal"; break;
  }
  
  $dbserver = dbconnect(DBHOST, DBUSER, DBPASSWORD);
 
  if( $einddatum == "" )
  {
     $query = "SELECT sum($fieldname) AS totaal FROM bon WHERE datum >= '$begindatum' AND tijd >= '$begintijd' AND status='betaald'";
  }
  else
  {
       $query = "SELECT sum($fieldname) AS totaal FROM bon 
                 WHERE datum >= '$begindatum' AND tijd >= '$begintijd'
                 AND datum <= '$einddatum' AND tijd < '$eindtijd' AND status='betaald'";
  }

  $result = dbquery( $query );

  if( mysql_num_rows( $result ) != 0 )
  {
    $tmpval = mysql_fetch_assoc($result);
    $retval = $tmpval['totaal'];
   }
  else
  {
    $retval = -1;
  }

  return $retval;
}

function open_kassa($userid, $moduleid, $kasin, $controle, $commentaar)
{
  $dbserver = dbconnect();

  $query = "INSERT INTO kassalog VALUES (NULL, $userid, $moduleid, 1, $kasin, 0,0,0,0,0,0,$controle, '$commentaar', now(), now())";
 
  $result = dbquery( $query );

  dbclose($dbserver);
}

function sluit_kassa($userid, $moduleid, $kasin, $kasuit, $kasgeld, $afromen, $pin, $rekening, $kasverschil, $controle, $commentaar)
{

  $dbserver = dbconnect();

  $query = "INSERT INTO kassalog VALUES 
           (NULL, $userid, $moduleid, 2, $kasin, $kasuit, $kasgeld, $afromen,
            $pin, $rekening, $kasverschil, $controle, '$commentaar' , now(), now())";

  $result = dbquery( $query );

  dbclose($dbserver);
}

function get_modulenaam( $moduleid )
{
  $dbserver = dbconnect();
  
  $query ="SELECT naam FROM module WHERE id = $moduleid"; 
             
  $result = dbquery( $query );
   
  $retval = array('naam'=>"ONBEKEND");  
  
  if( mysql_num_rows( $result ) != 0 )
  {
    $retval = mysql_fetch_assoc($result);
  }
  
  dbclose($dbserver); 
  
  return $retval['naam'];
  
}

function get_modulenaam_locatie( $ipaddress, $moduletype )
{
  $dbserver = dbconnect();

  $tmpipaddress = $ipaddress;
  
  $query ="SELECT naam FROM module WHERE ipaddress = '$tmpipaddress' AND type='$moduletype'"; 
             
  $result = dbquery( $query );
   
  $retval = array('naam'=>"ONBEKEND");  
  
  if( mysql_num_rows( $result ) != 0 )
  {
    $retval = mysql_fetch_assoc($result);
  }
  
  dbclose($dbserver); 
  
  return $retval['naam'];
  
}



function get_usernaam( $userid )
{
  $dbserver = dbconnect();
  
  $query = sprintf("SELECT naam FROM user WHERE id = '%s'", $userid ); 
           
  
  $result = dbquery( $query );
   
  $retval = array('naam'=>"ONBEKEND");  
  
  if( mysql_num_rows( $result ) != 0 )
  {
    $retval = mysql_fetch_assoc($result);
  }
  
  dbclose($dbserver); 
  
  return $retval['naam'];
  
}

function get_userid( $usernaam )
{
  $dbserver = dbconnect(DBHOST, DBUSER, DBPASSWORD);
  
  $query = sprintf("SELECT id FROM user WHERE naam = '%s'", $usernaam ); 
           
  
  $result = dbquery( $query );
   
  $retval = array('id'=>-1);  
  
  if( mysql_num_rows( $result ) != 0 )
  {
    $retval = mysql_fetch_assoc($result);
  }
  
  dbclose($dbserver); 
  
  return $retval['id'];
  
}

function get_moneystr( $moneyint )
{
  $sign = "";	
  if( $moneyint < 0 ){ $sign = "-"; };	
   
  $moneyint = abs( $moneyint );
  $rest = strval($moneyint % 100);
  if( strlen($rest) == 1) $rest = "0".$rest;
  $absbedrag = strval( floor( $moneyint / 100)).".".$rest;
  
  return $sign.$absbedrag;
}

function get_moneyint( $moneystr )
{
  $sign = 1;
  $tmpstr = trim( $moneystr );
  if( $tmpstr[0] == '-' )
  {
    $sign = -1;
    $str = substr( $tmpstr, 1, strlen($tmpstr));
    $tmpstr = $str;
  }
  $position = strrpos($tmpstr, ",");
  if( $position === false )
  {
     $position = strrpos($tmpstr, ".");
  }

  if( $position === false )
  {
    $str = $tmpstr;
    $reststr = "";
  }
  else
  {
     $str = substr( $tmpstr, 0, $position);
     $reststr = substr( $tmpstr, $position, strlen($tmpstr) );
  }
     
  if( strlen($reststr) == 0 )
  { 
    $rest = 0;
  }
  elseif( strlen($reststr) == 1 )
  {
    $rest = 10 * $reststr[1];
  }
  else
  {
    $rest = 10 * $reststr[1];
    $rest = $rest + $reststr[2];
  }
 
     	
  return $sign*(100*strval($str) + $rest);
 
}

function formatmoney( $moneystr )
{
  return get_moneystr( get_moneyint( $moneystr ));
}

function get_datum( $dag, $maand, $jaar )
{
  $datum = $jaar."-";
  if( $maand  < 10 )
  {
    $datum = $datum."0".$maand."-";
  }
  else
  {
    $datum = $datum.$maand."-";
  }

  if( $dag < 10 )
  {
    $datum = $datum."0".$dag;
  }
  else
  {
    $datum = $datum.$dag;
  }

  return $datum;
}

function get_marge( $totaal, $inkoop )
{
 $marge = 0;

 if( $inkoop != 0 && $totaal != 0)
 {
   $exprijs = floor( $totaal/BTW + 0.5);
   $marge = round(($exprijs - $inkoop) / $inkoop, 2);
 }

 return 100*$marge; 

}

function get_nettowinst( $totaal, $inkoop )
{
 $nettowinst = 0;

 if( $inkoop != 0 && $totaal != 0)
 {
   $exprijs = floor( $totaal/BTW + 0.5);
   $nettowinst = $exprijs - $inkoop;
 }

 return $nettowinst;

}

function get_exbtw( $totaal )
{
 $exbtw = floor( $totaal/BTW + 0.5);
 
 return $exbtw;

}
?>