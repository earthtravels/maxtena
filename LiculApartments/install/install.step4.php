<?php
session_start();
if (!$_SESSION['step1'] && !$_SESSION['step2'] && !$_SESSION['step3']){
	header ("Location: install.php");
	die;
}
$_SESSION['step4'] = true; // step 3 passed

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
<form action="install.finish.php" method="post">
  <table align="center" width="60%" cellspacing="0" cellpadding="0" border="0"  class="table1">
    <tr>
      <td align="left" class="table_bg" colspan="2">ADMINISTRATOR INFORMATION</td>
    </tr>
    <tr>
      <td class="table_line" colspan="2"><img src="spacer.gif" height="2" alt="" /></td>
    </tr>
    <tr>
      <td class="table_content" colspan="2" align="center"><br />
        Please put information for Administrator account creation. <br />
        Put the Hotel name and  Email address.<br />
        <br /></td>
    </tr>
    <tr>
      <td class="table_content_l" align="right" width="50%"><b>Administrator Login:</b><br>
        Leave balnk if auto generated required.</td>
      <td class="table_content_r" align="left" width="50%"><input name="admin_login" type="text" class="inputtext" /></td>
    </tr>
    <tr>
      <td class="table_content_l" align="right"><b>Administrator Password:</b><br>
        Leave balnk if auto generated required.</td>
      <td class="table_content_r" align="left"><input name="admin_password" type="password" class="inputtext" /></td>
    </tr>
    <tr>
      <td class="table_content" colspan="2">&nbsp;</td>
    </tr>
    <tr>
      <td class="table_content_l" align="right"><b>Hotel Name:</b></td>
      <td class="table_content_r" align="left"><input name="hotel_name" type="text" class="inputtext" /></td>
    </tr>
    <tr>
      <td class="table_content_l" align="right"><b>Hotel Email:</b></td>
      <td class="table_content_r" align="left"><input name="hotel_email" type="text" class="inputtext" /></td>
    </tr>
    <tr>
      <td class="table_content" colspan="2">&nbsp;<br />
        <br />
        <br />
        <br /></td>
    </tr>
    <tr>
      <td class="table_content" align="center" colspan="2"><input type="submit" value="Finish" class="button" /></td>
    </tr>
    <tr>
      <td class="table_line" colspan="2"><img src="spacer.gif" width="1" height="2" alt="" /></td>
    </tr>
  </table>
</form>
</body>
</html>
