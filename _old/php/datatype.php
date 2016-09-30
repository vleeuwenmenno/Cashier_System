<?php

class Module {

  var $userid;
  var $usernaam;
  var $moduleid;
  var $modulenaam;
  var $moduletype;
  var $ipaddress;

  function set_module($user, $type)
  {
    // global $REMOTE_ADDR;
    $ipaddress = $_SERVER['REMOTE_ADDR'];
 
    $this->usernaam = $user;
    $this->userid = get_userid($this->usernaam);
    $this->moduletype = $type;
    
    $dbserver = dbconnect();
   
    $query = "SELECT id, naam FROM module WHERE ipaddress = '$ipaddress' AND type='$type'"; 
            
    $result = dbquery( $query );
    
    if( mysql_num_rows( $result ) != 0 )
    {
      $retval = mysql_fetch_assoc($result);
      $this->modulenaam = $retval['naam'];
      $this->moduleid = $retval['id'];
      // $this->ipaddress = $_SERVER['REMOTE_ADDR'];
      $this->ipaddress = $ipaddress;
    }
    else
    {
     $this->modulenaam = "ONBEKEND";
     $this->moduleid = -1;
    }
    dbclose($dbserver); 

    return 0;
  }
  
}

class KassaStatus {

  var $status;
  var $datum;
  var $tijd;
  var $userid;
  var $usernaam;
  var $kasin;
  var $kasuit;
  var $kasgeld;
  var $afromen;
  var $pinbon;
  var $oprekening;
  var $kasverschil;
  var $controle;
  var $commentaar;
  var $moduleid;
  
  function update($status = -1)
  {
    global $module;

    $dbserver = dbconnect();
  
    if( $status == GEOPEND || $status == GESLOTEN )
    {
      $query = sprintf("SELECT * FROM kassalog, user WHERE moduleid = '%s' AND status = %d ORDER BY datum DESC, tijd DESC", 
                   $module->$moduleid, $status );
    } else
    {
       $query = sprintf("SELECT * FROM kassalog WHERE moduleid = '%s' ORDER BY datum DESC, tijd DESC", 
                   $module->moduleid );
      
    }
                   
    $result = dbquery( $query );
  
    $retval = false;
  
    if( mysql_num_rows( $result ) != 0 )
    {
      $row = mysql_fetch_object($result);
    }
  
    dbclose($dbserver); 
 
    $this->userid = $row->userid;
    $this->usernaam = get_usernaam($row->userid);
    $this->status = $row->status;
    $this->kasin = $row->kasin;
    $this->kasuit = $row->kasuit;
    $this->kasgeld= $row->kasgeld;
    $this->afromen = $row->afromen;
    $this->pinbon = $row->pinbon;
    $this->oprekening = $row->oprekening;
    $this->controle = $row->controle;
    $this->commentaar = $row->commentaar;
    $this->datum = $row->datum;
    $this->tijd = $row->tijd;
    $this->moduleid = $row->moduleid;
    
    return $this;
  }
  
  function set_opening( $sluiting )
  {
    $status = GEOPEND;
    $dbserver = dbconnect();
    $query = "SELECT * FROM kassalog WHERE 
               moduleid = $sluiting->moduleid AND status = $status 
               AND ( (datum <= '$sluiting->datum' AND tijd < '$sluiting->tijd' )
                     OR datum < '$sluiting->datum' )
               ORDER BY datum DESC, tijd DESC";
    $result = dbquery( $query );
  
    $retval = false;
  
    if( mysql_num_rows( $result ) != 0 )
    {
      $row = mysql_fetch_object($result);
    }
  
    dbclose($dbserver); 
 
    $this->userid = $row->userid;
    $this->usernaam = get_usernaam($row->userid);
    $this->status = $row->status;
    $this->kasin = $row->kasin;
    $this->kasuit = $row->kasuit;
    $this->kasgeld= $row->kasgeld;
    $this->afromen = $row->afromen;
    $this->pinbon = $row->pinbon;
    $this->oprekening = $row->oprekening;
    $this->controle = $row->controle;
    $this->commentaar = $row->commentaar;
    $this->datum = $row->datum;
    $this->tijd = $row->tijd;
    
    return $this;
   
  }
}


?>