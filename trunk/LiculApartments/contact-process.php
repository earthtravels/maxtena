<?php
include_once ("includes/SystemConfiguration.class.php");
include ("includes/language.php");

global $systemConfiguration;
global $logger;
session_start ();

$systemConfiguration->assertReferer();


if (!isset($_REQUEST['form']) || sizeof($_REQUEST['form']) == 0)
{
	$_SESSION['errors'] = array();
	$_SESSION['errors'][] = BOOKING_FAILURE_INVALID_REQUEST;
	header("Location: error.php");
}

$emailSender = new EmailSender();
if ($emailSender->sendContactEmail($_REQUEST['form']))
{
	header("Location: contact-success.php");
}
else
{
	$_SESSION['errors'] = array();
	$_SESSION['errors'][] = CONTACT_SEND_ERROR . " " . $systemConfiguration->getHotelDetails()->getHotelEmail();
	header("Location: error.php");
}
?>