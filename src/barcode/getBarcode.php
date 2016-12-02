<?php
if (isset($_GET['EAN']))
{
	include_once("Barcode_Gen/BarcodeGenerator.php");
	include_once("Barcode_Gen/BarcodeGeneratorSVG.php");
	$generator = new Picqer\Barcode\BarcodeGeneratorSVG();

	echo '<div id="eanCode' . $_GET['EAN'] . '">';
	echo '<span style="display: inline-block;">' . $generator->getBarcode($_GET['EAN'], $generator::TYPE_EAN_13, 1.5, 64) . '</span>';
	echo '<span style="    display: inline-block;
		background-color: white;
		position: relative;
		margin-left: 3px;
		left: -122.735;">
		&nbsp;&nbsp;&nbsp;' . $_GET['EAN'] . '&nbsp;&nbsp;&nbsp;</span>';
	echo '</div>';
}
