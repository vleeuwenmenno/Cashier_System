	var options = 'fullscreen,scrollbars';

function toevoegen_bon_artikel(i,transactie)
{
  var bonid = document.getElementById("bonid").value;  
  var aantal = document.getElementById("aantal"+i).value;  
  var artikelid = document.getElementById("artikelid"+i).value;  
  var demo  = document.getElementById("demo"+i).checked;
  
   if( demo  )
  {
    var demoval = 1;
  }
  else
  {
     var demoval = 0;
  }   
  window.location.replace('../bon/bon.php' + '?submit=Toevoegen&bonid='+bonid+'&artikelid='+artikelid+'&aantal='+aantal+'&transactie='+transactie+'&demo='+demoval); 
  
}

function toevoegen_bon_klant(i,transactie)
{
  var bonid = document.getElementById("bonid").value;  
  var klantid = document.getElementById("klantid"+i).value;  
  
  window.location.replace('../bon/bon.php' + '?submit=Toevoegen&bonid='+bonid+'&klantid='+klantid); 
  
}

function toevoegen_bon_systeem(i)
{
  var bonid = document.getElementById("bonid").value;  
  var systeemid = document.getElementById("systeemid"+i).value;  
  window.location.replace('../bon/bon.php' + '?submit=Toevoegen&bonid='+bonid+'&systeemid='+systeemid); 
}

function nieuwe_bon(i)
{
  if(i==-1)
  { 
    var bonid = -1 
  }
  else
  {
    var bonid = document.getElementById("bonid"+i).value;
  
  }
  window.open('../bon/bon.php?submit=Open&bonid='+bonid,'',options);
}


function nieuwe_bon_artikel(i,transactie)
{
  var aantal = document.getElementById("aantal"+i).value;  
  var artikelid = document.getElementById("artikelid"+i).value;  
  var demo  = document.getElementById("demo"+i).checked;
  
  if( demo  )
  {
    var demoval = 1;
  }
  else
  {
     var demoval = 0;
  }   
  window.open('../bon/bon.php?submit=Open&bonid=-1&artikelid='+artikelid+'&aantal='+aantal+'&transactie='+transactie+'&demo='+demoval,'', options);
}

function nieuwe_bon_systeem(i)
{
  var systeemid = document.getElementById("systeemid"+i).value;
  window.open('../bon/bon.php?submit=Open&bonid=-1&systeemid='+systeemid, '',options );
}

function nieuwe_bon_klant(artikelid,klantid)
{
  //aantalval = document.forms['result'].elements['aantal'+i].value;  
  window.open('../bon/bon.php?submit=Open&bonid=-1&klantid='+klantid, '', options);
}

function wijzigen_klant(i)
{
  var klantid = document.getElementById("klantid"+i).value;  

  //  alert(window.location.href + "?submit=Wijzigen&klantid="+klantid );
  
  window.location.replace(window.location.href + "?submit=Wijzigen&klantid="+klantid); 

}

function wijzigen_bon_item(i)
{
  var itemid = document.getElementById("itemid"+i).value;  
  var bonid = document.getElementById("bonid").value;  

  //  alert(window.location.href + "?submit=Wijzigen&klantid="+klantid );
  
  window.location.replace('../bon/bon.php' + "?submit=Wijzigen&bonid="+bonid+'&itemid='+itemid); 

}

function verwijderen_bon_item(i)
{
  var itemid = document.getElementById("itemid"+i).value;  
  var bonid = document.getElementById("bonid").value;  

  //  alert(window.location.href + "?submit=Wijzigen&klantid="+klantid );
  
  window.location.replace('../bon/bon.php' + '?submit=Verwijderen&bonid='+bonid+'&itemid='+itemid); 

}

function add_artikel(i)
{
   var bonid = document.getElementById("bonid"+i).value;  
   var artikelid = document.getElementById("artikelid"+i).value;  

  //  alert(window.location.href + "?submit=Wijzigen&klantid="+klantid );
  
  window.location.replace(window.location.href + "?submit=Toevoegen&bonid="+bonid); 

}

function verwijderen_bon(i)
{
  var bonid = document.getElementById("bonid"+i).value;  
  
  window.location.replace('../kassa/bonnen.php' + '?submit=Verwijderen&bonid='+bonid); 

}

function verwijderen_bon_beheer(i)
{
  var bonid = document.getElementById("bonid"+i).value;  
  
  window.location.replace('../beheer/bonnen.php' + '?submit=Verwijderen&bonid='+bonid); 

}

function afdrukken_bon()
{
  var bonid = document.getElementById("bonid").value;  
  
  window.location.replace('../bon/bon.php' + '?submit=Afdrukken&bonid='+bonid); 

}

function terugnemen_bon()
{
  var bonid = document.getElementById("bonid").value;  
  
  window.location.replace('../bon/bon.php' + '?submit=Terugnemen&bonid='+bonid); 

}

function openen_bon(i)
{
  var bonid = document.getElementById("bonid"+i).value;  
  
  window.open('../bon/bon.php' + '?submit=Open&bonid='+bonid, '', options); 
}

function opslaan_bon()
{
  var bonid = document.getElementById("bonid").value;  
  
  window.location.replace('../bon/bon.php' + '?submit=OpslaanBon&bonid='+bonid); 
}

function check_betaalwijze()
{
  var betaalwijze = document.getElementById("betaalwijze").value;  

  
  if( betaalwijze == "pin en kontant" )
  {
    document.getElementById("pinrow").style.visibility = "";
    document.getElementById("kontantrow").style.visibility = "";
  }
  else
  {
    document.getElementById("pinrow").style.visibility = "hidden";
    document.getElementById("kontantrow").style.visibility = "hidden";
  }
}

function voorraad_artikel(i,transactie)
{

  var artikelid = document.getElementById("artikelid"+i).value;  
  var aantal = document.getElementById("aantal"+i).value;  
  window.location.replace('../beheer/artikelen.php' + '?submit=Voorraad&artikelid='+artikelid+'&aantal='+aantal+'&transactie='+transactie); 
}

function wijzigen_artikel(i)
{
  var artikelid = document.getElementById("artikelid"+i).value;  
  window.location.replace('../beheer/artikelen.php' + '?submit=Wijzigen&artikelid='+artikelid); 
}

function verwijderen_categorie(i)
{
  var categorieid = document.getElementById("categorieid"+i).value;  

  //  alert(window.location.href + "?submit=Wijzigen&klantid="+klantid );
  
  window.location.replace('../beheer/categorieen.php' + '?submit=Verwijderen&categorieid='+categorieid); 

}

function verwijderen_merk(i)
{
  var merkid = document.getElementById("merkid"+i).value;  

  //  alert(window.location.href + "?submit=Wijzigen&klantid="+klantid );
  
  window.location.replace('../beheer/merken.php' + '?submit=Verwijderen&merkid='+merkid); 

}

function open_systeem(i)
{
  if( i==-1)
  {
    var systeemid = -1;  
  }
  else
  {
    var systeemid = document.getElementById("systeemid"+i).value;  
 
  }
  window.open('../systeem/systeem.php?submit=Open&systeemid='+systeemid, '', options);
}

function toevoegen_systeem_artikel(i)
{
  var systeemid = document.getElementById("systeemid").value;  
  var aantal = document.getElementById("aantal"+i).value;  
  var artikelid = document.getElementById("artikelid"+i).value;  
  
  window.location.replace('../systeem/systeem.php' + '?submit=Toevoegen&systeemid='+systeemid+'&artikelid='+artikelid+'&aantal='+aantal); 
  
}

function verwijderen_systeemitem(i)
{
  var systeemid = document.getElementById("systeemid").value;  
  var systeemitemid = document.getElementById("systeemitemid"+i).value;  
  
  window.location.replace('../systeem/systeem.php' + '?submit=Verwijderen&systeemid='+systeemid+'&systeemitemid='+systeemitemid); 
  
}

function wijzigen_systeemitem(i)
{
  var systeemid = document.getElementById("systeemid").value;  
  var systeemitemid = document.getElementById("systeemitemid"+i).value;  
  
  window.location.replace('../systeem/systeem.php' + '?submit=Wijzigen&systeemid='+systeemid+'&systeemitemid='+systeemitemid); 
  
}

function opslaan_systeemnaam()
{
  var systeemid = document.getElementById("systeemid").value;  
  var naam = document.getElementById("naam").value;  
  
  window.location.replace('../systeem/systeem.php' + '?submit=NaamOpslaan&systeemid='+systeemid+'&naam='+naam); 
  
}

function wijzigen_systeemnaam()
{
  var systeemid = document.getElementById("systeemid").value;  
  window.location.replace('../systeem/systeem.php' + '?submit=NaamWijzigen&systeemid='+systeemid); 
}

function wijzigen_user(i)
{
  var userid = document.getElementById("userid"+i).value;  

  window.location.replace('../beheer/users.php' + "?submit=Wijzigen&userid="+userid); 

}

function verwijderen_user(i)
{
  var userid = document.getElementById("userid"+i).value;  

  window.location.replace('../beheer/users.php' + "?submit=Verwijderen&userid="+userid); 

}

function nieuw_user()
{
  window.location.replace('../beheer/users.php' + "?submit=Nieuw"); 

}

function submitViaEnterBeheer(evt) {
    evt = (evt) ? evt : event;
    var target = (evt.target) ? evt.target : evt.srcElement;
    var form = document.getElementById("queryform");
    var charCode = (evt.charCode) ? evt.charCode :
        ((evt.which) ? evt.which : evt.keyCode);
    if (charCode == 13 || charCode == 3) {
    //alert("AAP");
    var arttype = document.getElementById("type").value;  
    var categorie = document.getElementById("categorie").value;  
    var merk = document.getElementById("merk").value;  
    var eol = document.getElementById("eol").value;  
    window.location.replace('../beheer/artikelen.php' + '?submit=Zoeken&type='+arttype+'&categorie='+categorie+'&merk='+merk+'&eol='+eol); 
     return false;
   }
   return true;
}

function submitViaEnter(evt, moduletype) {
    evt = (evt) ? evt : event;
    var target = (evt.target) ? evt.target : evt.srcElement;
    var form = document.getElementById("queryform");
    var charCode = (evt.charCode) ? evt.charCode :
        ((evt.which) ? evt.which : evt.keyCode);
    if (charCode == 13 || charCode == 3) {
    //alert("AAP");
    var arttype = document.getElementById("type").value;  
    var categorie = document.getElementById("categorie").value;  
    var merk = document.getElementById("merk").value;  
    var eol = document.getElementById("eol").value;  

    if( moduletype == 0 ) 
    {
      window.location.replace('../kassa/artikelen.php' + '?submit=Zoeken&type='+arttype+'&categorie='+categorie+'&merk='+merk+'&eol='+eol); 
    }
    else if ( moduletype == 1 )
    {      
       window.location.replace('../beheer/artikelen.php' + '?submit=Zoeken&type='+arttype+'&categorie='+categorie+'&merk='+merk+'&eol='+eol); 
    } 
    else if ( moduletype == 2 )
    {
      var bonid = document.getElementById("bonid").value;  
      window.location.replace('../bon/artikelen.php' + '?submit=Zoeken&bonid='+bonid+'&type='+arttype+'&categorie='+categorie+'&merk='+merk+'&eol='+eol); 
    }    

    return false;

   }
   return true;
}

function get_marge(inputid) 
{
    var inkoop = CF(document.getElementById("inkoop").value);  
    var marge = parseInt(document.getElementById("marge").value );  
    var prijs = CF(document.getElementById("prijs").value); 
    //nm var inkoopbtw = .19 * inkoop;
    //nm var prijsexcl = prijs / 1.19;
    //nm var prijsbtw = prijs - prijsexcl;

    //alert( inkoop );
    if( inputid == 0 || inputid == 1) 
    {
      //alert( marge);
      //prijs = (inkoop + inkoop*marge/100)*1.19;
      //nm var tmpprijs = ((inkoopbtw + prijsexcl - inkoop - prijsbtw)/(inkoopbtw + prijsexcl)
      var tmpprijs = CF(((100 + marge)/100)*inkoop * 1.19);
      document.getElementById("prijs").value = tmpprijs;
      //document.getElementById("inkoop").value = inkoop;
    }
    else if ( inputid == 2 )
    {      
      var tmpmarge = parseInt(100*((prijs / 1.19 - inkoop)/inkoop)+ .5);
      //var inbedrag = .19*inkoop + prijs/1.19;
      //var uitbedrag = inkoop + prijs * (1 - 1/1.19);
      //var tmpmarge = parseInt(100*((inbedrag - uitbedrag)/inbedrag));
      document.getElementById("marge").value = tmpmarge;
      //document.getElementById("verkoop").value = verkoop;
    } 
   return true;
}

function CF(amount)
{
	var i = parseFloat(amount);
	if(isNaN(i)) { i = 0.00; }
	var minus = '';
	if(i < 0) { minus = '-'; }
	i = Math.abs(i);
	i = parseInt((i + .005) * 100);
	i = i / 100;
	s = new String(i);
	if(s.indexOf('.') < 0) { s += '.00'; }
	if(s.indexOf('.') == (s.length - 2)) { s += '0'; }
	s = minus + s;
	return s;
}
//CF format