<?php
global $systeConfiguration;
global $logger;
$logger->LogInfo(__FILE__);

if (isset ($_REQUEST['lang']))
{
	$logger->LogInfo("Request contains language: " . $_REQUEST['lang']);
	$_SESSION['language_name'] = $_REQUEST['lang'];
}


// Determine language customer is using and source in language file
$language_selected = "none";
if (isset ($_SESSION['language_name']))
{
	$logger->LogDebug("Session contains language: " . $_SESSION['language_name']);
	$language_selected = $_SESSION['language_name'];	
}

$language = Language::fetchForCodeOrDefault($language_selected);
if ($language == null)
{
	$logger->LogError("Invalid language configuration. Could not find a default language.");
	die ("Invalid language configuration.");
}
$language_selected = $language->languageCode;
include_once ("languages/" . $language->fileName);
?>