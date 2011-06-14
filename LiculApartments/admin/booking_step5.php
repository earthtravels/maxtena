<?php
include_once ("../includes/SystemConfiguration.class.php");
include_once ("../languages/english.php");
$language_selected="en";


global $systemConfiguration;
global $logger;
session_start();


//$systemConfiguration->assertReferer("booking-details.php");
if(!isset($_SESSION['bookingDetailsAdmin']))
{
	$logger->LogError("BookingDetails not in session!");
	$_SESSION['errors'] = array();
	$_SESSION['errors'][] = "Session object was not set. Try the booking process from the start";
	header('Location: error.php'); 
}

// Get booking details from session
$bookingDetails = unserialize($_SESSION['bookingDetailsAdmin']);

// Get Client
$client = Client::fetchFromParameters($_POST);
if (!$client->isValid())
{
	$logger->LogError("Client is not valid!");
	$logger->LogError($client->errors);
	$_SESSION['errors'] =$client->errors;
	header('Location: booking-step4.php');
}
$bookingDetails->client = $client;

// Update client data
$client = Client::fetchFromDbForEmail($bookingDetails->client->email);
if ($client != null)
{
	$bookingDetails->client->id = $client->id;	
}
if (!$bookingDetails->client->save())
{
	$logger->LogError("Saving client failed!");
	$logger->LogError($client->errors);
	$_SESSION['errors'] = $bookingDetails->client->errors;
	header('Location: error.php');	
}


// Validate booking info
if (!$bookingDetails->isValid())
{
	$logger->LogError("BookingDetails are not valid!");
	$logger->LogError($bookingDetails->errors);
	$_SESSION['errors'] = $bookingDetails->errors;
	header('Location: error.php');
}

$paymentGateway = PaymentGateway::fetchFromDbForCode("ha");
if ($paymentGateway == null)
{
	$logger->LogError("Payment gateway code: ha not in database!");
	header('Location: error.php');
}
$bookingDetails->paymentGateway = $paymentGateway;

// Check for concurent booking
if (!$bookingDetails->isBookingStillAvailable())
{
	$_SESSION['errors'] = array();
	$_SESSION['errors'][] = "Another customer already booked the room in time requested";
	header('Location: error.php');	
}

$bookingDetails->generateBooking($language_selected);

// Generate invoice
$invoiceHtml = $bookingDetails->generateInvoice($language_selected);
$invoiceHtml.='<br />' ."\n";	
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
$invoiceHtml.='			N/A' ."\n";
$invoiceHtml.='		</td>' . "\n";
$invoiceHtml.='	</tr>' . "\n";
$invoiceHtml.='</table>' . "\n";
$bookingDetails->booking->invoice .= $invoiceHtml;

$bookingDetails->booking->paymentTransactionId = "N/A";
$bookingDetails->booking->paypalEmail = "";
$bookingDetails->booking->isPaymentSuccessful = true;
$logger->LogInfo("Running save ...");
if (!$bookingDetails->booking->save(true))
{
	$logger->LogFatal("Error saving booking!");
	$logger->LogFatal($bookingDetails->booking->errors);
	die ("Error: " . $bookingDetails->booking->errors[0]);
}
$logger->LogInfo("Save is successful.");
	
// Send email to client that txn was processed
$logger->LogInfo("Getting confirmation email contents ...");
$confirmationEmailContents = EmailContents::fetchFromDbForCode("Confirmation Email");			
if ($confirmationEmailContents == null)
{
	die("Booking was created but email could not be sent! Cannot find confirmation email details in database");	
}		

$client = $bookingDetails->booking->getClient();
$clientEmail = $client->email;

$logger->LogInfo("Personalizing email ...");
$emailPersonalizer = new EmailPersonalizer($bookingDetails->booking);
$emailBody = $emailPersonalizer->customizeEmailContents($confirmationEmailContents->emailText->getText($bookingDetails->booking->languageCode));
$logger->LogDebug("Email body:");
$logger->LogDebug($emailBody);			


$emailSender = new EmailSender();
$logger->LogInfo("Sending email ...");
if (!$emailSender->sendEmail($clientEmail, $confirmationEmailContents->emailSubject->getText($bookingDetails->booking->languageCode), $emailBody))
{
	$logger->LogError("Unable to send email confirmation to client.");
}


/* Notify Email for Hotel about Booking */
$notifyEmailSubject = "Booking no." . $bookingDetails->booking->id . " - Notification of Room Booking by ".$client->firstName . " " . $client->lastName;

$logger->LogInfo("Sending email to hotel email ...");
if (!$emailSender->sendEmail($systemConfiguration->getHotelDetails()->getHotelEmail(), $notifyEmailSubject, $invoiceHtml))
{
	$logger->LogError("Unable to send email tp hotel admint.");
}
header('Location: booking-confirm.php');

?>