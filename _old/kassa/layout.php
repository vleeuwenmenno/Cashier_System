<?php 

/* mogelijke menuopties/pagina's van hoofdmodule kassa */
define( 'MKASSA', 1 );
define( 'MNIEUWEBON', 2);
define( 'MARTIKELEN', 3);
define( 'MBONNEN', 4 );
define( 'MKLANTEN', 5 );
define( 'MSLUITEN', 8 );
define( 'MOPENEN', 7 );
define( 'MSYSTEMEN', 6 );
define( 'MLOGIN', 9 );

/* mogelijke subpaginas */
define( 'SMAIN', 0 ); 
define( 'SWIJZIGEN', 1 );
define( 'SOPENEN', 2 );
define( 'SSLUITEN', 3 );
define( 'SINVOEREN', 4 );
define( 'SBEVESTIGEN', 5);
define( 'SCONTROLEREN', 6);
define( 'SRESULTAAT', 7 );
define( 'SAFROMEN', 8 );
define( 'SOVERZICHT', 9 );
define( 'SOPENING', 9 );

/* mogelijk acties */
define( 'AANNULEREN', 1 );
define( 'ABEVESTIGEN', 2 );
define( 'AOPENEN', 3 );
define( 'ASLUITEN', 4 );
define( 'AINVOEREN', 5);
define( 'AOPSLAAN', 6);
define( 'AZOEKEN', 7);
  
$kassamenu = array( 
    MKASSA => array('prompt' => "KASSA", 'ref' => "\"${httpdir}/kassa/kassa.php\"", 'status' => 'enabled' ),
    MNIEUWEBON => array('prompt' => "NIEUWE BON", 'ref' => "\"javascript:void(0)\" onClick=\"nieuwe_bon(-1)\"", 'status' => 'enabled' ),
    MARTIKELEN => array('prompt' => "ARTIKELEN", 'ref' => "\"${httpdir}/kassa/artikelen.php\"", 'status' => 'enabled' ),
    MBONNEN => array('prompt' => "BONNEN", 'ref' => "\"${httpdir}/kassa/bonnen.php\"", 'status' => 'enabled' ),
    MKLANTEN => array('prompt' => "KLANTEN", 'ref' => "\"${httpdir}/kassa/klanten.php\"", 'status' => 'enabled' ),
    MSYSTEMEN => array('prompt' => "SYSTEMEN", 'ref' => "\"${httpdir}/kassa/systemen.php\"", 'status' => 'enabled' ));

function display_kassa_menu($menuoption, $subpage)
{
  global $kassamenu;

  if( $menuoption == MLOGIN || $subpage == SWIJZIGEN || $subpage== SCONTROLEREN || $subpage == SBEVESTIGEN )
  {
     $kassamenu[MKASSA]['status'] = 'disabled';
     $kassamenu[MNIEUWEBON]['status'] = 'disabled';
     $kassamenu[MARTIKELEN]['status'] = 'disabled';
     $kassamenu[MBONNEN]['status'] = 'disabled';
     $kassamenu[MKLANTEN]['status'] = 'disabled';
     $kassamenu[MSYSTEMEN]['status'] = 'disabled';
//     $kassamenu[MSLUITEN]['status'] = 'disabled';
//     $kassamenu[MOPENEN]['status'] = 'disabled';
  }
  else
  {
     $kassamenu[$menuoption]['status'] = 'active';
  }
  display_menu($kassamenu);

}
    
?>