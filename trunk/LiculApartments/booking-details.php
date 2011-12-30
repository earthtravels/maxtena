<?php
include_once ("includes/SystemConfiguration.class.php");
include ("includes/language.php");


global $systemConfiguration;
global $logger;
session_start ();
$logger->LogInfo(__FILE__);

$systemConfiguration->assertReferer();

$logger->LogInfo("Getting session object ...");
if (!isset($_SESSION['bookingDetails']))
{
	$logger->LogError("Session object could not be found ...");
	header ("Location: booking-failure.php?error_code=9");
}

// Get booking details from session
$bookingDetails = unserialize($_SESSION['bookingDetails']);

// Get selected extra bed
if (isset($_POST['extraBed']) && is_numeric($_POST['extraBed']) && intval($_POST['extraBed']) > 0)
{
	$logger->LogInfo("Extra bed was selected!");
    $bookingDetails->extraBedRequested = true;
}

// Get selected services
if (isset($_POST['extraServices']))
{
	$logger->LogInfo("Extra services were selected!");
    $bookingDetails->extraServices = array();
    foreach ($_POST['extraServices'] as $serviceId => $quantity)
    {
        $extraService = ExtraService::fetchFromDb(intval($serviceId));
        if ($extraService == null)
        {			
        	$logger->LogError("No extra service could be found for id: " . intval($serviceId));
            $_SESSION['errors'] = array (0 => BOOKING_FAILURE_INVALID_REQUEST);
            header ("Location: booking-failure.php");
        }
        if (intval($quantity) > 0)
        {
        	$logger->LogInfo("Extra service " .  $extraService->getName($language_selected) . " selected for quantity: " . intval($quantity));        
            $extraServiceDetails = array ( 0 => $extraService, 1 => $quantity, 2 => floatval($extraService->price * $quantity ) );
            $bookingDetails->extraServices[] = $extraServiceDetails;
        }
        else 
        {
        	$logger->LogWarn("Extra service has a quntity of 0. Skipping!");
        }
    }
}

// Calculate price details
$logger->LogInfo("Calculating price ...");
$bookingDetails->calculatePriceDetails($language_selected);

// Get entered promo code and client info
$promoErrorMessage = null;
$clientEmailAddress = null;
$client = new Client();
if (isset($_POST['promo_code']))
{
	$logger->LogInfo("Promo code was entered: " . $_POST['promo_code']);
	$client = Client::fetchFromParameters($_POST);
    $bookingDetails->promoCode = null;
    if (isset($_POST['email']))
    {
    	$logger->LogInfo("Email was entered: " . $_POST['email']);
       	$clientsEmailAddress = $_POST['email'];       	
    }
    $promoCode = PromoCode::fetchFromDBForCode($_POST['promo_code']);
    if ($promoCode == null)
    {   
    	$logger->LogWarn("Promo could not be found in the database!");
    	$logger->LogWarn("Errors:");
    	$logger->LogWarn(PromoCode::$staticErrors);
        $promoErrorMessage = PromoCode::$staticErrors[0];
    }	
    else if ($promoCode->isApplicable($bookingDetails->priceDetails->grandTotal, $clientsEmailAddress, $bookingDetails->searchCriteria->getNightCount(), $promoErrorMessage))
    {
    	$logger->LogInfo("Promo is applicable!");
        $bookingDetails->promoCode = $promoCode;
        $logger->LogInfo("Recalculating price ...");
        $bookingDetails->calculatePriceDetails($language_selected);
    }
    else
    {
    	$logger->LogWarn("Promo is not applicable!");
    	$logger->LogWarn($promoErrorMessage);
    }
}
    


// Save booking details to session
$bookingDetailsSerialized = serialize($bookingDetails);
$_SESSION['bookingDetails'] = $bookingDetailsSerialized;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title><?=$systemConfiguration->getSiteTitle()?> </title>
	<meta name="description" content="<?=$systemConfiguration->getSiteDescription()?>" />
	<meta name="keywords" content="<?=$systemConfiguration->getSiteKeywords()?>" />    
    <meta name="robots" content="ALL,FOLLOW" />
    <meta name="Author" content="AIT" />
    <meta http-equiv="imagetoolbar" content="no" />
    <title>Contact</title>
    <link rel="stylesheet" href="./css/reset.css" type="text/css" />
    <link rel="stylesheet" href="./css/jquery.fancybox.css" type="text/css" />
    <link rel="stylesheet" href="./css/nivo-slider.css" type="text/css" />
    <link rel="stylesheet" href="./css/screen.css" type="text/css" />
    <!--[if IE 7]>
		<link rel="stylesheet" type="text/css" href="http://www.ait.sk/simplicius/html/css/ie7.css" />
	<![endif]-->    
</head>
<body class="light">  
<?php include_once("analyticstracking.php") ?>      
    <!-- setting of light/dark main page box -->
    <div class="back">
        <div class="base">
            <?php include("header.php")?>
            <div class="page_top">                               
            </div>
            <div class="page">
                <div class="page_inside clear">
                    <div class="subpage clear">
                        <!-- ****** SIDEBAR ****** -->
                        <div id="sidebar">
                            <div class="sidebox">
                                <div class="box_top">
                                </div>
                                <div class="sideinner sidecontact" id="discount_code">                                    
                                    <h2><?= BOOKING_DETAILS_PROMO_CODE ?><span style="font-size: 12px; display: block; font-weight: bold; padding-top: 2px;"><?= BOOKING_DETAILS_PROMO_CODE_DESC ?></span></h2>                                     
                                    <?php
                                         if ($bookingDetails->promoCode != null)
                                         {
                                             echo '<p>' . BOOKING_DETAILS_COUPON_APPLIED . '</p>';
                                         }                                         
                                         else
                                         {
	                                         if ($promoErrorMessage != null)
	                                         {
	                                             echo '<p style="font-weight:bold; color: red;">' . $promoErrorMessage . '</p>';
	                                         }
									 ?>
                                            <form name="promo_code_form" id="promo_code_form" action="<?= $_SERVER['PHP_SELF'] ?>" method="post" onsubmit="return validatePromoCode();">
                                                <div class="clear">
                                                    <label for="discount_coupon"><?= BOOKING_DETAILS_CODE ?></label>
                                                    <input type="text" name="promo_code" id="promo_code" class="input" />
                                                    <input type="hidden" name="last_name" />
													<input type="hidden" name="middle_name" />
													<input type="hidden" name="first_name" />
													<input type="hidden" name="street_address" />
													<input type="hidden" name="city" />
													<input type="hidden" name="state" />
													<input type="hidden" name="country" />
													<input type="hidden" name="phone" />
                                                    <input type="hidden" name="email" />
                                                    <input type="hidden" name="agreement" />
                                                </div>
                                                <div class="confirm clear" id="btn_coupon_apply">
                                                    <div style="padding-right: 15px; width: 100px; padding-top: 0px;">
                                                        <input style="font-weight: 400; text-align: center; text-indent: 0; -moz-box-shadow: 0px 1px 0 #292925, 0px -1px 0 #292925;
                                                            background-color: #3f3f38; border-bottom: 1px solid #4d4d44; border-top: 1px solid #4d4d44;
                                                            cursor: pointer; vertical-align: middle; font: 99% arial,helvetica,clean,sans-serif;
                                                            float: left; margin-top: 0px !important; font-size: 13px; display: block; color: white !important;
                                                            text-decoration: none !important; position: relative; -webkit-transition: opacity .2s;
                                                            -moz-transition: opacity .2s; -o-transition: opacity .2s; -webkit-border-radius: 5px;
                                                            -moz-border-radius: 5px; padding: 4px 0 4px; letter-spacing: 0.9px; background-image: url(images/btn_overlay.png);
                                                            background-position: 0 50%; background-repeat: repeat-x; width: 100%; border: 0;
                                                            -webkit-box-shadow: 0px 1px 0 #292925, 0px -1px 0 #292925;" name="submit" type="submit"
                                                            value="<?= BOOKING_DETAILS_BTN_APPLY_PROMO ?>" />
                                                    </div>                                                                                               
                                                </div>                                                                                
                                            </form>
                                 <?php
										}									
								 ?>         	                        		
                                </div>
                                <div class="box_down">
                                </div>
                            </div>                           
                        </div>
                        <!-- end of sidebar -->
                        <!-- MAIN -->
                        <div id="main" class="clear">
                            <h1 style="padding-bottom: -10px;"><strong><?= BOOKING_DETAILS_TITLE ?></strong></h1>
                            <table>
                                <tr>
                                    <th style="text-align: right;">
                                        <?= BOOKING_DETAILS_CHECK_IN ?>
                                    </th>
                                    <th  style="text-align: right;">
                                        <?= BOOKING_DETAILS_CHECK_OUT ?>
                                    </th>
                                    <th  style="text-align: right;">
                                        <?= BOOKING_DETAILS_TOTAL_NIGHTS ?>
                                    </th>                                   
                                </tr>
                                <tr>
                                    <td  style="text-align: right;">
                                        <?= $bookingDetails->searchCriteria->checkInDate->format("m/d/Y") ?>
                                    </td>
                                    <td  style="text-align: right;">
                                        <?= $bookingDetails->searchCriteria->checkOutDate->format("m/d/Y") ?>
                                    </td>
                                    <td  style="text-align: right;">
                                        <?= $bookingDetails->searchCriteria->getNightCount() ?>
                                    </td>                                    
                                </tr>
                                <tr>
                                    <th  style="text-align: right;">
                                        <?= BOOKING_DETAILS_ACCOMMODATION_NUMBER ?>
                                    </th>
                                    <th  style="text-align: right;">
                                        <?= BOOKING_DETAILS_ACCOMMODATION_NAME ?>
                                    </th>
                                    <th  style="text-align: right;">
                                        <?= BOOKING_DETAILS_TOTAL_OCCUPANTS ?>
                                    </th>                                   
                                </tr>
                                <tr>
                                    <td  style="text-align: right;">
                                        <?= $bookingDetails->room->roomNumber ?>
                                    </td>
                                    <td  style="text-align: right;">
                                        <?= $bookingDetails->room->roomName ?>
                                    </td>
                                    <td  style="text-align: right;">
                                        <?= intval($bookingDetails->searchCriteria->adultsCount) +  intval($bookingDetails->searchCriteria->childrenCount) ?>
                                    </td>                                    
                                </tr>
                                <tr>
                                     <td colspan="2" style="text-align: right;">
                                        <?= BOOKING_DETAILS_ACCOMMODATION_TOTAL ?>
                                    </td>
                                    <td style="text-align: right;">
                                        <?= $systemConfiguration->formatCurrency($bookingDetails->priceDetails->roomPrice) ?>
                                    </td>
                                </tr>                                
                             <?php
                       			if ($bookingDetails->priceDetails->extraBedPrice > 0)
                       			{                       		?>
                                    <tr>
                                         <td colspan="2" style="text-align: right;">
                                            <?= BOOKING_SERVICES_EXTRA_BED ?>
                                        </td>
                                        <td style="text-align: right;">
                                            <?= $systemConfiguration->formatCurrency($bookingDetails->priceDetails->extraBedPrice) ?>
                                        </td>
                                    </tr>
                       		<?php									
                       			}
                       		
                       			if (sizeof($bookingDetails->priceDetails->extraServicesDetail) > 0)
                       			{
									foreach($bookingDetails->priceDetails->extraServicesDetail as $extraServiceDetails)
									{
										$extraService = $extraServiceDetails[0];
										$quantity = intval($extraServiceDetails[1]);
										$totalPrice = floatval($extraServiceDetails[2]);
                       		?>
                                        <tr>
                                             <td colspan="2" style="text-align: right;">
                                                <?= $extraService->getName($language_selected) . " (" . $systemConfiguration->formatCurrency($extraService->price) .  " x " . $quantity . ")"   ?>
                                            </td>
                                            <td style="text-align: right;">
                                                <?= $systemConfiguration->formatCurrency($totalPrice) ?>
                                            </td>
                                        </tr>
                       		<?php 
									}
                       			}
								if (sizeof($bookingDetails->priceDetails->extraServicesDetail) > 0 || $bookingDetails->priceDetails->extraBedPrice > 0)
                       			{
                       		?>
                       				<tr>
	                                     <th colspan="2" style="text-align: right;">
	                                        <?= BOOKING_DETAILS_SUBTOTAL ?>
	                                    </th>
	                                    <td style="text-align: right;">
	                                        <?= $systemConfiguration->formatCurrency($bookingDetails->priceDetails->subtotalBeforeDiscounts) ?>
	                                    </td>
	                                </tr>
                       		<?php 
								}
                       			
                       			if ($bookingDetails->priceDetails->monthlyDiscount > 0)
                       			{
                       		?>
	                       			<tr>
	                                     <td colspan="2" style="text-align: right;">
	                                        <?= BOOKING_DETAILS_DISCOUNT ?> (<?= $bookingDetails->priceDetails->monthlyDiscountPercent ?> %)
	                                    </td>
	                                    <td style="text-align: right;">
	                                        (- <?= $systemConfiguration->formatCurrency($bookingDetails->priceDetails->monthlyDiscount) ?>)
	                                    </td>
	                                </tr>
                       		
                       		<?php
                       			} 
                       			
                       			if ($bookingDetails->priceDetails->promoDiscount > 0)
                       			{
                       		?>   
                       				<tr>
	                                     <td colspan="2" style="text-align: right;">
	                                        <?= BOOKING_DETAILS_CODE . " (" . $bookingDetails->priceDetails->promoCode . ")" ?>
	                                    </td>
	                                    <td style="text-align: right;">
	                                        (- <?= $systemConfiguration->formatCurrency($bookingDetails->priceDetails->promoDiscount) ?>)
	                                    </td>
	                                </tr>
                       		<?php
                       			} 
								
								if ($bookingDetails->priceDetails->monthlyDiscount > 0 || $bookingDetails->priceDetails->promoDiscount > 0)
                       			{
                       		?>
	                       			<tr>
	                                     <td colspan="2" style="text-align: right;">
	                                        <?= BOOKING_DETAILS_SUBTOTAL ?>
	                                    </td>
	                                    <td style="text-align: right;">
	                                        <?= $systemConfiguration->formatCurrency($bookingDetails->priceDetails->subtotalAfterDiscounts) ?>
	                                    </td>
	                                </tr>
                       		
                       		<?php
                       			}
                       			
                       			if ($bookingDetails->priceDetails->taxAmount > 0)
                       			{
                       		?>   
                       				<tr>
	                                     <td colspan="2" style="text-align: right;">
	                                        <?= BOOKING_DETAILS_TAX ?> (<?= $bookingDetails->priceDetails->taxRate ?> %)
	                                    </td>
	                                    <td style="text-align: right;">
	                                        <?= $systemConfiguration->formatCurrency($bookingDetails->priceDetails->taxAmount) ?>
	                                    </td>
	                                </tr>	                                
                       		<?php
                       			} 
                       		?>
                       				<tr>
	                                     <td colspan="2" style="text-align: right;">
	                                        <?= BOOKING_DETAILS_GRAND_TOTAL ?>
	                                    </td>
	                                    <td style="text-align: right;">
	                                        <?= $systemConfiguration->formatCurrency($bookingDetails->priceDetails->grandTotal) ?>
	                                    </td>
	                                </tr>
                       		<?php 
								
								if ($bookingDetails->priceDetails->monthlyDepositPercent > 0)
								{
                       		?>                          
									<tr>                                	
	                                    <th colspan="2" style="text-align: right;">
	                                        <?= BOOKING_DETAILS_DEPOSIT . " (" . $bookingDetails->priceDetails->monthlyDepositPercent . " %)"  ?>
	                                    </th>
	                                    <td style="text-align: right;">
	                                        <?= $systemConfiguration->formatCurrency($bookingDetails->priceDetails->totalDue) ?>
	                                    </td>
									</tr>
							<?php
								}
							?>
								 
                            </table>                            
                            
                            <h1><strong><?= BOOKING_DETAILS_BILLING ?></strong></h1>
                            <div id="contact">
                                <form id="booking_form" action="booking-process.php" method="post" onsubmit="return validateClientData();">
                                <div class="clear">
                                    <label for="first_name" class="required"><?= BOOKING_DETAILS_FNAME ?></label>
                                    <input type="text" name="first_name" class="input" value="<?= htmlentities($client->firstName) ?>" />
                                </div>
                                <div class="clear">
                                    <label for="middle_name"><?= BOOKING_DETAILS_MNAME ?></label>
                                    <input type="text" name="middle_name" class="input" value="<?= htmlentities($client->middleName) ?>" />
                                </div>
                                <div class="clear">
                                    <label for="last_name" class="required"><?= BOOKING_DETAILS_LNAME ?></label>
                                    <input type="text" name="last_name" id="last_name" class="input" value="<?= htmlentities($client->lastName) ?>" />
                                </div>
                                <div class="clear">
                                    <label for="street_address" class="required"><?= BOOKING_DETAILS_STR_ADDR ?></label>
                                    <input type="text" name="street_address" class="input" value="<?= htmlentities($client->streetAddress) ?>" />
                                </div>
                                <div class="clear">
                                    <label for="city" class="required"><?= BOOKING_DETAILS_CITY ?></label>
                                    <input type="text" name="city" class="input" value="<?= htmlentities($client->city) ?>" />
                                </div>
                                <div class="clear">
                                    <label for="state" class="required"><?= BOOKING_DETAILS_STATE ?></label>
                                    <input type="text" name="state" class="input" value="<?= htmlentities($client->state) ?>" />
                                </div>
                                <div class="clear">
                                    <label for="country" class="required"><?= BOOKING_DETAILS_COUNTRY ?></label>
                                    <input type="text" name="country" class="input" value="<?= htmlentities($client->country) ?>" />
                                </div>
                                <div class="clear">
                                    <label for="phone" class="required"><?= BOOKING_DETAILS_PHONE ?></label>
                                    <input type="text" name="phone" class="input" value="<?= htmlentities($client->phone) ?>" />
                                </div>
                                <div class="clear">
                                    <label for="email" class="required"><?= BOOKING_DETAILS_EMAIL ?></label>
                                    <input type="text" name="email" class="input" value="<?= htmlentities($client->email) ?>" />
                                </div>
                                <div class="clear">
                                    <label for="paymentMethod" class="required"><?= BOOKING_DETAILS_PAYMENT_OPTION ?></label>
                                    <?php
                                    $paymentGateways = PaymentGateway::fetchFromDbNonAdminActive();
                                    $isFirstItem = true;
                                    foreach ($paymentGateways as $paymentGateway) 
                                    {
                                    	echo '<input type="radio" name="payment_gateway_code" value="' . $paymentGateway->gatewayCode . '"' . ($isFirstItem ? ' checked="checked"' : '') . '/>' . $paymentGateway->gatewayName->getText($language_selected) . '<br />';
                                    	$isFirstItem = false;
                                    } 
                                    ?>                                    
                                </div>
                                <div class="clear">
                                    <label class="empty required"><?= BOOKING_DETAILS_AGREEMENT ?></label>
                                    <input type="checkbox" name="agreement" id="agreement" class="checkbox" <?= isset($_POST['agreement']) ? 'checked="checked"' : '' ?> />
                                    <label for="agreement" class="right"><?= BOOKING_DETAILS_AGREE_TEXT_PART1 ?><a href="terms.php" target="_blank"><?= BOOKING_DETAILS_AGREE_TEXT_PART2 ?></a></label>
                                </div>                               
                                <div class="confirm clear">
                                    <div style="padding-right: 15px; width: 100px; padding-top: 0px;">
                                        <input style="font-weight: 400; text-align: center; text-indent: 0; -moz-box-shadow: 0px 1px 0 #292925, 0px -1px 0 #292925;
                                            background-color: #3f3f38; border-bottom: 1px solid #4d4d44; border-top: 1px solid #4d4d44;
                                            cursor: pointer; vertical-align: middle; font: 99% arial,helvetica,clean,sans-serif;
                                            float: left; margin-top: 0px !important; font-size: 13px; display: block; color: white !important;
                                            text-decoration: none !important; position: relative; -webkit-transition: opacity .2s;
                                            -moz-transition: opacity .2s; -o-transition: opacity .2s; -webkit-border-radius: 5px;
                                            -moz-border-radius: 5px; padding: 4px 0 4px; letter-spacing: 0.9px; background-image: url(images/btn_overlay.png);
                                            background-position: 0 50%; background-repeat: repeat-x; width: 100%; border: 0;
                                            -webkit-box-shadow: 0px 1px 0 #292925, 0px -1px 0 #292925;" name="submit" type="submit"
                                            value="<?= BOOKING_DETAILS_SUBMIT_BUTTON ?>" />
                                    </div>                                    
                                </div>
                                </form>
                                <div class="rule">
                                </div>
                            </div>                            
                        </div>
                        <!-- end of main -->
                    </div>                   
                </div>
            </div>
            <div class="page_down">
            </div>
            <?php include("footer.php")?>
        </div>
    </div>
    <script type="text/javascript" src="./js/jquery.js"></script>
    <script type="text/javascript" src="./js/jquery.validate.js"></script>
    <script type="text/javascript" src="./js/jquery.fancybox.js"></script>
    <script type="text/javascript" src="./js/jquery.nivo.js"></script>
    <script type="text/javascript" src="./js/cufon.js"></script>
    <script type="text/javascript" src="./js/geometr231_hv_bt_400.font.js"></script>
    <script type="text/javascript" src="./js/script.js"></script>
    <script src="scripts/jquery.validate.js" type="text/javascript"></script>
	<script src="scripts/hotelvalidation.js" type="text/javascript"></script>
    
    <script type="text/javascript">
	String.prototype.mytrim = function() { return this.replace(/^\s\s*/, '').replace(/\s\s*$/, '')};
	
	function isEmail(string) 
	{
		if (string.search(/^[A-Za-z0-9\._\%\+\-]+@[A-Za-z0-9\.\-]+\.[A-Za-z]{2,4}$/ ) != -1)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	function isPhoneNumber(string) 
	{
		if (string.search(/^\+?[0-9\-\(\) ]+$/ ) != -1)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	function validateClientData()
	{
		var bookingForm = document.getElementById("booking_form");
		if (bookingForm == null)
		{
			alert("Error looking up booking form!");
			return false;
		}
		
		if(bookingForm.first_name.value.mytrim().length == 0){
			alert('<?= BOOKING_DETAILS_FNAME_REQ ?>');
			return false;
		}
		
		if(bookingForm.middle_name.value.mytrim().length == 0){
			alert('<?= BOOKING_DETAILS_MNAME_REQ ?>');
			return false;
		}
		
		if(bookingForm.last_name.value.mytrim().length == 0){
			alert('<?= BOOKING_DETAILS_LNAME_REQ ?>');
			return false;
		}
		
		if(bookingForm.street_address.value.mytrim().length == 0){
			alert('<?= BOOKING_DETAILS_STR_ADDR_REQ ?>');
			return false;
		}
		
		if(bookingForm.city.value.mytrim().length == 0){
			alert('<?= BOOKING_DETAILS_CITY_REQ ?>');
			return false;
		}
		
		if(bookingForm.state.value.mytrim().length == 0){
			alert('<?= BOOKING_DETAILS_STATE_REQ ?>');
			return false;
		}		
		
		if(bookingForm.country.value.mytrim().length == 0){
			alert('<?= BOOKING_DETAILS_COUNTRY_REQ ?>');
			return false;
		}
		
		if(bookingForm.phone.value.mytrim().length == 0){
			alert('<?= BOOKING_DETAILS_PHONE_REQ ?>');
			return false;
		}
		else if (!isPhoneNumber(bookingForm.phone.value)){
			alert('<?= BOOKING_DETAILS_PHONE_INVALID ?>');
			return false;
		}		
		
		if(bookingForm.email.value.mytrim().length == 0){
			alert('<?= BOOKING_DETAILS_COUPON_EMAIL_REQ ?>');
			return false;
		}	
		else if (!isEmail(bookingForm.email.value)){
			alert('<?= BOOKING_DETAILS_EMAIL_INVALID ?>');
			return false;
		}

		if (!bookingForm.agreement.checked)
		{
			alert('<?= BOOKING_DETAILS_AGREEMENT_REQ ?>');
			return false;
		}		
	}
	
	function validatePromoCode()
	{
		var promoForm = document.getElementById("promo_code_form");
		if (promoForm == null)
		{
			alert("Error looking up promo form!");
			return false;
		}

		var bookingForm = document.getElementById("booking_form");
		if (bookingForm == null)
		{
			alert("Error looking up booking form!");
			return false;
		}
				
		if(promoForm.promo_code.value.mytrim().length == 0){
			alert('<?= BOOKING_DETAILS_COUPON_EMPTY ?>');
			return false;
		}
		else if(bookingForm.email.value.mytrim().length == 0){
			alert('<?= BOOKING_DETAILS_COUPON_EMAIL_REQ ?>');
			return false;
		}	
		else if (!isEmail(bookingForm.email.value)){
			alert('<?= BOOKING_DETAILS_EMAIL_INVALID ?>');
			return false;
		}
		promoForm.first_name.value = bookingForm.first_name.value;
		promoForm.middle_name.value = bookingForm.middle_name.value;
		promoForm.last_name.value = bookingForm.last_name.value;
		promoForm.street_address.value = bookingForm.street_address.value;
		promoForm.city.value = bookingForm.city.value;
		promoForm.state.value = bookingForm.state.value;
		promoForm.country.value = bookingForm.country.value;
		promoForm.phone.value = bookingForm.phone.value;
		promoForm.email.value = bookingForm.email.value;
		promoForm.agreement.value = bookingForm.agreement.value;		
	}
	</script>  
    
	
	<script type="text/javascript" src="scripts/base64_decode.js"></script>    
</body>
</html>














