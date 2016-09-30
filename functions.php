<?php

if (file_exists('vars.php') || file_exists('../vars.php'))
  include('vars.php');
else
  die("FATAL ERROR: vars.php is missing!!<br />Example vars.php<br />
  <br />
\$config = array(<br />
&nbsp;&nbsp;&nbsp;&nbsp;'SQL_PASS' => \"\",<br />
&nbsp;&nbsp;&nbsp;&nbsp;'SQL_USER' => \"\",<br />
&nbsp;&nbsp;&nbsp;&nbsp;'SQL_HOST' => \"localhost\"<br />
&nbsp;&nbsp;&nbsp;&nbsp;'SQL_DB' => \"\"<br />
);<br />");

include('classes/permissions.php');

?>