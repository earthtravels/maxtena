<?php
//--install-info-begin--//
// this section abaileble if script is not installed
$path_info = pathinfo($_SERVER['PHP_SELF']);
if ($path_info['basename'] !="install.php"){
header ("Location: install/install.php");
die;
}
$script_installed = false;
//--install-info-end--//

if($script_installed){	
//--donot-remove-this-line--//
define("MYSQL_SERVER", "localhost");
define("MYSQL_USER", "bsihotel");
define("MYSQL_PASSWORD", "abcxyz");
define("MYSQL_DATABASE", "bsidb");

mysql_connect(MYSQL_SERVER,MYSQL_USER,MYSQL_PASSWORD) or die ('I cannot connect to the database because 1: ' . mysql_error());
mysql_select_db(MYSQL_DATABASE) or die ('I cannot connect to the database because 2: ' . mysql_error());
//--donot-remove-this-line--//	
}
?>

