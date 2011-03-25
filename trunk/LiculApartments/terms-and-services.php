<?php 
session_start();
include("includes/db.conn.php");
include("includes/language.php");
include("includes/conf.class.php");

$body_content=mysql_fetch_assoc(mysql_query("select * from bsi_contents where id=5"));
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=$bsiCore->config['conf_hotel_name']?> : <?=$body_content['cont_title']?></title>
</head>

<body>
<?=html_entity_decode($body_content['contents_'.$langauge_selcted])?>
</body>
</html>
