<?
include ("access.php");
error_reporting(0);
include("../includes/db.conn.php"); 
include("../includes/admin.ajaxprocess.class.php");	
$adminAjaxProc = new adminAjaxProcessor();
$adminAjaxProc->block_xml_calendar();
?>