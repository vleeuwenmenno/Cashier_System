<?php
//Get ALL receipt data to put on the paper
$printAmount = $_GET['printAmount'];
$receiptId = $_GET['receiptId'];
$paymentMethod = $_GET['paymentMethod'];

if ($paymentMethod == "PC")
{
    $cashValue = $_GET['cash'];
    $pinValue = $_GET['pin'];

    
}

//Create a document for the paper receipt

//Print receipt (Amount based on GET para &print)

//Register receipt as paid into the database

//Move receipt data in session to OLD
