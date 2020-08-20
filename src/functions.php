<?php

include('vars.php');
include('classes/Misc.php');
include('classes/permissions.php');
include('classes/Items.php');
include('classes/Calculate.php');


$_CFG = array(
  'HOST_NAME' => 'cashier.local',
  'VERSION' => 'v2.0.8 (Beta)'
);

$_CFG['COMPANY_NAME'] = Misc::sqlGet("companyName", "options", "id", 1)['companyName'];
$_CFG['companyAddress'] = Misc::sqlGet("companyAddress", "options", "id", 1)['companyAddress'];
$_CFG['companyPhone'] = Misc::sqlGet("companyPhone", "options", "id", 1)['companyPhone'];
$_CFG['companyFax'] = Misc::sqlGet("companyFax", "options", "id", 1)['companyFax'];
$_CFG['companyWebsite'] = Misc::sqlGet("companyWebsite", "options", "id", 1)['companyWebsite'];
$_CFG['companyKvk'] = Misc::sqlGet("companyKvk", "options", "id", 1)['companyKvk'];
$_CFG['companyIBAN'] = Misc::sqlGet("companyIBAN", "options", "id", 1)['companyIBAN'];
$_CFG['companyVATNo'] = Misc::sqlGet("companyVATNo", "options", "id", 1)['companyVATNo'];
$_CFG['companyEmail'] = Misc::sqlGet("companyEmail", "options", "id", 1)['companyEmail'];
$_CFG['disclaimer'] = Misc::sqlGet("disclaimer", "options", "id", 1)['disclaimer'];
$_CFG['invoiceExpireDays'] = Misc::sqlGet("invoiceExpireDays", "options", "id", 1)['invoiceExpireDays'];

$_CFG['VAT'] = Misc::sqlGet("vat", "options", "id", 1)['vat'];
$_CFG['VATText'] = Misc::sqlGet("VATText", "options", "id", 1)['VATText'];
$_CFG['CURRENCY'] = Misc::sqlGet("currency", "options", "id", 1)['currency'];
$_CFG['smtpHost'] = Misc::sqlGet("smtpHost", "options", "id", 1)['smtpHost'];
$_CFG['smtpPort'] = Misc::sqlGet("smtpPort", "options", "id", 1)['smtpPort'];
$_CFG['smtpName'] = Misc::sqlGet("smtpName", "options", "id", 1)['smtpName'];
$_CFG['smtpUser'] = Misc::sqlGet("smtpUser", "options", "id", 1)['smtpUser'];
$_CFG['smtpPass'] = Misc::sqlGet("smtpPass", "options", "id", 1)['smtpPass'];
$_CFG['smtpSecure'] = Misc::sqlGet("smtpSecure", "options", "id", 1)['smtpSecure'];

?>