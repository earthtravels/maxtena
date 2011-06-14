<?php
include_once ("includes/SystemConfiguration.class.php");
include ("includes/language.php");


global $systemConfiguration;
global $logger;
session_start ();
$logger->LogInfo(__FILE__);

$systemConfiguration->assertReferer();

$logger->LogInfo("Getting object from session ...");
if(!isset($_SESSION['bookingDetails']))
{
	$logger->LogError("BookingDetails not in session!");
	header('Location: booking-failure.php?error_code=9'); 
}

// Get booking details from session
$bookingDetails = unserialize($_SESSION['bookingDetails']);

// Get Client
$logger->LogInfo("Getting client from POST parameters ...");
$client = Client::fetchFromParameters($_POST);
if (!$client->isValid())
{
	$logger->LogError("Client is not valid!");
	$logger->LogError($client->errors);
	$_SESSION['errors'] =$client->errors;
	header('Location: booking-details.php');
}
$bookingDetails->client = $client;

// Update client data
$logger->LogInfo("Cheking if client exists in the database ...");
$client = Client::fetchFromDbForEmail($bookingDetails->client->email);
if ($client != null)
{
	$logger->LogInfo("Found client with email " . $bookingDetails->client->email);
	$bookingDetails->client->id = $client->id;	
}
else
{
	$logger->LogInfo("Client not found!");
}

$logger->LogInfo("Saving client ...");
if (!$bookingDetails->client->save())
{
	$logger->LogError("Saving client failed!");
	$logger->LogError($client->errors);
	$_SESSION['errors'] = $bookingDetails->client->errors;
	header('Location: booking-failure.php');	
}


// Validate booking info
$logger->LogInfo("Checking if booking details object is valid ...");
if (!$bookingDetails->isValid())
{
	$logger->LogError("BookingDetails are not valid!");
	$logger->LogError($bookingDetails->errors);
	$_SESSION['errors'] = $bookingDetails->errors;
	header('Location: booking-failure.php');
}

// Get payment gateway
$logger->LogInfo("Looking up payment gateway ...");
if (!isset($_POST['payment_gateway_code']))
{
	$logger->LogError("Payment gateway code not in POST!");
	header('Location: booking-failure.php?error_code=9');
}

$logger->LogInfo("Fetching payment gateway for code: " . $_POST['payment_gateway_code'] . "...");
$paymentGateway = PaymentGateway::fetchFromDbForCode($_POST['payment_gateway_code']);
if ($paymentGateway == null)
{
	$logger->LogError("Payment gateway code: " . $_POST['payment_gateway_code'] . " not in database!");
	header('Location: booking-failure.php?error_code=9');
}
$bookingDetails->paymentGateway = $paymentGateway;

// Check for concurent booking
$logger->LogInfo("Checking for concurrent booking ...");
if (!$bookingDetails->isBookingStillAvailable())
{
	$logger->LogWarn("Concurrent booking found!");
	header('Location: booking-failure.php?error_code=13');	
}

// Save booking
$logger->LogInfo("Saving booking ...");
if (!$bookingDetails->saveBooking($language_selected))
{
	$logger->LogError("Saving booking failed!");
	$logger->LogError($bookingDetails->errors);
	$_SESSION['errors'] = $bookingDetails->errors;
	header('Location: booking-failure.php');	
}

// Save invoice
$logger->LogInfo("Saving invoice ...");
if (!$bookingDetails->saveInvoice($language_selected))
{
	$logger->LogError("Saving invoice failed!");
	$logger->LogError($bookingDetails->errors);
	$_SESSION['errors'] = $bookingDetails->errors;
	header('Location: booking-failure.php');
} 


switch($bookingDetails->paymentGateway->gatewayCode)
{	
	case "poa":
		processPayOnArrival();
		break;
		
	case "pp": 		
		processPayPal();
		break;
				
	default:
		processOther();
		break;
}


/* PAY ON ARIVAL: MANUAL PAYMENT */	
function processPayOnArrival()
{	
	global $bookingDetails;
	global $systemConfiguration;	
	global $logger;	
	global $language_selected;
	
	$logger->LogInfo("Processing payment on arrival ...");
	
	$transactionId = "N/A";
	$booking = $bookingDetails->booking;
	$paymentGateway = $bookingDetails->paymentGateway;

	$logger->LogInfo("Creating bottom portion of invoice ...");
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
	$invoiceHtml.='			' . $paymentGateway->gatewayName->getText($language_selected) ."\n";
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
	$booking->paypalEmail = "";
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
		$logger->LogFatal("Could not find confirmation email contents.");
		die("Cannot find confirmation email details");	
	}		
	
	$client = $booking->getClient();
	$clientEmail = $client->email;
	
	$logger->LogInfo("Personalizing email ...");
	$emailPersonalizer = new EmailPersonalizer($booking);
	$emailBody = $emailPersonalizer->customizeEmailContents($confirmationEmailContents->emailText->getText($booking->languageCode));
	$logger->LogDebug("Email body:");
	$logger->LogDebug($emailBody);			

	
	$logger->LogInfo("Sending email to client at " . $clientEmail . " ...");
	$emailSender = new EmailSender();
	if (!$emailSender->sendEmail($clientEmail, $confirmationEmailContents->emailSubject->getText($booking->languageCode), $emailBody))
	{
		$logger->LogError("Failed to send confirmation email to client.");
	}
	
	/* Notify Email for Hotel about Booking */
	$notifyEmailSubject = "Booking no.".$booking->id." - Notification of Room Booking by ".$client->firstName . " " . $client->lastName;
	
	$logger->LogInfo("Sending email to hotel admin...");
	if (!$emailSender->sendEmail($systemConfiguration->getHotelDetails()->getHotelEmail(), $notifyEmailSubject, $invoiceHtml))
	{
		$logger->LogError("Failed to send email to the hotel admin.");
	}
	
	header('Location: booking-confirm.php');
}

/* PAYPAL PAYMENT */ 
function processPayPal()
{
	global $bookingDetails;
	global $systemConfiguration;
	
	echo "<script language=\"JavaScript\">";
	echo "document.write('<form action=\"paypal.php\" method=\"post\" name=\"formpaypal\">');";
	echo "document.write('<input type=\"hidden\" name=\"amount\"  value=\"".number_format($bookingDetails->priceDetails->totalDue, 2, '.', '')."\">');";
	echo "document.write('<input type=\"hidden\" name=\"invoice\"  value=\"".$bookingDetails->booking->id."\">');";
	echo "document.write('</form>');";
	echo "setTimeout(\"document.formpaypal.submit()\",500);";
	echo "</script>";	
}

/* OTHER PAYMENT */
function processOther(){
	/* not implemented yet */
	header('Location: booking-failure.php?error_code=22');
	die;
}
?>