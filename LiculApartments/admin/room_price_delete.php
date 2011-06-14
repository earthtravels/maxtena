<?php
include ("access.php");
include_once ("../includes/SystemConfiguration.class.php");

// If we have id, then we are trying to delete
if (isset($_GET['id']) && is_numeric($_GET['id']))
{
	$id = $_GET['id'];
	$deleteRoomPriceSql = "DELETE FROM bsi_room_price WHERE id = " . $id;
	if (!mysql_query($deleteRoomPriceSql))	
	{
		die('Error: ' . mysql_error());
	}		
}
header ("Location: room_price_list.php");
