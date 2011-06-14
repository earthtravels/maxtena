<?php
class PriceDetails
{   
    public $roomPrice= 0;
    public $extraBedPrice = 0;
	// [0] = ExtraService object 
	// [1] = quantity
	// [2] = total price
    public $extraServicesDetail = array();
    public $subtotalBeforeDiscounts;
    public $monthlyDiscountPercent = 0;
    public $monthlyDiscount = 0;
    public $promoCode = "";
    public $promoDiscount = 0;     
    public $subtotalAfterDiscounts = 0;
    public $taxRate = 0;
    public $taxAmount = 0;
    public $grandTotal = 0;
    public $monthlyDepositPercent = 0;
    public $totalDue = 0;
}