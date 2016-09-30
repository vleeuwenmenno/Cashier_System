<?php 

/* mogelijke menuopties/pagina's van hoofdmodule kassa */
define( 'MBEHEER', 1 );
define( 'MBONNEN', 2);
define( 'MARTIKELEN', 3);
define( 'MKLANTEN', 4 );
define( 'MSYSTEMEN', 5 );
define( 'MCATEGORIEEN', 6 );
define( 'MMERKEN', 7 );
define( 'MOVERZICHTEN', 8 );
define( 'MLOGIN', 9 );
define( 'MUSERS', 10 );

/* mogelijke subpaginas */
define( 'SMAIN', 0 ); 
define( 'SWIJZIGEN', 1 );
define( 'SINVOEREN', 2 );
define( 'SBEVESTIGEN', 4);
define( 'SCONTROLEREN', 5);
define( 'SRESULTAAT', 6 );
define( 'SVERKOOP', 7 );
define( 'SVOORRAAD', 8 );
define( 'SMESSAGE', 9 );
define( 'SMUTATIES', 10 );
define( 'SAFDRUKKEN', 11 );
define( 'SOMZET', 12 );
  
$beheermenu = array( 
    MBEHEER => array('prompt' => "BEHEER", 'ref' => "\"${httpdir}/beheer/beheer.php\"", 'status' => 'enabled' ),
    MBONNEN => array('prompt' => "BONNEN", 'ref' => "\"${httpdir}/beheer/bonnen.php\"", 'status' => 'enabled' ),
    MARTIKELEN => array('prompt' => "ARTIKELEN", 'ref' => "\"${httpdir}/beheer/artikelen.php\"", 'status' => 'enabled' ),
    MKLANTEN => array('prompt' => "KLANTEN", 'ref' => "\"${httpdir}/beheer/klanten.php\"", 'status' => 'enabled' ),
    MSYSTEMEN => array('prompt' => "SYSTEMEN", 'ref' => "\"${httpdir}/beheer/systemen.php\"", 'status' => 'enabled' ),
    MMERKEN => array('prompt' => "MERKEN", 'ref' => "\"${httpdir}/beheer/merken.php\"", 'status' => 'enabled' ),
    MCATEGORIEEN => array('prompt' => "CATEGORIEEN", 'ref' => "\"${httpdir}/beheer/categorieen.php\"", 'status' => 'enabled' ),
    MUSERS => array('prompt' => "GEBRUIKERS", 'ref' => "\"${httpdir}/beheer/users.php\"", 'status' => 'enabled' ),
    MOVERZICHTEN => array('prompt' => "OVERZICHTEN", 'ref' => "\"${httpdir}/beheer/overzichten.php\"", 'status' => 'enabled' ));

function display_beheer_menu($menuoption, $subpage)
{
  global $beheermenu;
  
  if(  $menuoption == MLOGIN || $subpage == SWIJZIGEN )
  {
    $beheermenu[MBEHEER]['status'] = 'disabled';
    $beheermenu[MBONNEN]['status'] = 'disabled';
    $beheermenu[MARTIKELEN]['status'] = 'disabled';
    $beheermenu[MKLANTEN]['status'] = 'disabled';
    $beheermenu[MSYSTEMEN]['status'] = 'disabled';
    $beheermenu[MMERKEN]['status'] = 'disabled';
    $beheermenu[MCATEGORIEEN]['status'] = 'disabled';
    $beheermenu[MUSERS]['status'] = 'disabled';
    $beheermenu[MOVERZICHTEN]['status'] = 'disabled';
  }
  else
  {
    $beheermenu[$menuoption]['status'] = 'active';
  }
  display_menu( $beheermenu );

}
    
?>