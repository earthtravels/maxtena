<?php
include("includes/db.conn.php");
include("includes/conf.class.php");


$responsecode		= $bsiCore->ClearInput($_POST["x_response_code"]);
$responsereasoncode	= $bsiCore->ClearInput($_POST["x_response_reason_code"]);
$responsereasontext	= $bsiCore->ClearInput($_POST["x_response_reason_text"]);
$authcode			= $bsiCore->ClearInput($_POST["x_auth_code"]);
$transid			= $bsiCore->ClearInput($_POST["x_trans_id"]);
$cardtype			= $bsiCore->ClearInput($_POST["x_card_type"]);
$accounnumber		= $bsiCore->ClearInput($_POST["x_account_number"]);
$firstname			= $bsiCore->ClearInput($_POST["x_first_name"]);
$lastname			= $bsiCore->ClearInput($_POST["x_last_name"]);
$invoicenum			= $bsiCore->ClearInput($_POST["x_invoice_num"]);
$amount				= $bsiCore->ClearInput($_POST["x_amount"]);
$MD5Hash			= $bsiCore->ClearInput($_POST["x_MD5_Hash"]);
$mcrorderid			= $bsiCore->ClearInput($_POST["z_mcrorderid"]);

$paymentGatewayDetails = $bsiCore->loadPaymentGateways();	
$accountdetails	= explode("=|=",$paymentGatewayDetails['an']['account']);
$loginId		= trim($accountdetails[0]);
$transactionKey	= trim($accountdetails[1]);
$fingerPrint	= NULL;


//hasvalue: mybsidemo
/*
if( phpversion() >= '5.1.2' ){ 
	$fingerPrint = hash_hmac("md5", $loginId . "^" . $invoicenum . "^" . substr($mcrorderid,8,10) . "^" . $amount . "^", $transactionKey); 
}else{ 
	$fingerPrint = bin2hex(mhash(MHASH_MD5, $loginId . "^" . $invoicenum . "^" . substr($mcrorderid,8,10) . "^" . $amount . "^", $transactionKey)); 
}
*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
</head>

<body><br /><br /><?php
if($_SERVER['HTTP_REFERER']== "" and $responsecode > 0){
	if($responsecode == 1 || $responsecode == 4){
		mysql_query("UPDATE bsi_bookings SET payment_success=true, payment_txnid='".$transid."' WHERE booking_id='".$invoicenum."'");	
		$invoiceROWS = mysql_fetch_assoc(mysql_query("SELECT client_name, client_email, invoice FROM bsi_invoice WHERE booking_id='".$invoicenum."'"));
		mysql_query("UPDATE bsi_clients SET existing_client = 1 WHERE email = '".$invoiceROWS['client_email']."'");		
		$invoiceHTML = $invoiceROWS['invoice'];		
		$invoiceHTML.= '<br><br><table  style="font-family:Verdana, Geneva, sans-serif; font-size: 12px; background:#999999; width:700px; border:none;" cellpadding="4" cellspacing="1"><tr><td align="left" colspan="2" style="font-weight:bold; font-variant:small-caps; background:#eeeeee">Payment Details</td></tr><tr><td align="left" width="30%" style="font-weight:bold; font-variant:small-caps; background:#ffffff">Payment Option</td><td align="left" style="background:#ffffff">Authorize.Net</td></tr><tr><td align="left" width="30%" style="font-weight:bold; font-variant:small-caps; background:#ffffff">Order Number</td><td align="left" style="background:#ffffff">'.$transid.'</td></tr></table>';		
		mysql_query("UPDATE bsi_invoice SET invoice = '$invoiceHTML' WHERE booking_id='".$invoicenum."'");
		
		include("includes/mail.class.php");
		$bsiMail = new bsiMail();
	
		$emailBody = "Dear ".$invoiceROWS['client_name'].",<br><br>";
		$emailBody .= $bsiMail->emailContent['body']."<br><br>";
		$emailBody .= $invoiceHTML;
		$emailBody .= '<br><br>Regards,<br>'.$bsiCore->config['conf_hotel_name'].'<br>'.$bsiCore->config['conf_hotel_phone'];
		$emailBody .= "<br><br><font style=\"color:#F00; font-size:10px;\">[ You will need to carry a print out of this e-mail and present it to the hotel on arrival and check-in. This e-mail is the confirmation voucher for your booking. ]</font>";
		
		$bsiMail->sendEMail($invoiceROWS['client_email'], $bsiMail->emailContent['subject'], $emailBody);
		
		/* Notify Email for Hotel about Booking */
		$notifyEmailSubject = "Booking no.".$invoicenum." - Notification of Room Booking by ".$invoiceROWS['client_name'];
			
		$bsiMail->sendEMail($bsiCore->config['conf_hotel_email'], $notifyEmailSubject, $invoiceHTML);
		
		echo '<table cellpadding="4" cellspacing="0" style="border:solid 2px #009933;" border="0" align="center"><tr><td>';
		echo '<table cellpadding="4" cellspacing="0" border="0" style="border:solid 2px #009933; font-family:Arial, Helvetica, sans-serif; font-size:13px;" align="center" width="450">';
		echo '<tr><td align="center" style="font-weight:bold; font-size:16px;">Congratulation!!</td></tr>';		
		
		if($responsecode == 1){
			echo '<tr><td align="center" style="font-weight:bold;">'.$responsereasontext.'</td></tr>';
			echo '<tr><td align="center" style="font-weight:bold; font-size:16px;">Your booking is confirmed!!</td></tr>';
		}else{
			echo '<tr><td align="center" style="font-weight:bold;">Your booking is confirmed temporarily because '.$responsereasontext.'</td></tr>';
			echo '<tr><td align="center" style="font-weight:bold; font-size:16px;">After review if transaction not approved, your booking will be canceled.</td></tr>';	
		}
		
		echo '<tr><td align="center" style="font-weight:bold; font-size:16px;">Thank You!!</td></tr>';
		echo '<tr><td align="left" style="font-weight:bold;"></td></tr>';
		
		echo '<tr><td ><ul><li>If you you have any query please ccontact: &nbsp;'.$bsiCore->config['conf_hotel_email'].'</li></ul></td></tr>';
		echo '</table></td></tr></table>';
	} else {
		echo '<h3>Something went wrong, and your booking has not been completed. Reason: '.$responsereasontext.'<br />Please contact: &nbsp; '.$bsiCore->config['conf_hotel_email'].'</h3>';
	}
}else{
	echo '<h3>This transaction is not authorized. Your booking is failed. Please contact: &nbsp; '.$bsiCore->config['conf_hotel_email'].'</h3>';
	
	//print_r($_POST);
	//echo "<BR>".$fingerPrint ."<BR>". $MD5Hash;
	
	
}
?>
</body>
</html>
