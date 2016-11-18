<?php
session_start();

abstract class PaymentMethod
{
    const Pin = 1;
    const Cash = 2;
    const BankTransfer = 3;
    const All = 4;
}

class Calculate
{
    public static function getGrossTurnover($identifier)
    {
        if ($identifier == PaymentMethod::All)
        {
            $sql = "SELECT * FROM  receipt WHERE paidDt IS NOT NULL";


        }
        else if ($identifier == PaymentMethod::Pin)
        {
            $sql = "SELECT * FROM  receipt WHERE paidDt IS NOT NULL AND paymentMethod='PIN'";


        }
        else if ($identifier == PaymentMethod::Cash)
        {
            $sql = "SELECT * FROM  receipt WHERE paidDt IS NOT NULL AND paymentMethod='CASH'";


        }
        else if ($identifier == PaymentMethod::BankTransfer)
        {
            $sql = "SELECT * FROM  receipt WHERE paidDt IS NOT NULL AND paymentMethod='BANK'";


        }
    }

    public static function getNetTurnover($identifier)
    {
        if ($identifier == PaymentMethod::All)
        {

        }
        else if ($identifier == PaymentMethod::Pin)
        {

        }
        else if ($identifier == PaymentMethod::Cash)
        {

        }
        else if ($identifier == PaymentMethod::BankTransfer)
        {

        }
    }

    public static function getMargin($identifier)
    {
        if ($identifier == PaymentMethod::All)
        {

        }
        else if ($identifier == PaymentMethod::Pin)
        {

        }
        else if ($identifier == PaymentMethod::Cash)
        {

        }
        else if ($identifier == PaymentMethod::BankTransfer)
        {

        }
    }
}
