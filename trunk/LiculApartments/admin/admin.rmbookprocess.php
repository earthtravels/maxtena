<?php
include ("access.php");
include ("../includes/db.conn.php");
include ('../includes/conf.class.php'); 
include ('../includes/mail.class.php');
include ("../includes/process.class.php");

$bookprs = new BookingProcess();

switch($bookprs->paymentGatewayCode){	
	case "pp": 		
		processPayPal();
		break;				
	case "2co":
		process2Checkout();
		break;	
	case "cc":
		processCreditCard();
		break;	
	case "poa":
		processPayOnArrival();
		break;	
	case "admin":
		processPayOnArrival();
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
	
	echo "<script language=\"javascript\">";
	echo "document.write('<form action=\"https://www.2checkout.com/checkout/purchase\" method=\"post\" name=\"form2checkout\">');";
	echo "document.write('<input type=\"hidden\" name=\"id_type\" value=\"1\">');";
	echo "document.write('<input type=\"hidden\" name=\"demo\" value=\"Y\"/>');";
	echo "document.write('<input type=\"hidden\" name=\"x_invoice_num\" value=\"".$bookprs->bookingId."\"/>');";
	echo "document.write('<input type=\"hidden\" name=\"sid\" value=\"".$paymentGatewayDetails['2co']['account']."\">');";
	echo "document.write('<input type=\"hidden\" name=\"cart_order_id\" value=\"".$bsiCore->config['conf_hotel_name']."\">');";
	echo "document.write('<input type=\"hidden\" name=\"total\" value=\"".number_format($bookprs->totalPaymentAmount, 2, '.', '')."\">');";
	echo "document.write('<input type=\"hidden\" name=\"x_Receipt_Link_URL\" value=\"http://".$_SERVER['HTTP_HOST']."/2co.php\"/>');";
	echo "document.write('</form>');";
	echo "setTimeout(\"document.form2checkout.submit()\",500);";
	echo "</script>";	
}

/* CREDIT CARD PAYMENT */
function processCreditCard(){
	/* not implemented yet */
	header('Location: booking-failure.php?error_code=22');
	die;
}

/* AUTHORIZE.NET PAYMENT */
function processAuthorizeDotNet(){
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