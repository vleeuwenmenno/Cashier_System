<?php 

/* mogelijke menuopties/pagina's van hoofdmodule kassa */
define( 'MSYSTEEM', 1 );
define( 'MARTIKELEN', 2);

/* mogelijke subpaginas */
define( 'SMAIN', 0 ); 
define( 'SWIJZIGEN', 1 );
define( 'SNAAM', 2 );
define( 'SBEVESTIGEN', 4);
define( 'SCONTROLEREN', 5);
define( 'SRESULTAAT', 6 );

  
$systeemmenu = array( 
    MSYSTEEM => array('prompt' => "SYSTEEM", 'ref' => "\"${httpdir}/systeem/systeem.php?\"", 'status' => 'enabled' ),
    MARTIKELEN => array('prompt' => "ARTIKELEN", 'ref' => "\"${httpdir}/systeem/artikelen.php\"", 'status' => 'enabled' ));
    

function display_systeem_menu($menuoption, $subpage)
{
  global $systeemmenu;
  global $systeemid;
  global $httpdir;

  $systeemmenu[MSYSTEEM]['ref'] = "\"${httpdir}/systeem/systeem.php?systeemid=$systeemid\"";
  $systeemmenu[MARTIKELEN]['ref'] = "\"${httpdir}/systeem/artikelen.php?systeemid=$systeemid\"";
  if( $subpage == SWIJZIGEN || $subpage == SNAAM )
  {
    $systeemmenu[MSYSTEEM]['status'] = 'disabled';
    $systeemmenu[MARTIKELEN]['status'] = 'disabled';
  }
  else
  {
     $systeemmenu[$menuoption]['status'] = 'active';
  }  
  display_menu($systeemmenu);

}
?>