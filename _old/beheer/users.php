<?php
include("include.php");
session_start();

if( !isset( $_SESSION['usernaam'] ) )
{
  header("Location: login.php");
  exit();
}
else
{  
  $module = new Module();
  $module->set_module( $_SESSION['usernaam'], 'Beheer');
  $subpage = process_input_users();
  display_users_page($subpage);
}

function display_users_page($subpage)
{ 
  global $module;
  global $httpdir;

  display_header(); 
  echo '<body>';
  display_stylesheet();
  echo '<script type="text/javascript" src="'.$httpdir.'/php/jsfuncties.js"></script>';
  echo '<div class="screen">';
  display_beheer_menu(MUSERS, $subpage);
  display_user_main($subpage);
  echo '</div>';
  echo '</body>';
  echo '</html>';
}

function display_user_main($subpage)
{
  global $module;
  global $userid;

  if( $subpage == SWIJZIGEN )
  {
    display_user_wijzigen($userid);
  }
  else
  {
     display_user_result();
  }
}

function display_user_result()
{
  global $module;
  global $httpdir;

  echo '<div class="mainbox">', "\n";
  $query="select * from user";
  $dbserver = dbconnect();
  $result = dbquery($query);
  if( mysql_num_rows($result) == 0 )
  {
       echo "Geen Users!!!!"; 
  }
  else
  {
      $i=0;
      echo '<TABLE align="center" class="resulttable">';
      display_result_header();
      while ($row = mysql_fetch_object($result)) 
      { 
         display_result_row($row, $i);
         $i++;
      }
      echo '</TABLE>'; 
  }
  echo '<input class=resultbutton type="button" value="Nieuw" onclick="nieuw_user()"';

  mysql_free_result($result);
  dbclose($dbserver); 
  
  echo '</div>', "\n"; 
}

function process_input_users()
{
  global $module;
  global $submit;
  global $query;
  global $userid;
 
  if( !isset( $submit ) )
  {
        $query = "";
        $subpage = SMAIN;
  }
  elseif( $submit == "Opslaan" )
  {
      update_user();
      $subpage = SMAIN;
  }
  elseif( $submit == "Wijzigen" )
  {
      $subpage = SWIJZIGEN;
  }
  elseif( $submit == "Annuleren" )
  {
      $subpage = SMAIN;
  }
  elseif( $submit == "Nieuw" )
  {
      $user = new_user();
      $userid = $user->id;
      $subpage = SWIJZIGEN;
  }
  elseif( $submit == "Verwijderen" )
  {
      delete_user($userid);
      $subpage = SMAIN;
  }

  return $subpage;  
}

function display_result_header()
{
  echo "<TR><TH>Naam</TH><TH>Functie</TH></TR>";
}

function display_result_row($row, $i)
{
    echo "<TR>";
    echo '<input id="userid'.$i. '" type=hidden value="'. $row->id . '"/>';
    echo "<TD>$row->naam</TD>";
    echo "<TD>$row->role</TD>";
    echo '<TD><input class=resultbutton type="button" value="Wijzigen" onclick="wijzigen_user('.$i.')"</TD>';
    echo '<TD><input class=resultbutton type="button" value="Verwijderen" onclick="verwijderen_user('.$i.')"</TD>';
    echo "</TR>";
}

function display_user_wijzigen($userid)
{
  // get user gegevens, if -1 nieuwe users  
  global $httpdir;
  global $module;

  if( $userid == -1 )
  {
     $user=new_user();
  }
  else
  {  
     $user=get_user($userid);
  }
  echo '<div class="mainbox">', "\n";
  echo '<form action="', "${httpdir}", '/beheer/users.php" method="post">';
  echo '<input type="hidden" name="userid" value='. $user->id . ' >';
  echo '<TABLE align="center" class="requesttable">';
  echo '<TR><TD>Naam</TD>';
  echo '<TD><input type"text" name="naam" value="'.$user->naam . '" ><TD></TR>';

  echo '<TR><TD>Functie</TD>';
  echo '<TD>', display_select_role($user->role),'<TD></TR>';

  echo '<TR><TD>Password</TD>';
  echo '<TD><input type"text" name="password" value="'.$user->password . '" ><TD></TR>';
  echo '</TABLE>';
  echo '<input type="submit" name="submit" value="Annuleren" />';
  echo '<input type="submit" name="submit" value="Opslaan" />';
  echo '</FORM>';
  echo '</div>', "\n"; 
}

function new_user()
{
$dbserver = dbconnect();
  $query = "insert into user values (NULL,'onbekend', 'onbekend', 'medewerker')";
  $result = dbquery( $query );
  $userid = mysql_insert_id();
  //mysql_free_result();
  dbclose($dbserver);
  return get_user( $userid );
}

function get_user($userid)
{
  $dbserver = dbconnect();
  $query = "select * from user where id = $userid";
  $result = dbquery($query);
  $row = mysql_fetch_object($result);
  mysql_free_result( $result);
  dbclose( $dbserver );
  return $row;
}

function update_user()
{ 
    extract($_POST);
    extract($_GET);
    $dbserver = dbconnect();
    $query = "update user set naam='$naam',
                             password='$password',
                             role='$role'where id = $userid";
    dbquery($query);
    dbclose($dbserver);
} 

function delete_user()
{
   extract($_POST);
   extract($_GET);
   $dbserver = dbconnect();
   $query = "delete from user where id = $userid";
   dbquery($query);
   dbclose($dbserver);
}

?>