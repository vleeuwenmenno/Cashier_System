<?php

function print_voorraad($fname)
{
  $handle = fopen($fname, 'w' );
  $dbserver = dbconnect();
  $query = "select *  from  artikel where eol = 'Nee' and categorie <> 'Arbeidsloon' order by categorie, merk";
  $dbserver = dbconnect();
  $result = dbquery($query);
  if( mysql_num_rows($result) == 0 )
  {
    fwrite($handle, "Geen artikelen in database aanwezig!"); 
  }
  else
  {
    $totaal = 0;
    fwrite($handle, "Overzicht artikelen (niet eol) voor balansen op ".date('d-m-Y', time())."\r\n\r\n"); 
    while ($row = mysql_fetch_object($result)) 
    { 
      if( $row->voorraad > 0 || $row->demo > 0)
      {
         $totaal = $totaal + ($row->voorraad * $row->inkoop ) + ($row->demo * $row->inkoop );
      } 
      display_voorraad_row($handle, $row);
    }
    mysql_free_result($result);
    dbclose($dbserver); 
  }

  fwrite($handle, "\r\n");
  fwrite($handle, "\r\n");
  fwrite($handle, "Totaal inkoop bedrag artikelen op voorraad: "." \x80".str_pad(get_moneystr($totaal), 10, " ", STR_PAD_LEFT));

  fclose($handle);
}

function display_voorraad_row($handle, $row)
{
  fwrite($handle, substr(str_pad($row->categorie, 15),0,10));
  //fwrite($handle, substr(str_pad($row->categorie, 15),0,2));
  fwrite($handle, " ".substr(str_pad($row->merk,15),0,11));
  //fwrite($handle, " ".substr(str_pad($row->merk,7),0,3));
  fwrite($handle, " ".substr(str_pad(stripslashes($row->type),25),0,24));
  //fwrite($handle, " ".substr(str_pad(stripslashes($row->type),50),0,49));
  fwrite($handle, " ".substr(str_pad($row->voorraad,4),0,3));
  fwrite($handle, " ".substr(str_pad($row->demo,4),0,3));
  fwrite($handle, " \x80".str_pad(get_moneystr($row->inkoop), 7, " ", STR_PAD_LEFT));
  fwrite($handle, " \x80".str_pad(get_moneystr($row->prijs), 7, " ", STR_PAD_LEFT));
  fwrite($handle, " ".str_pad($row->marge."%", 4, " ", STR_PAD_LEFT));
  fwrite($handle, "\r\n");
}

function print_mutaties($fname)
{
  global $begindag, $beginmaand, $beginjaar;
  global $einddag, $eindmaand, $eindjaar;

  $begindatum = get_datum( $begindag, $beginmaand, $beginjaar);
  $einddatum = get_datum( $einddag, $eindmaand, $eindjaar );

  $handle = fopen($fname, 'w' );
  $dbserver = dbconnect();
  $query = "select artikel.categorie as categorie,
                   artikel.merk as merk,
                   artikel.type as type,
                   voorraadlog.inkoop as inkoop,
                   voorraadlog.aantal as aantal,
                   voorraadlog.prijs as prijs,
                   voorraadlog.transactie as transactie,
                   voorraadlog.totaal as totaal 
            from voorraadlog, artikel where 
                   artikel.id = voorraadlog.artikelid and
                   artikel.categorie <> 'arbeidsloon' and
                   voorraadlog.transactie = 'voorraad' and
                   voorraadlog.datum >= '$begindatum' and voorraadlog.datum <= '$einddatum' 
                   order by artikel.categorie, artikel.merk";
  $dbserver = dbconnect();
  $result = dbquery($query);
  if( mysql_num_rows($result) == 0 )
  {
    fwrite($handle, "Geen voorraadmutaties in deze periode!"); 
  }
  else
  {
    fwrite($handle, "Overzicht opgevoerde voorraad in periode van ".date('d-m-Y', strtotime($begindatum))." tot en met ".date('d-m-Y', strtotime($einddatum))."\r\n\r\n"); 
    while ($row = mysql_fetch_object($result)) 
    { 
      display_mutaties_row($handle, $row);
    }
    mysql_free_result($result);
    dbclose($dbserver); 
  }
  fclose($handle);
}

function display_mutaties_row($handle, $row)
{
  fwrite($handle, substr(str_pad($row->categorie, 16),0,15));
  fwrite($handle, " ".substr(str_pad($row->merk,10),0,9));
  fwrite($handle, " ".substr(str_pad(stripslashes($row->type),20),0,19));
  fwrite($handle, " ".substr(str_pad($row->aantal,5),0,4));
  //fwrite($handle, " ".substr(str_pad($row->transactie,13),0,12));
  //fwrite($handle, " ".substr(str_pad($row->demo,5),0,4));
  fwrite($handle, " \x80".str_pad(get_moneystr($row->inkoop), 8, " ", STR_PAD_LEFT));
  fwrite($handle, " \x80".str_pad(get_moneystr($row->prijs), 8, " ", STR_PAD_LEFT));
  fwrite($handle, " \x80".str_pad(get_moneystr($row->totaal), 8, " ", STR_PAD_LEFT));
  fwrite($handle, "\r\n");
}

function print_verkoop($fname)
{
  global $begindag, $beginmaand, $beginjaar;
  global $einddag, $eindmaand, $eindjaar;

  $begindatum = get_datum( $begindag, $beginmaand, $beginjaar);
  $einddatum = get_datum( $einddag, $eindmaand, $eindjaar );

  $handle = fopen($fname, 'w' );
  $dbserver = dbconnect();
  $query = "select artikel.categorie as categorie,
                   artikel.merk as merk,
                   artikel.type as type,
                   artikel.inkoop as inkoopprijs,
                   item.transactie as transactie,
                   sum(item.aantal) as aantal,
                   sum(item.totaal) as totaal 
            from bon, item, artikel where 
                   bon.datum >= '$begindatum' and bon.datum <= '$einddatum' 
                   and bon.id = item.bonid and item.artikelid = artikel.id 
                   and bon.status = 'betaald'
                   group by artikel.id, transactie
                   order by artikel.categorie, artikel.merk";
  $dbserver = dbconnect();
  $result = dbquery($query);
  if( mysql_num_rows($result) == 0 )
  {
    fwrite($handle, "Geen verkochte artikelen in deze periode!"); 
  }
  else
  {
    fwrite($handle, "Overzicht verkochte artikelen in periode van ".date('d-m-Y', strtotime($begindatum))." tot en met ".date('d-m-Y', strtotime($einddatum))."\r\n\r\n"); 
    while ($row = mysql_fetch_object($result)) 
    { 
      display_verkoop_row($handle, $row);
    }
    mysql_free_result($result);
    dbclose($dbserver); 
  }
  fclose($handle);
}

function display_verkoop_row($handle, $row)
{
  fwrite($handle, substr(str_pad($row->categorie, 18),0,12));
  fwrite($handle, " ".substr(str_pad($row->merk,15),0,13));
  fwrite($handle, " ".substr(str_pad(stripslashes($row->type),30),0,26));
  fwrite($handle, str_pad($row->aantal,4, " ", STR_PAD_LEFT)." x");
  fwrite($handle, " ".substr(str_pad($row->transactie,9),0,3));
  fwrite($handle, " \x80".str_pad(get_moneystr($row->totaal), 9, " ", STR_PAD_LEFT));
  $marge = get_marge($row->totaal, $row->aantal * $row->inkoopprijs);
  fwrite($handle, " ".str_pad($marge."%", 4, " ", STR_PAD_LEFT));
  fwrite($handle, "\r\n");
}

function print_omzet($fname)
{
  global $begindag, $beginmaand, $beginjaar;
  global $einddag, $eindmaand, $eindjaar;

  $begindatum = get_datum( $begindag, $beginmaand, $beginjaar);
  $einddatum = get_datum( $einddag, $eindmaand, $eindjaar );

  $handle = fopen($fname, 'w' );
  $dbserver = dbconnect();
  $query = "select sum(bon.totaal) as totaal, 
                   sum(bon.rekening) as rekening,
                   sum(bon.pin) as pin,
                   sum(bon.kontant) as kontant 
                   from bon where 
                   bon.datum >= '$begindatum' and bon.datum <= '$einddatum' 
                   and bon.status = 'betaald'";
  $result = dbquery($query);
  $row = mysql_fetch_object($result);
  $pin=$row->pin;
  $totaal=$row->totaal;
  $rekening=$row->rekening;
  $kontant=$row->kontant;


  $query = "select sum(kassalog.kasverschil) as kasverschil
            from kassalog where 
                   kassalog.status = 2 and
                   kassalog.datum >= '$begindatum' and kassalog.datum <= '$einddatum'";
  $result = dbquery($query);
  $row = mysql_fetch_object($result);

  //$totaal = $row->oprekening+$row->pinbon+$row->kontant;
  fwrite($handle, "Overzicht kassaomzet en opgevoerde voorraad in periode ".date('d-m-Y', strtotime($begindatum))." - ".date('d-m-Y', strtotime($einddatum))."\r\n\r\n"); 
  fwrite($handle, str_pad("Kontant:",30)."\x80 ".str_pad(get_moneystr($kontant),10," ",STR_PAD_LEFT)."\r\n");
  fwrite($handle, str_pad("Pin:",30)."\x80 ".str_pad(get_moneystr($pin),10," ",STR_PAD_LEFT)."\r\n");
  fwrite($handle, str_pad("Op rekening:",30)."\x80 ".str_pad(get_moneystr($rekening),10," ",STR_PAD_LEFT)."\r\n");
  fwrite($handle, str_pad("Totaal",30)."\x80 ".str_pad(get_moneystr($totaal),10," ",STR_PAD_LEFT)."\r\n\r\n");
  fwrite($handle, str_pad("Kasverschil:",30)."\x80 ".str_pad(get_moneystr($row->kasverschil),10," ",STR_PAD_LEFT)."\r\n\r\n");
  mysql_free_result( $result);

  $query = "select sum(voorraadlog.totaal) as totaal 
            from voorraadlog where 
                   voorraadlog.transactie = 'voorraad' and
                   voorraadlog.datum >= '$begindatum' and voorraadlog.datum <= '$einddatum'";
  $result = dbquery($query);
  $row = mysql_fetch_object($result);
  
  fwrite($handle, str_pad("Opgevoerde voorraad (ex BTW)",30)."\x80 ".str_pad(get_moneystr($row->totaal),10," ",STR_PAD_LEFT)."\r\n");
  mysql_free_result( $result);
  dbclose($dbserver);
  fclose($handle);
}

function nieuwe_print_overzicht($sluiting, $fname )
{
  $opening = new KassaStatus;
  $opening->set_opening( $sluiting );
  $modulenaam = get_modulenaam( $sluiting->moduleid );

  $omzetpin = get_omzet($opening->datum, $opening->tijd, $sluiting->datum, $sluiting->tijd, PIN);
  $omzetkontant = get_omzet($opening->datum, $opening->tijd, $sluiting->datum, $sluiting->tijd, KONTANT);	
  $omzetrekening = get_omzet($opening->datum, $opening->tijd, $sluiting->datum, $sluiting->tijd, REKENING);	
  $omzettotaal = get_omzet($opening->datum, $opening->tijd, $sluiting->datum, $sluiting->tijd, TOTAAL);	

  $inkomsten = $sluiting->pinbon + $sluiting->kasgeld - $sluiting->kasin + $sluiting->oprekening;

  $dbserver = dbconnect();
  $query = "select sum(artikel.inkoop * item.aantal) as totaalinkoop
            from item, bon, artikel where 
                   item.bonid=bon.id and item.artikelid = artikel.id and bon.status = 'betaald' and
                   ((bon.datum = '$sluiting->datum' and bon.tijd < '$sluiting->tijd') or bon.datum < '$sluiting->datum')
                   and ((bon.datum = '$opening->datum' and bon.tijd > '$opening->tijd') or bon.datum > '$opening->datum' )";
                   
  $result = dbquery($query);
  $row = mysql_fetch_object( $result );
  $totaalinkoop = $row->totaalinkoop;

  $handle = fopen($fname, 'w' );
  //fwrite($handle, str_pad("-",80,"-", STR_PAD_BOTH)."\r\n\r\n");
  fwrite($handle, str_pad("Dagoverzicht van $modulenaam op $sluiting->datum", 14)."\r\n\r\n");

  fwrite($handle, str_pad("Geopend: ".date('d-m-Y', strtotime($opening->datum))." $opening->tijd", 14)."\r\n");
  fwrite($handle, str_pad("Medewerker: ".ucfirst($opening->usernaam), 14)."\r\n");
  fwrite($handle, "\r\n");
  fwrite($handle, str_pad("Gesloten: ".date('d-m-Y',strtotime($sluiting->datum))." $sluiting->tijd", 14)."\r\n");
  fwrite($handle, str_pad("Medewerker: ".ucfirst($sluiting->usernaam), 14)."\r\n");
  fwrite($handle, "\r\n");
  fwrite($handle, str_pad("Kasgeld:",20)."\x80 ".str_pad(get_moneystr($sluiting->kasgeld), 10," ",STR_PAD_LEFT)."\r\n");
  fwrite($handle, str_pad("Kas in:",20). "\x80 ".str_pad(get_moneystr($sluiting->kasin),10," ",STR_PAD_LEFT)."\r\n");
  fwrite($handle, str_pad("Afromen:",20)."\x80 ".str_pad(get_moneystr($sluiting->afromen),10," ",STR_PAD_LEFT)."\r\n");
  fwrite($handle, str_pad("Kasuit:",20)."\x80 ".str_pad(get_moneystr($sluiting->kasuit),10," ",STR_PAD_LEFT)."\r\n");
  fwrite($handle, "\r\n");
  fwrite($handle, str_pad("Totale inkomsten:",20)."\x80 ".str_pad(get_moneystr($inkomsten),10," ",STR_PAD_LEFT)."\r\n");
  fwrite($handle, str_pad("Totale omzet:",20)."\x80 ".str_pad(get_moneystr($omzettotaal),10," ",STR_PAD_LEFT)."\r\n");
  fwrite($handle, str_pad("Verschil:",20)."\x80 ".str_pad(get_moneystr($inkomsten-$omzettotaal),10," ",STR_PAD_LEFT)."\r\n");
  fwrite($handle, "\r\n");
  fwrite($handle, str_pad("Kasgeld - Kas in:",20)."\x80 ".str_pad(get_moneystr($sluiting->kasgeld - $opening->kasin),10," ",STR_PAD_LEFT)."\r\n");
  fwrite($handle, str_pad("Omzet kontant:",20)."\x80 ".str_pad(get_moneystr($omzetkontant),10," ",STR_PAD_LEFT)."\r\n");
  fwrite($handle, str_pad("Kas verschil:",20)."\x80 ".str_pad(get_moneystr($sluiting->kasgeld - $sluiting->kasin - $omzetkontant),10," ",STR_PAD_LEFT)."\r\n");
  fwrite($handle, "\r\n");
  fwrite($handle, str_pad("Pinbon:",20)."\x80 ".str_pad(get_moneystr($sluiting->pinbon),10," ",STR_PAD_LEFT)."\r\n");
  fwrite($handle, str_pad("Omzet pint:",20)."\x80 ".str_pad(get_moneystr($omzetpin),10," ",STR_PAD_LEFT)."\r\n");
  fwrite($handle, str_pad("Pin verschil:",20)."\x80 ".str_pad(get_moneystr($sluiting->pinbon - $omzetpin),10," ",STR_PAD_LEFT)."\r\n");
  fwrite($handle, "\r\n");
  fwrite($handle, str_pad("Op rekening:",20)."\x80 ".str_pad(get_moneystr($sluiting->oprekening),10," ",STR_PAD_LEFT)."\r\n");
  fwrite($handle, str_pad("Omzet rekening:",20)."\x80 ".str_pad(get_moneystr($omzetrekening),10," ",STR_PAD_LEFT)."\r\n");
  fwrite($handle, str_pad("Rekening verschil:",20)."\x80 ".str_pad(get_moneystr($sluiting->oprekening - $omzetrekening),10," ",STR_PAD_LEFT)."\r\n");
  fwrite($handle, "\r\n");
  fwrite($handle, str_pad("Totaal Omzet",20)."\x80 ".str_pad(get_moneystr($omzettotaal),10," ",STR_PAD_LEFT)."\r\n");
  $omzettotaalexbtw = get_exbtw($omzettotaal);
  fwrite($handle, str_pad("Totaal Omzet ex BTW",20)."\x80 ".str_pad(get_moneystr($omzettotaalexbtw),10," ",STR_PAD_LEFT)."\r\n");
  fwrite($handle, str_pad("Totaal Inkoop",20)."\x80 ".str_pad(get_moneystr($totaalinkoop),10," ",STR_PAD_LEFT)."\r\n");
  $nettowinst = get_nettowinst($omzettotaal, $totaalinkoop);
  fwrite($handle, str_pad("Netto Winst",20)."\x80 ".str_pad(get_moneystr($nettowinst),10," ",STR_PAD_LEFT)."\r\n");
  //fwrite($handle, "\xc");
  fwrite($handle, "\r\n\r\n");
  //$dbserver = dbconnect();
  $query = "select item.categorie as categorie,
                   item.merk as merk,
                   item.type as type,
                   artikel.inkoop as inkoop,
                   sum(item.aantal) as aantal,
                   item.transactie as transactie,
                   sum(item.totaal) as totaal
            from item, bon, artikel where 
                   item.bonid=bon.id and item.artikelid = artikel.id and bon.status = 'betaald' and
                   ((bon.datum = '$sluiting->datum' and bon.tijd < '$sluiting->tijd') or bon.datum < '$sluiting->datum')
                   and ((bon.datum = '$opening->datum' and bon.tijd > '$opening->tijd') or bon.datum > '$opening->datum' )
                   group by categorie, merk, type, transactie
                   order by item.categorie, item.merk, item.type"; 
  $result = dbquery($query);
  $aantalres = mysql_num_rows( $result );

  if( $aantalres == 0 )
  {
    mysql_free_result( $result);
    dbclose($dbserver);
    fwrite($handle, "\r\n\r\nGeen artikelen verkocht\r\n");
  }
  else
  {
    while ($item = mysql_fetch_object($result)) 
    {
      print_overzicht_artikel($handle, $item);
    }  
    mysql_free_result( $result);
    dbclose($dbserver);
  }
  fclose($handle);
}

function nieuwe_print_opening($opening, $fname )
{
  $modulenaam = get_modulenaam( $opening->moduleid );
  
  $handle = fopen($fname, 'w' );
  fwrite($handle, str_pad("$modulenaam geopend op ".date('d-m-Y',strtotime($opening->datum))." om $opening->tijd", 14)."\r\n\r\n");
  fwrite($handle, str_pad("Medewerker: ".ucfirst($opening->usernaam), 14)."\r\n");
  fwrite($handle, "Kas in: ". "\x80 ".get_moneystr($opening->kasin)."\r\n");
  fclose($handle);
  
}
function nieuwe_print_bon($bonid, $fname, $media)
{
  $bon = get_bon($bonid);
  $handle = fopen($fname, 'w+' );
  if( $media == 'printer')
  {
  	for( $i=0;$i<12;$i++ )
  	{
    	fwrite($handle,"\r\n"); 
    }
  }
  //fwrite($handle, str_pad("-", 80, "-", STR_PAD_BOTH)."\r\n");
  fwrite($handle, str_pad("Bonnr:", 14) . $bon->id . "\r\n");
  fwrite($handle, str_pad("Datum:", 14) . date('d-m-Y',strtotime($bon->datum)) . "\r\n");
  fwrite($handle, str_pad("Tijd:",14) . $bon->tijd . "\r\n");
  fwrite($handle, str_pad("Kassa:",14) . get_modulenaam($bon->moduleid) . "\r\n");
  fwrite($handle, str_pad("Medewerker:",14) . ucfirst(get_usernaam($bon->userid)) . "\r\n");
  fwrite($handle, str_pad("Betaalwijze:",14) . $bon->betaalwijze . "\r\n");

  if( $bon->klantid != -1 )
  {
     $klant = get_klant( $bon->klantid );
     fwrite($handle, "\r\n");
     if( $klant->bedrijfsnaam != "" )
     {
       fwrite($handle, str_pad("Bedrijfsnaam:",14) . $klant->bedrijfsnaam . "\r\n");
       if( $klant->achternaam != "" )
       {
 	      fwrite($handle, str_pad("Contactpersoon:", 14) . $klant->achternaam . "\r\n");
 
       }
     }
     else
     {
       fwrite($handle, str_pad("Naam:", 14) . $klant->achternaam . "\r\n");
     }
     fwrite($handle, str_pad("Adres:",14) . $klant->straat." ".$klant->huisnr . "\r\n");
     fwrite($handle, str_pad("Postcode:",14) . $klant->postcode . "\r\n");
     fwrite($handle, str_pad("Woonplaats:",14) . $klant->woonplaats . "\r\n");
  }

  if( $bon->naam != "" )
  {
     fwrite($handle, "\r\n".str_pad($bon->naam,80, " ", STR_PAD_BOTH)."\r\n");
  }

  $dbserver = dbconnect();
  $query = "SELECT * from item where bonid=$bon->id"; 
  $result = dbquery($query);
  $aantalres = mysql_num_rows( $result );
  if( $aantalres == 0 )
  {
    mysql_free_result( $result);
    dbclose($dbserver);
    fwrite($handle, "\r\nGeen artikelen geselecteerd!!!!!!!\r\n");
  }
  else
  { 
    fwrite($handle, "\r\n");
    while ($item = mysql_fetch_object($result)) 
    {
      print_bon_item($handle, $item);
    }  
    mysql_free_result( $result);
    dbclose($dbserver);

    $exbtw = floor($bon->totaal/BTW + 0.5);
    $btw = $bon->totaal - $exbtw;
    //display_money($exbtw);
    fwrite($handle, "\r\n\r\n");
    fwrite($handle, str_pad("Ex. BTW:", 68, " ", STR_PAD_LEFT));
    fwrite($handle, " \x80".str_pad(get_moneystr($exbtw), 9, " ", STR_PAD_LEFT));
    fwrite($handle, "\r\n\r\n");
    fwrite($handle, str_pad("BTW 19%:", 68, " ", STR_PAD_LEFT));
    fwrite($handle, " \x80".str_pad(get_moneystr($btw), 9, " ", STR_PAD_LEFT));
    if( $bon->betaalwijze == "pin en kontant" )
    {
      fwrite($handle, "\r\n\r\n");
      fwrite($handle, str_pad("Pin:", 27, " ", STR_PAD_LEFT));
      fwrite($handle, " \x80".str_pad(get_moneystr($bon->pin), 8, " ", STR_PAD_LEFT));
      fwrite($handle, str_pad("Kontant:", 10, " ", STR_PAD_LEFT));
      fwrite($handle, " \x80".str_pad(get_moneystr($bon->kontant), 8, " ", STR_PAD_LEFT));
      fwrite($handle, str_pad("Totaal:", 10, " ", STR_PAD_LEFT));
      fwrite($handle, " \x80".str_pad(get_moneystr( $bon->totaal ), 9, " ", STR_PAD_LEFT));
    }
    else
    {
      fwrite($handle, "\r\n\r\n");
      fwrite($handle, str_pad("Totaal:", 68, " ", STR_PAD_LEFT));
      fwrite($handle, " \x80".str_pad(get_moneystr( $bon->totaal ), 9, " ", STR_PAD_LEFT));
    }
  }
  //fwrite($handle, "\x1a");  
  fclose($handle);
}
  
function  delete_print_file($fname)
{
  unlink( $fname );
}

function print_bon_item($handle, $item )
{
   $tmp= $item->aantal . " x";
   fwrite($handle, str_pad($tmp, 4, " ", STR_PAD_LEFT)); 
   fwrite($handle, " ".substr(str_pad($item->categorie, 15),0,14));
   fwrite($handle, " ".substr(str_pad($item->merk,10),0,9));
   fwrite($handle, " ".substr(str_pad(stripslashes($item->type),40),0,38)." ");
   fwrite($handle, "\x80".str_pad(get_moneystr($item->totaal), 9, " ", STR_PAD_LEFT));
   fwrite($handle, "\r\n");
}

function print_overzicht_artikel($handle, $item )
{
   fwrite($handle, str_pad($item->aantal,4, " ", STR_PAD_LEFT)." x");
   fwrite($handle, " ".substr(str_pad($item->transactie,9),0,3));
   fwrite($handle, " ".substr(str_pad($item->categorie, 10),0,9) );
   fwrite($handle, " ".substr(str_pad($item->merk,13),0,12) );
   fwrite($handle, " ".substr(str_pad(stripslashes($item->type),30),0,25));
   fwrite($handle, " \x80".str_pad(get_moneystr($item->totaal), 10, " ", STR_PAD_LEFT));
   $marge = get_marge($item->totaal, $item->aantal * $item->inkoop);
   fwrite($handle, " ".str_pad($marge."%", 4, " ", STR_PAD_LEFT));
   fwrite($handle, "\r\n");
}

function copy_to_spool($fname, $aantal)
{
        global $rootdirwin;
        $base = basename($fname);
	for($i=1;$i<$aantal+1;$i++)
        {
          $command = "copy $rootdirwin\\tmp\\$base c:\\print\\spool\\${base}_$i";
 	        exec($command);
	}
}

function copy_to_backup($fname)
{
        global $rootdirwin;
        $base = basename($fname);
        $command = "copy $rootdirwin\\tmp\\$base $rootdirwin\\print\\$base";
        exec($command);
}

function get_print_filename($prefix)
{
  global $rootdir;
  return "$rootdir/tmp/".$prefix.date('YmdHis', time());
}

function get_print_filename_nodate($fname)
{
  global $rootdir;
  return "$rootdir/tmp/".$fname;
}
?>