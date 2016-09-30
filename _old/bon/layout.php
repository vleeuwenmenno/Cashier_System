<?php 

/* mogelijke menuopties/pagina's van hoofdmodule kassa */
define( 'MBON', 1 );
define( 'MARTIKELEN', 2);
define( 'MKLANTEN', 3 );
define( 'MSYSTEMEN', 4 );

/* mogelijke subpaginas */
define( 'SMAIN', 0 ); 
define( 'SWIJZIGEN', 1 );
define( 'SINVOEREN', 2 );
define( 'SBEVESTIGEN', 4);
define( 'SCONTROLEREN', 5);
define( 'SRESULTAAT', 6 );
define( 'SSLUITEN', 7 );

  
$bonmenu = array( 
    MBON => array('prompt' => "BON", 'ref' => "\"${httpdir}/bon/bon.php?\"", 'status' => 'enabled' ),
    MARTIKELEN => array('prompt' => "ARTIKELEN", 'ref' => "\"${httpdir}/bon/artikelen.php\"", 'status' => 'enabled' ),
    MKLANTEN => array('prompt' => "KLANTEN", 'ref' => "\"${httpdir}/bon/klanten.php\"", 'status' => 'enabled' ),
    MSYSTEMEN => array('prompt' => "SYSTEMEN", 'ref' => "\"${httpdir}/bon/systemen.php\"", 'status' => 'enabled' ));


function display_bon_menu($menuoption, $subpage)
{
  global $kassastatus;
  global $bonmenu;
  global $bonid;
  global $httpdir;

  $bonmenu[MBON]['ref'] = "\"${httpdir}/bon/bon.php?bonid=$bonid\"";
  $bonmenu[MARTIKELEN]['ref'] = "\"${httpdir}/bon/artikelen.php?bonid=$bonid\"";
  $bonmenu[MKLANTEN]['ref'] = "\"${httpdir}/bon/klanten.php?bonid=$bonid\"";
  $bonmenu[MSYSTEMEN]['ref'] = "\"${httpdir}/bon/systemen.php?bonid=$bonid\"";
  $bonmenu[MSYSTEMEN]['enabled'] = false;
  if( $subpage == SWIJZIGEN || $subpage == SRESULTAAT )
  {
    $bonmenu[MBON]['status'] = 'disabled';
    $bonmenu[MARTIKELEN]['status'] = 'disabled';
    $bonmenu[MKLANTEN]['status'] = 'disabled';
    $bonmenu[MSYSTEMEN]['status'] = 'disabled';
  }
  else
  {
     $bonmenu[$menuoption]['status'] = 'active';
  }  
  display_menu($bonmenu);

}


?>