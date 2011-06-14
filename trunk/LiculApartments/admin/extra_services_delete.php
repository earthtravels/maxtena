<?php
include_once ("access.php");
include_once ("../includes/SystemConfiguration.class.php");

// If we have id, then we are trying to delete
if (isset($_GET['id']) && is_numeric($_GET['id']))
{
	$id = $_GET['id'];
	ExtraService::delete($id);			
}
header ("Location: extra_services_list.php");
?>
