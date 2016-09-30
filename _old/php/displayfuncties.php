<?php 

function display_header($title = "")
{
  global $httpdir;
  global $module;	
  header( "Cache-Control: no-cache, must-revalidate" );
  header( "Pragma: no-cache" );
  echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">', NL;
  echo '<html xmlns="http://www.w3.org/1999/xhtml">', NL;
  echo '<head>', NL;
  if( isset($module) )
  {
    echo "  <title>$module->modulenaam - $module->usernaam $title</title>", "\n";
  }
  else
  {
    echo "  <title>$title</title>", "\n";

  }
  echo '  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">', "\n";
  echo '  <link rel="stylesheet" type="text/CSS" href="', "${httpdir}", '/comtoday.css">', "\n";
  echo '<style type="text/JavaScript">window.resizeTo(width,height);</style>';
  echo '</head>', "\n";
}

function display_menu($menu)
{
  echo '<div class="menubox">', "\n";
  echo '  <ul class="menulist">', "\n";
  foreach( $menu as $item )
  {
    if( $item['status'] == 'disabled' )
    {
      echo '    <li id="disabled">', $item['prompt'], "</li>", NL;
    }
    elseif( $item['status'] == 'enabled' )
    {
      echo '    <li >', "<a id=enabled href=",$item['ref'],">", $item['prompt'], '</a></li>', NL;
    }
    else
    {
      echo '    <li >', "<a id=active href=", $item['ref'], ">", $item['prompt'], '</a></li>', NL;
    }
    
  }
  echo '  </ul>', "\n";
  echo '</div>', "\n";
}

function display_sluiten()
{ 
  echo '<html><body><SCRIPT LANGUAGE="JAVASCRIPT">window.close()</SCRIPT></body></html>';
}

function display_select_merk($value, $i)
{ 
  if( $i != -1 )
  { echo "<SELECT class=textval NAME=\"merk\" id=\"merk\" value=\"$value\" onkeypress=\"return submitViaEnter(event, $i)\">"; }
  else
  { echo "<SELECT class=textval NAME=\"merk\" id=\"merk\" value=\"$value\">"; }
 
  $dbserver = dbconnect();
  $query = "SELECT * from merk order by naam";

  $result = dbquery($query);
  while ($row = mysql_fetch_array($result)) {
    if( $row['naam'] == $value )
    { 
      echo '<OPTION VALUE = "'. $row['naam']. '" selected>'.$row['naam'].'</OPTION>';   
    }
    else
    {
      echo '<OPTION VALUE = "'.$row['naam'].'">'.$row['naam'].'</OPTION>';   
    }
  }
  
  mysql_free_result( $result );
  dbclose($dbserver);
  echo '</SELECT>';
  
}

function display_select_categorie($value, $i)
{
  if( $i != -1 )
  { echo "<SELECT class=textval NAME=\"categorie\" id=\"categorie\" value=\"$value\" onkeypress=\"return submitViaEnter(event, $i)\">"; }
  else
  { echo "<SELECT class=textval NAME=\"categorie\" id=\"categorie\" value=\"$value\" >"; }
  $dbserver = dbconnect();
  $query = "SELECT * from categorie order by naam"; 

  $result = dbquery($query);
  while ($row = mysql_fetch_array($result)) 
  {
    if( $row['naam'] == $value )
    {
      echo '<OPTION VALUE = "'.$row['naam'].'" selected>'.$row['naam'].'</OPTION>';   
    }
    else
    {
       echo '<OPTION VALUE = "'.$row['naam'].'">'.$row['naam'].'</OPTION>';   
    }
  }
  
  mysql_free_result( $result );
  dbclose($dbserver);
  echo '</SELECT>';
  
}

function display_select_toggle( $name, $value)
{
  echo '<select class=textval id="' . $name . '"' . 'name="' . $name . '" value="' . $value . "\" >";
  if( $value == 'Ja' )
  {
     echo '<option value=""></option>';   
     echo '<option value="Ja" selected>Ja</option>';   
     echo '<option value="Nee">Nee</option>';   
  }
  elseif( $value == 'Nee' )
  {
     echo '<option value=""></option>';   
     echo '<option value="Ja">Ja</option>';   
     echo '<option value="Nee" selected>Nee</option>';   
  }
  else
  {
     echo '<option value="" selected></option>';   
   	 echo '<option value="Ja">Ja</option>';   
     echo '<option value="Nee">Nee</option>';   
  }
  echo '</select>';
   
}

function display_select_toggle_query( $name, $value, $i )
{
  if( $i != -1 )
  { echo '<select class=textval name="' . $name . '" value="' . $value . "\" onkeypress=\"return submitViaEnter(event, $i)\">"; }
  else
  { echo '<select class=textval name="' . $name . '" value="' . $value . "\" >"; }

  if( $value == 'Ja' )
  {
     echo '<option value=""></option>';   
     echo '<option value="Ja" selected>Ja</option>';   
     echo '<option value="Nee">Nee</option>';   
  }
  elseif( $value == 'Nee' )
  {
     echo '<option value=""></option>';   
     echo '<option value="Ja">Ja</option>';   
     echo '<option value="Nee" selected>Nee</option>';   
  }
  else
  {
     echo '<option value="" selected></option>';   
     echo '<option value="Ja">Ja</option>';   
     echo '<option value="Nee">Nee</option>';   
  }
 
  echo '</select>';
   
}

function display_money($value)
{
  $valuestr=get_moneystr($value);
  echo "<TD>&#8364</TD><TD class=numval>$valuestr</TD>";
}
  
function display_empty_row($aantal)
{
  for($i=0;$i<$aantal;$i++)
  {
     echo "<TR><TD></TD></TR>";
  }
}

function display_empty_col($aantal)
{
  for($i=0;$i<$aantal;$i++)
  {
     echo "<TD></TD>";
  }
}

function display_select_betaalwijze($value)// aanpassen!
{
  echo "<SELECT NAME=\"betaalwijze\" id=betaalwijze value=\"$value\" onChange=\"check_betaalwijze()\">";

  $dbserver = dbconnect();
  $query = "SELECT * from betaalwijze"; 

  $result = dbquery($query);
  while ($row = mysql_fetch_array($result)) 
  {
    if( $row['naam'] == $value )
    {
      echo '<OPTION VALUE = "'.$row['naam'].'" selected>'.$row['naam'].'</OPTION>';   
    }
    else
    {
       echo '<OPTION VALUE = "'.$row['naam'].'">'.$row['naam'].'</OPTION>';   
    }
  }
  
  mysql_free_result( $result );
  dbclose($dbserver);
  echo '</SELECT>';
  
  
}

function display_stylesheet($moduletype="")
{
  global $module;

  if( $moduletype != "" )
  {
    $type = $moduletype;
  }
  else
  {
    $type = $module->moduletype;
  }

  if( $type == 'Beheer' )
  {
    echo '<STYLE type="text/CSS">';
    echo 'html { background-color: rgb(255,255,200);}';
    echo 'body { background-color: rgb(255,255,200);}';
    echo '.mainbox { background-color: rgb(255,255,210);}';
    echo '.resultbox { background-color: rgb(255,255,210);}';
 
    echo '.querybox { background-color: rgb(255,255,200);}';
    echo '.menubox { background-color: rgb(255,255,200);}';
    echo 'body {  background-color: rgb(255,255,200);}';
    echo '</STYLE>';
  }
  else
  { 
    if( isset($module) )
    {
      $kassastatus = new KassaStatus();
      $kassastatus->update();
      if( $kassastatus->status != GEOPEND )
      {
        echo '<STYLE type="text/CSS">';
        echo '.mainbox { background-color: rgb(255,240,240);}';
        echo '.resultbox { background-color: rgb(255,240,240);}';
 
        echo '.querybox { background-color: rgb(255,230,230);}';
        echo '.menubox { background-color: rgb(255,230,230);}';
        echo 'body {  background-color: rgb(255,230,230);}';
        echo '</STYLE>';  
      }
    }
  }
  
}
function display_moneyval($numval)
{
  $numvalstr = get_moneystr($numval);
  echo "<TD>&#8364</TD><TD class=numval>$numvalstr</TD>";
}

function display_numval($numval)
{
  echo "<TD class=numval>$numval</TD>";
}

function display_textval($textval)
{
  $numvalstr = get_moneystr($textval);
  echo "<TD class=textval>$textval</TD>";
}


function display_select_dag($name, $value)
{
  echo "<SELECT class=textval NAME=\"$name\" value=\"\">";

  if( $value == "" )
  { 
    echo '<OPTION VALUE = ""></OPTION>';   
  }
  else
  {
     echo '<OPTION VALUE = "" selected></OPTION>';   
  } 
  
  for($i=1;$i < 32;$i++)
  {
     if( $value == $i )
     {
       echo '<OPTION VALUE = '. $i . ' selected>'.$i.'</OPTION>';   
     }
     else
     {
       echo '<OPTION VALUE = '. $i . '>'.$i.'</OPTION>';   
     }
  }
}

function display_select_maand($name, $value)
{
  echo "<SELECT class=textval NAME=\"$name\" value=\"\">";

  if( $value == "" )
  { 
    echo '<OPTION VALUE = ""></OPTION>';   
  }
  else
  {
     echo '<OPTION VALUE = "" selected></OPTION>';   
  } 
  
  for($i=1;$i < 13;$i++)
  {
     if( $value == $i )
     {
       echo '<OPTION VALUE = '. $i . ' selected>'.$i.'</OPTION>';   
     }
     else
     {
       echo '<OPTION VALUE = '. $i . '>'.$i.'</OPTION>';   
     }
  }
}

function display_select_jaar($name, $value)
{
  echo "<SELECT class=textval NAME=\"$name\" value=\"\">";

  if( $value == "" )
  { 
    echo '<OPTION VALUE = ""></OPTION>';   
  }
  else
  {
     echo '<OPTION VALUE = "" selected></OPTION>';   
  } 
  
  for($i=2005;$i < 2015;$i++)
  {
     if( $value == $i )
     {
       echo '<OPTION VALUE = '. $i . ' selected>'.$i.'</OPTION>';   
     }
     else
     {
       echo '<OPTION VALUE = '. $i . '>'.$i.'</OPTION>';   
     }
  }
}

function display_select_role($value)// aanpassen!
{
  echo "<SELECT NAME=role value=\"$value\" >";

  if( $value == 'Medewerker' )
  {
      echo '<OPTION VALUE = "Medewerker" selected>Medewerker</OPTION>';   
      echo '<OPTION VALUE = "Beheerder">Beheerder</OPTION>';   
  }
  else
  {
      echo '<OPTION VALUE = "Medewerker">Medewerker</OPTION>';   
      echo '<OPTION VALUE = "Beheerder" selected>Beheerder</OPTION>';   
  }
  echo '</SELECT>';

}



?>