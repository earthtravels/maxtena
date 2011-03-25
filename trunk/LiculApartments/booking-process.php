<?php
session_start();
include("includes/db.conn.php");
include("includes/conf.class.php");

if(isset($_SERVER['HTTP_REFERER'])){
	if($_SERVER['HTTP_REFERER'] != $bsiCore->getweburl()."booking-details.php"){ 
		header('Location: booking-failure.php?error_code=9'); 
	}
}else{ 
	header('Location: booking-failure.php?error_code=9'); 
}
include("includes/mail.class.php");
include("includes/process.class.php");
$bookprs = new BookingProcess();

switch($bookprs->paymentGatewayCode){	
	case "poa":
		processPayOnArrival();
		break;
		
	case "pp": 		
		processPayPal();
		break;	
					
	case "2co":
		process2Checkout();
		break;	
		
	case "cc":
		processCreditCard();
		break;			
		
	case "admin":
		processPayOnArrival();
		break;	
				
	case "an":
		processAuthorizeDotNet();
		break;
		
	default:
		processOther();
}


/* PAY ON ARIVAL: MANUAL PAYMENT */	
function processPayOnArrival(){	
	global $bookprs;
	global $bsiCore;
	$bsiMail = new bsiMail();
	
	mysql_query("UPDATE bsi_bookings SET payment_success=true WHERE booking_id = ".$bookprs->bookingId);
	mysql_query("UPDATE bsi_clients SET existing_client = 1 WHERE email = '".$bookprs->clientEmail."'");		
	
	$emailBody = "Dear ".$bookprs->clientName.",<br><br>";
	$emailBody .= $bsiMail->emailContent['body']."<br><br>";
	$emailBody .= $bookprs->invoiceHtml;
	$emailBody .= '<br><br>Regards,<br>'.$bsiCore->config['conf_hotel_name'].'<br>'.$bsiCore->config['conf_hotel_phone'];
	$emailBody .= '<br><br><font style=\"color:#F00; font-size:10px;\">[ You will need to carry a print out of this e-mail and present it to the hotel on arrival and check-in. This e-mail is the confirmation voucher for your booking. ]</font>';				
	$returnMsg = $bsiMail->sendEMail($bookprs->clientEmail, $bsiMail->emailContent['subject'], $emailBody);
	
	if ($returnMsg == "Message successfully sent!") {		
		/* Notify Email for Hotel about Booking */
		$notifyEmailSubject = "Booking no.".$bookprs->bookingId." - Notification of Room Booking by ".$bookprs->clientName;				
		$notifynMsg = $bsiMail->sendEMail($bsiCore->config['conf_hotel_email'], $notifyEmailSubject, $bookprs->invoiceHtml);
		
		header('Location: booking-confirm.php?success_code=1');
		die;
	}else {
		header('Location: booking-failure.php?error_code=25');
		die;
	}		
}

/* PAYPAL PAYMENT */ 
function processPayPal(){
	global $bookprs;
	
	echo "<script language=\"JavaScript\">";
	echo "document.write('<form action=\"paypal.php\" method=\"post\" name=\"formpaypal\">');";
	echo "document.write('<input type=\"hidden\" name=\"amount\"  value=\"".number_format($bookprs->totalPaymentAmount, 2, '.', '')."\">');";
	echo "document.write('<input type=\"hidden\" name=\"invoice\"  value=\"".$bookprs->bookingId."\">');";
	echo "document.write('</form>');";
	echo "setTimeout(\"document.formpaypal.submit()\",500);";
	echo "</script>";	
}

/* 2CHECK OUT PAYMENT */
function process2Checkout(){
	global $bookprs;
	global $bsiCore;	
	$paymentGatewayDetails = $bsiCore->loadPaymentGateways();	
	$responseURL = $bsiCore->getweburl()."2co.php";
	$paymentAmount = number_format($bookprs->totalPaymentAmount, 2, '.', '');
	
	echo "<script language=\"javascript\">";
	echo "document.write('<form action=\"https://www.2checkout.com/checkout/purchase\" method=\"post\" name=\"form2checkout\">');";
	echo "document.write('<input type=\"hidden\" name=\"id_type\" value=\"1\">');";
	echo "document.write('<input type=\"hidden\" name=\"demo\" value=\"Y\"/>');";
	echo "document.write('<input type=\"hidden\" name=\"x_invoice_num\" value=\"".$bookprs->bookingId."\"/>');";
	echo "document.write('<input type=\"hidden\" name=\"sid\" value=\"".$paymentGatewayDetails['2co']['account']."\">');";
	echo "document.write('<input type=\"hidden\" name=\"cart_order_id\" value=\"".$bsiCore->config['conf_hotel_name']."\">');";
	echo "document.write('<input type=\"hidden\" name=\"total\" value=\"".$paymentAmount."\">');";
	echo "document.write('<input type=\"hidden\" name=\"x_Receipt_Link_URL\" value=\"".$responseURL."\"/>');";
	echo "document.write('</form>');";
	echo "setTimeout(\"document.form2checkout.submit()\",500);";
	echo "</script>";	
}

/* AUTHORIZE.NET PAYMENT */
function processAuthorizeDotNet(){
	global $bookprs;
	global $bsiCore;	
	$paymentGatewayDetails = $bsiCore->loadPaymentGateways();			
	$accountdetails	= explode("=|=",$paymentGatewayDetails['an']['account']);
	$loginId		= $accountdetails[0];
	$transactionKey	= $accountdetails[1];
	$paymentAmount	= number_format($bookprs->totalPaymentAmount, 2, '.', '');
	$mrcSequence	= $bookprs->bookingId;
	$timeStamp		= time();
	$dateStamp 		= date("Ymd",$timeStamp).$timeStamp.date("His",$timeStamp);	
	$fingerPrint	= NULL;
	$responseURL	= $bsiCore->getweburl()."authorize.net.php";	 
	
	// The following lines generate the SIM fingerprint.
	// PHP versions 5.1.2 and newer have the necessary hmac function built in. 
	// For older versions, it will try to use the mhash library.
	if( phpversion() >= '5.1.2' ){ 
		$fingerPrint = hash_hmac("md5", $loginId . "^" . $mrcSequence . "^" . $timeStamp . "^" . $paymentAmount . "^", $transactionKey); 
	}else{ 
		$fingerPrint = bin2hex(mhash(MHASH_MD5, $loginId . "^" . $mrcSequence . "^" . $timeStamp . "^" . $paymentAmount . "^", $transactionKey)); 
	}
	
	echo "<script language=\"javascript\">";
	echo "document.write('<form action=\"https://secure.authorize.net/gateway/transact.dll\" method=\"post\" name=\"formauthorizedotnet\">');";
	echo "document.write('<input type=\"hidden\" name=\"x_cust_id\" value=\"".$bookprs->clientId."\" />');";
	echo "document.write('<input type=\"hidden\" name=\"x_login\" value=\"".$loginId."\" />');";
	echo "document.write('<input type=\"hidden\" name=\"x_amount\" value=\"".$paymentAmount."\" />');";
	echo "document.write('<input type=\"hidden\" name=\"x_description\" value=\"".$bsiCore->config['conf_hotel_name']."\" />');";
	echo "document.write('<input type=\"hidden\" name=\"x_invoice_num\" value=\"".$bookprs->bookingId."\" />');";
	echo "document.write('<input type=\"hidden\" name=\"x_fp_sequence\" value=\"".$mrcSequence."\" />');";
	echo "document.write('<input type=\"hidden\" name=\"x_fp_timestamp\" value=\"".$timeStamp."\" />');";
	echo "document.write('<input type=\"hidden\" name=\"x_fp_hash\" value=\"".$fingerPrint."\" />');";
	echo "document.write('<input type=\"hidden\" name=\"x_test_request\" value=\"FALSE\" />');";
	echo "document.write('<input type=\"hidden\" name=\"x_show_form\" value=\"PAYMENT_FORM\" />');";
	echo "document.write('<input type=\"hidden\" name=\"x_relay_response\" value=\"TRUE\">');";
	echo "document.write('<input type=\"hidden\" name=\"x_relay_url\" value=\"".$responseURL."\">');";  
	echo "document.write('<input type=\"hidden\" name=\"z_mcrorderid\" value=\"".$dateStamp."\">');";	
	echo "document.write('</form>');";
	echo "setTimeout(\"document.formauthorizedotnet.submit()\",500);";
	echo "</script>";
}

/* CREDIT CARD PAYMENT */
function processCreditCard(){
	/* not implemented yet */
	header('Location: booking-failure.php?error_code=22');
	die;
}

/* IPAY88/MOBILE88 PAYMENT */
function processiPay88(){
	/* not implemented yet */
	header('Location: booking-failure.php?error_code=22');
	die;
}

/* OTHER PAYMENT */
function processOther(){
	/* not implemented yet */
	header('Location: booking-failure.php?error_code=22');
	die;
}
?>