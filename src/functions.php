<?php

include('vars.php');
include('classes/Misc.php');
include('classes/permissions.php');
include('classes/Items.php');
include('classes/Calculate.php');


$_CFG = array(
  'HOST_NAME' => '127.0.0.1'
);

$_CFG['COMPANY_NAME'] = Misc::sqlGet("companyName", "options", "id", 1)['companyName'];
$_CFG['VAT'] = Misc::sqlGet("vat", "options", "id", 1)['vat'];
$_CFG['CURRENCY'] = Misc::sqlGet("currency", "options", "id", 1)['currency'];
$_CFG['smtpHost'] = Misc::sqlGet("smtpHost", "options", "id", 1)['smtpHost'];
$_CFG['smtpPort'] = Misc::sqlGet("smtpPort", "options", "id", 1)['smtpPort'];
$_CFG['smtpName'] = Misc::sqlGet("smtpName", "options", "id", 1)['smtpName'];
$_CFG['smtpUser'] = Misc::sqlGet("smtpUser", "options", "id", 1)['smtpUser'];
$_CFG['smtpPass'] = Misc::sqlGet("smtpPass", "options", "id", 1)['smtpPass'];
$_CFG['smtpSecure'] = Misc::sqlGet("smtpSecure", "options", "id", 1)['smtpSecure'];


$config = array(
  'SQL_PASS' => "",
  'SQL_USER' => "root",
  'SQL_HOST' => "localhost",
  'SQL_DB' => 'cashier',
'timeout' => 480 // Session timeout
);
?>