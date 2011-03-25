<?php
session_start();
include ("../includes/db.conn.php"); // GLOBAL DB SETTINGS
include ('install.class.php');

$bsiStep = new bsiInstallFinish;

if(isset($_SESSION['step4'])){
	$_SESSION['finish'] = true; // step finish passed	
	unset($_SESSION['step4']);	
}

if(isset($_SESSION['step1'])) unset($_SESSION['step1']);
if(isset($_SESSION['step2'])) unset($_SESSION['step2']);
if(isset($_SESSION['step3'])) unset($_SESSION['step3']);	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>BSI Script Installation</title>
<link href="main.css" rel="stylesheet" type="text/css" />
</head>

<body>
<br />
<br />
<br />
<table align="center" width="60%" cellspacing="0" cellpadding="0" border="0"  class="table1">
  <tr>
    <td align="left" class="table_bg" colspan="2">INSTALLATION COMPLETED</td>
  </tr>
  <tr>
    <td class="table_line" colspan="2"><img src="spacer.gif" height="2" alt="" /></td>
  </tr>
  <tr>
    <td class="table_content" colspan="2" align="center"><br />
      <p style="padding:5px;"><b> Congratulation, You have succesfully installed PHP BSI Advance Hotel Booking System v1.0. </b></p></td>
  </tr>
  <tr>
    <td class="table_content" colspan="2"  align="center"><br />
      <span style="padding:5px;"> Administrator account has been succesfully created with below details: </span></td>
  </tr>
  <tr>
    <td class="table_content" align="right" width="50%"><b>Admin Login: </b></td>
    <td class="table_content" align="left"><span style="padding:5px;" class="green">
      <?=$bsiStep->adminUserName?>
      </span></td>
  <tr>
    <td class="table_content"  align="right"><b>Admin Password:</b></td>
    <td class="table_content" align="left"><span style="padding:5px;" class="green">
      <?=$bsiStep->adminUserPass?>
      </span></td>
  </tr>
  <tr>
    <td class="table_content" colspan="2" align="left"></td>
  </tr>
  <tr>
    <td class="table_content" align="right"><span style="padding:5px;"><b>User Portal Link:</b></span></td>
    <td class="table_content" align="left"><a href="<?=$bsiStep->userSitePath?>">
      <?=$bsiStep->userSitePath?>
      </a></td>
  </tr>
  <tr>
    <td class="table_content" align="right"><b>Admin Protal Link:</b></td>
    <td class="table_content" align="left"><a href="<?=$bsiStep->adminSitePath?>">
      <?=$bsiStep->adminSitePath?>
      </a></td>
  </tr>
  <tr>
    <td class="table_content" colspan="2" align="center"><br />
      <span style="padding:5px;" class="red"> Please remove "install" folder before proceed &nbsp;  -Recommended.</span></td>
  </tr>
   <tr>
    <td class="table_content" colspan="2" align="center"><br />
      <span style="padding:5px;" class="red"> Please change the file permission of &quot;db.conn.php&quot; to read only mode(chmod 644 db.conn.php) &nbsp;  -Recommended.</span></td>
  </tr>
  <tr>
    <td class="table_content" colspan="2" align="left"></td>
  </tr>
  <tr>
    <td class="table_content" colspan="2" align="center"><br />
      <span style="padding:5px;"><b>Thank you for choosing PHP BSI Advance Hotel Booking System.</b></span></td>
  </tr>
  <tr>
    <td class="table_line" colspan="2"><img src="spacer.gif" width="1" height="2" alt="" /></td>
  </tr>
</table>
</body>
</html>
