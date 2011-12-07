<?php
include ("access.php");
include("../includes/db.conn.php");
include("../includes/conf.class.php");
include("../includes/admin.ajaxprocess.class.php");	
$adminAjaxProc = new adminAjaxProcessor();

$actionCode = isset($_POST['actioncode']) ? $_POST['actioncode'] : 0;
switch($actionCode){
	case "1": $adminAjaxProc->getdefaultcapacity(); break;
	case "2": $adminAjaxProc->getroomtypecapacity(); break;
	case "3": $adminAjaxProc->getroomnumbers(); break;
	case "4": $adminAjaxProc->getdiscountdepoitenabled(); break;
	case "5": $adminAjaxProc->getdiscountupdate(); break;
	case "6": $adminAjaxProc->getdepositupdate(); break;
	case "7": $adminAjaxProc->addhotelextras(); break;
	case "8": $adminAjaxProc->adddiscountcoupon(); break;
	case "9": $adminAjaxProc->blockroombyadmin(); break;
	case "10": $adminAjaxProc->unblockRoom(); break;
	case "11": $adminAjaxProc->booking_search(); break;
	default:  $adminAjaxProc->sendErrorMsg();
}
?>