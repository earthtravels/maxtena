<?php
session_start();
include("includes/db.conn.php");
include("includes/language.php");
include("includes/conf.class.php");
include("includes/mail.class.php");	
include("includes/ajaxprocess.class.php");	
$ajaxProc = new ajaxProcessor();

switch($ajaxProc->actionCode){
	case "1": 
		$ajaxProc->getBookingStatus(); 
		break;
		
	case "2": 
		$ajaxProc->getCustomerDetails();
		break;
		
	case "3": 
		$ajaxProc->sendContactMessage();
		break;
		
	case "4":
		$ajaxProc->applyCouponDiscount();
		break;
		
	default:
		$ajaxProc->sendErrorMsg();
}
?>