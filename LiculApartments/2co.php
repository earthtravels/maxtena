<?php
include("includes/db.conn.php");
include("includes/conf.class.php");
include("includes/mail.class.php");
$bsiMail = new bsiMail();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
</head>

<body><br /><br /><?php
if($_POST['credit_card_processed'] == 'Y'){
		mysql_query("UPDATE bsi_bookings SET payment_success=true, payment_txnid='".$bsiCore->ClearInput($_POST['order_number'])."' WHERE booking_id='".$bsiCore->ClearInput($_POST['x_invoice_num'])."'");
			
	
		$invoiceROWS = mysql_fetch_assoc(mysql_query("SELECT client_name, client_email, invoice FROM bsi_invoice WHERE booking_id='".$bsiCore->ClearInput($_POST['x_invoice_num'])."'"));
		mysql_query("UPDATE bsi_clients SET existing_client = 1 WHERE email = '".$invoiceROWS['client_email']."'");
		
		$invoiceHTML = $invoiceROWS['invoice'];		
		$invoiceHTML.= '<br><br><table style="font-family:Verdana, Geneva, sans-serif; font-size: 12px; bgcolor:#999999; width:700px; border:none;" cellpadding="4" cellspacing="1"><tr><td align="left" colspan="2" style="font-weight:bold; font-variant:small-caps; background:#ffffff">Payment Details</td></tr><tr><td align="left" width="30%" style="font-weight:bold; font-variant:small-caps; background:#ffffff">Payment Option</td><td align="left" style="background:#ffffff">2Checkout</td></tr><tr><td align="left" width="30%" style="font-weight:bold; font-variant:small-caps; background:#ffffff">Order Number</td><td align="left" style="background:#ffffff">'.$bsiCore->ClearInput($_POST['order_number']).'</td></tr></table>';
		
		mysql_query("UPDATE bsi_invoice SET invoice = '$invoiceHTML' WHERE booking_id='".$bsiCore->ClearInput($_POST['x_invoice_num'])."'");

		$emailBody = "Dear ".$invoiceROWS['client_name'].",<br><br>";
		$emailBody .= $bsiMail->emailContent['body']."<br><br>";
		$emailBody .= $invoiceHTML;
		$emailBody .= '<br><br>Regards,<br>'.$bsiCore->config['conf_hotel_name'].'<br>'.$bsiCore->config['conf_hotel_phone'];
		$emailBody .= "<br><br><font style=\"color:#F00; font-size:10px;\">[ You will need to carry a print out of this e-mail and present it to the hotel on arrival and check-in. This e-mail is the confirmation voucher for your booking. ]</font>";
		
		$bsiMail->sendEMail($invoiceROWS['client_email'], $bsiMail->emailContent['subject'], $emailBody);
		
		/* Notify Email for Hotel about Booking */
		$notifyEmailSubject = "Booking no.".$bsiCore->ClearInput($_POST['x_invoice_num'])." - Notification of Room Booking by ".$invoiceROWS['client_name'];
			
		$bsiMail->sendEMail($bsiCore->config['conf_hotel_email'], $notifyEmailSubject, $invoiceHTML);
		
?>
<table cellpadding="4" cellspacing="0" style="border:solid 2px #009933;" border="0" align="center"><tr><td>
<table cellpadding="4" cellspacing="0" border="0" style="border:solid 2px #009933; font-family:Arial, Helvetica, sans-serif; font-size:13px;" align="center" width="450">
<tr><td align="center" style="font-weight:bold; font-size:16px;">Congratulation!!</td></tr>
<tr><td align="center" style="font-weight:bold;">Your Transaction is Successful.</td></tr>
<tr><td align="center" style="font-weight:bold; font-size:16px;">Thank You!!</td></tr>
<tr><td align="left" style="font-weight:bold;"></td></tr>

<tr><td ><ul>
<li>if you you have any query please contact <?=$bsiCore->config['conf_hotel_email']?>.</li>
</ul></td></tr>
</table></td></tr></table>
<?php } else {
echo "<h1>Something went wrong, and your order has not been completed. Please contact ".$bsiCore->config['conf_hotel_email']."</h1>";
}
?>
</body>
</html>
