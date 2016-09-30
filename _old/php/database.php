<?php

function dbconnect()
{
  $dbserver = mysql_connect(DBHOST, DBUSER, DBPASSWORD);
  if (!$dbserver){ die('Could not connect: ' . mysql_error());}
  
  $db = mysql_select_db(DBNAME, $dbserver);
  if (!$db){ die ('Can\'t use '.DBNAME.mysql_error()); };

  return $dbserver;
}

function dbquery( $query )
{
  $result = mysql_query( $query );
  
  if (!$result) { die($query."\n".'Invalid query: ' . mysql_error()); }; 

  return $result;

}

function dbclose( $dbserver )
{
  mysql_close( $dbserver );
}

?>