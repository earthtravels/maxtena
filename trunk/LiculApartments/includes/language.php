<?php
if (isset ($_REQUEST['lang']))
{
	$_SESSION['language'] = $_REQUEST['lang'];
	
	$host_info1 = pathinfo ($_SERVER["PHP_SELF"]);
	if ($host_info1['basename'] == "index.php")
		header ("location:" . $host_info1['basename']);
}

$row_default_lang = mysql_fetch_assoc (mysql_query ("select * from bsi_language where `default`=true"));
if (isset ($_SESSION['language']))
	$langauge_selected = $_SESSION['language'];
else
	$langauge_selected = $row_default_lang['lang_code'];

$row_visitor_lang = mysql_fetch_assoc (mysql_query ("select * from bsi_language where  lang_code='$langauge_selected' and status=true "));
include ("languages/" . $row_visitor_lang['lang_file_name']);
?>