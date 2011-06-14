<?php
// TODO: Uncomment
include ("access.php");
include_once ("../includes/SystemConfiguration.class.php");

global $systemConfiguration;
global $logger;

// If we have id, then we are trying to delete
if (isset($_GET['id']) && is_numeric($_GET['id']))
{
	$id = $_GET['id'];
	NewsPost::delete($id);			
}
header ("Location: news_posts_list.php");
?>
