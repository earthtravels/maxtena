<?php
// Setup class
include_once ("includes/SystemConfiguration.class.php");
include ("includes/language.php");

global $systemConfiguration;
global $logger;
$logger->LogInfo(__FILE__);

$systemConfiguration->assertReferer();

$logger->LogInfo("Loading payment gateway for code 'pp' ...");
$paypalPaymentGateway = PaymentGateway::fetchFromDbForCode("pp");
if ($paypalPaymentGateway == null)
{
	$logger->LogError("Payment gateway coudl not be found!");
	header('Location: booking-failure.php?error_code=9');
}

$emailSender = new EmailSender();

require_once('paypal.class.php');


$p = new paypal_class;             
$p->paypal_url = $paypalPaymentGateway->getUrl();
// 'https://www.sandbox.paypal.com/cgi-bin/webscr';   // testing paypal url
// 'https://www.paypal.com/cgi-bin/webscr';     // paypal url
            
// setup a variable for this script (ie: 'http://www.micahcarrick.com/paypal.php')
$this_script = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];

// if there is not action variable, set the default action of 'process'
if (empty($_GET['action'])) $_GET['action'] = 'process';  

switch ($_GET['action']) {
	case 'process':      // Process and order...  
		$logger->LogInfo("Procesisng request  for payment to PayPal ...");  	   
		$p->add_field('business', $paypalPaymentGateway->account);
		$p->add_field('return', $this_script.'?action=success');
		$p->add_field('cancel_return', $this_script.'?action=cancel');
		$p->add_field('notify_url', $this_script.'?action=ipn');
		$p->add_field('item_name', $systemConfiguration->getHotelDetails()->getHotelName());
		$p->add_field('invoice', $_POST['invoice']);
		$p->add_field('currency_code', $systemConfiguration->getCurrencyCode()); 
		$p->add_field('amount', $_POST['amount']);		
		$p->submit_paypal_post(); // submit the fields to paypal
		//$p->dump_fields();      // for debugging, output a table of all the fields
      break;
      
	case 'success':      // Order was successful... 
		$logger->LogInfo("Successful order was processed!");
		header("location:booking-confirm.php");
		break;
      
	case 'cancel':       // Order was canceled...		
		// The order was canceled before being completed. 
		$logger->LogInfo("Order was cancelled!");
		header ("Location: booking-cancel.php");      
		break;
      
	case 'ipn':          // Paypal is calling page for IPN validation...     
      	$logger->LogInfo("Processing PayPal IPN ...");
		if ($p->validate_ipn())		
		{
			$logger->LogInfo("IPN validated successfully!");
			$bookingId = intval($p->ipn_data['invoice']);
			$transactionId = $p->ipn_data['txn_id'];
			$paypalEmail = strtolower(trim($p->ipn_data['payer_email']));
						
			$logger->LogInfo("Booking id = $bookingId.");
			$logger->LogInfo("Transaction id = $transactionId.");
			$logger->LogInfo("PayPalEmail = $paypalEmail.");
			$booking = Booking::fetchFromDb($bookingId);
			if ($booking == null)
			{
				$logger->LogFatal("Error fetching booking with id = $bookingId.");
				$logger->LogFatal(Booking::$staticErrors);							
				die("Cannot find booking details for id " . $bookingId);
			}
			$logger->LogInfo("Successfully fetched booking.");
	
			$logger->LogInfo("Compposing invoice ...");
			$invoiceHtml ='<br />' ."\n";	
			$invoiceHtml.='<table  style="font-family:Verdana, Geneva, sans-serif; font-size: 12px; background:#999999; width:700px; border:none;" cellpadding="4" cellspacing="1">' . "\n";
			$invoiceHtml.='	<tr>' . "\n";
			$invoiceHtml.='		<td align="left" colspan="2" style="font-weight:bold; font-variant:small-caps; background:#eeeeee;">' . "\n";
			$invoiceHtml.='			' . BOOKING_DETAILS_BILLING . "\n";
			$invoiceHtml.='		</td>' . "\n";
			$invoiceHtml.='	</tr>' . "\n";
			$invoiceHtml.='	<tr>' . "\n";
			$invoiceHtml.='		<td align="left" width="30%" style="font-weight:bold; font-variant:small-caps;background:#ffffff;" width="33%">' . "\n";
			$invoiceHtml.='			' . BOOKING_DETAILS_PAYMENT_OPTION . "\n";
			$invoiceHtml.='		</td>' . "\n";
			$invoiceHtml.='		<td align="left" style="background:#ffffff;">' . "\n";
			$invoiceHtml.='			' . $paypalPaymentGateway->gatewayName->getText($language_selected) ."\n";
	 		$invoiceHtml.='		</td>' . "\n";
			$invoiceHtml.='	</tr>' ."\n";
			$invoiceHtml.='	<tr>' ."\n";
			$invoiceHtml.='		<td align="left" width="30%" style="font-weight:bold; font-variant:small-caps; background:#ffffff;" width="33%">' . "\n";
			$invoiceHtml.='			' . BOOKING_DETAILS_TRANSACTION . "\n";
			$invoiceHtml.='		</td>' . "\n";
			$invoiceHtml.='		<td align="left" style="background:#ffffff;">' . "\n";
			$invoiceHtml.='			' . $transactionId ."\n";
			$invoiceHtml.='		</td>' . "\n";
			$invoiceHtml.='	</tr>' . "\n";
			$invoiceHtml.='</table>' . "\n";
			
			$booking->invoice .= $invoiceHtml;
			$booking->paymentTransactionId = $transactionId;
			$booking->paypalEmail = $paypalEmail;
			$booking->isPaymentSuccessful = true;
			$logger->LogInfo("Saving booking ...");
			if (!$booking->save(true))
			{
				$logger->LogFatal("Error saving booking!");
				$logger->LogFatal($booking->errors);
				die ("Error: " . $booking->errors[0]);
			}
			$logger->LogInfo("Save is successful.");
				
			// Send email to client that txn was processed
			$logger->LogInfo("Getting confirmation email contents ...");
			$confirmationEmailContents = EmailContents::fetchFromDbForCode("Confirmation Email");			
			if ($confirmationEmailContents == null)
			{
				$logger->LogError("Cannot find confirmation email contents!");
				$logger->LogError(EmailContents::$staticErrors);					
			}		
			
			$client = $booking->getClient();
			$clientEmail = $client->email;
			
			$logger->LogInfo("Personalizing email ...");
			$emailPersonalizer = new EmailPersonalizer($booking);
			$emailBody = $emailPersonalizer->customizeEmailContents($confirmationEmailContents->emailText->getText($booking->languageCode));
			$logger->LogDebug("Email body:");
			$logger->LogDebug($emailBody);			
	
			
			$logger->LogInfo("Sending email to client...");
			if (!$emailSender->sendEmail($clientEmail, $confirmationEmailContents->emailSubject->getText($booking->languageCode), $emailBody))
			{
				$logger->LogError("Failed to send confirmation email to client.");
			}
			
			/* Notify Email for Hotel about Booking */
			$notifyEmailSubject = "Booking no.".$bookingId." - Notification of Room Booking by ".$client->firstName . " " . $client->lastName;
			
			$logger->LogInfo("Sending email to hotel admin...");
			if (!$emailSender->sendEmail($systemConfiguration->getHotelDetails()->getHotelEmail(), $notifyEmailSubject, $invoiceHtml))
			{
				$logger->LogError("Failed to send email to the hotel admin.");
			}			
		}
		else 
		{
			$logger->LogWarn("IPN was not processed successfully!");
			$ipnData = file_get_contents($p->ipn_log_file);
			$logger->LogWarn("IPN data:");
			$logger->LogWarn($ipnData);			
		}
		break;
 }
?>
