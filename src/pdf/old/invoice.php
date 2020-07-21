<?php
    include_once("../includes.php");
    Permissions::checkSession(basename($_SERVER['REQUEST_URI']));
    
if (isset($_GET['contractId']))
{
	$custId = Misc::sqlGet("customerId", "contract", "contractId", $_GET['contractId'])['customerId'];
    ?>
	<!DOCTYPE html>
	<html lang="en">
		<head>
			<meta charset="utf-8">
			<title>Factuur specificatie</title>
			<link rel="stylesheet" href="style.css" media="all" />
		</head>
		<body>
			<header class="clearfix">
				<div id="logo">
					<img src="logo.png">
				</div>
				<div id="company">
					<h2 class="name"><?=$_CFG['COMPANY_NAME']?></h2>
					<div><?=$_CFG['companyAddress']?></div>
					<div><?=$_CFG['companyPhone']?></div>
					<div><a href="mailto:<?=$_CFG['companyEmail']?>"><?=$_CFG['companyEmail']?></a></div>
				</div>
				</div>
			</header>
			<main>
				<div id="details" class="clearfix">
					<div id="client">
						<div class="to">KLANT:</div>
						<h2 class="name"><?=Misc::sqlGet("initials", "customers", "customerId", $custId)['initials']?> <?=Misc::sqlGet("familyName", "customers", "customerId", $custId)['familyName']?></h2>
						<div class="address"><?=Misc::sqlGet("companyName", "customers", "customerId", $custId)['companyName']?></div>
						<div class="address"><?=Misc::sqlGet("streetName", "customers", "customerId", $custId)['streetName']?>, <?=Misc::sqlGet("postalCode", "customers", "customerId", $custId)['postalCode']?> <?=Misc::sqlGet("city", "customers", "customerId", $custId)['city']?></div>
						<div class="email"><a href="mailto:<?=Misc::sqlGet("email", "customers", "customerId", $custId)['email']?>"><?=Misc::sqlGet("email", "customers", "customerId", $custId)['email']?></a></div>
					</div>
					<div id="invoice">
						<h1>FACTUUR</h1>
						<div class="date">Factuurnummer: #000032</div>
						<div class="date">Factuurdatum: 01-06-2014</div>
						<div class="date">Vervaldatum: 30-06-2014</div>
					</div>
				</div>
				<table border="0" cellspacing="0" cellpadding="0">
					<thead>
						<tr>
							<th class="no">#</th>
							<th class="desc">OMSCHRIJVING</th>
							<th class="unit">STUKPRIJS</th>
							<th class="qty">AANTAL</th>
							<th class="total">BEDRAG</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td class="no">01</td>
							<td class="desc">
								<h3>Mailbox</h3>
							</td>
							<td class="unit"><?=$_CFG['CURRENCY']?>&nbsp;5,00</td>
							<td class="qty">5</td>
							<td class="total"><?=$_CFG['CURRENCY']?>&nbsp;25,00</td>
						</tr>
						<tr>
							<td class="no">02</td>
							<td class="desc">
								<h3>Onderhoud</h3>
							</td>
							<td class="unit"><?=$_CFG['CURRENCY']?>&nbsp;40,00</td>
							<td class="qty">1</td>
							<td class="total"><?=$_CFG['CURRENCY']?>&nbsp;40,00</td>
						</tr>
					</tbody>
					<tfoot>
						<tr>
							<td colspan="2"></td>
							<td colspan="2">Sub-totaal</td>
							<td><?=$_CFG['CURRENCY']?>&nbsp;45,00</td>
						</tr>
						<tr>
							<td colspan="2"></td>
							<td colspan="2">BTW <?=$_CFG['VAT']*100-100?>%</td>
							<td>$1,300.00</td>
						</tr>
						<tr>
							<td colspan="2"></td>
							<td colspan="2">GRAND TOTAL</td>
							<td>$6,500.00</td>
						</tr>
					</tfoot>
				</table>
				<div id="thanks">Thank you!</div>
				<div id="notices">
					<div>NOTICE:</div>
					<div class="notice">A finance charge of 1.5% will be made on unpaid balances after 30 days.</div>
				</div>
			</main>
			<footer>
				<?=$_CFG['companyKvk']?> | <?=$_CFG['companyVATNo']?> | <?=$_CFG['companyIBAN']?> 
			</footer>
		</body>
	</html>
<?php
}