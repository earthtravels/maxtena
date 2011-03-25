<?php
session_start();
if (!$_SESSION['step1']){
	header ("Location: install.php");
	die;
}
$_SESSION['step2'] = true; // step 2 passed
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
<form action="install.step3.php" method="post">
  <table align="center" width="60%" cellspacing="0" cellpadding="0" border="0"  class="table1">
    <tr>
      <td align="left" class="table_bg" colspan="2">DATABASE INFORMATION</td>
    </tr>
    <tr>
      <td class="table_line" colspan="2"><img src="spacer.gif" height="2" alt="" /></td>
    </tr>
    <tr>
      <td class="table_content" colspan="2" align="center"><br />
        Script needs to know some information about how your database is setup.<br />
        Please input the database name, mysql login and password, and hostname below.<br />
        The <b>&quot;hostname&quot;</b> is NOT the domain name of your server.<br />
        Usually, the hostname is <b>&quot;localhost&quot;</b>, but you should contact your website administrator or web host to be sure.<br />
        <br /></td>
    </tr>
    <tr>
      <td class="table_content_l" align="right" width="50%"><b>Database Host:</b></td>
      <td class="table_content_r" align="left" width="50%"><input name="mysql_host" type="text" value="localhost" class="inputtext" /></td>
    </tr>
    <tr>
      <td class="table_content_l" align="right"><b>Database Name:</b></td>
      <td class="table_content_r" align="left"><input name="mysql_db" type="text" value="" class="inputtext" /></td>
    </tr>
    <tr>
      <td class="table_content_l" align="right"><b>Database Login:</b></td>
      <td class="table_content_r" align="left"><input name="mysql_login" type="text" value="" class="inputtext" /></td>
    </tr>
    <tr>
      <td class="table_content_l" align="right"><b>Database Password:</b></td>
      <td class="table_content_r" align="left"><input name="mysql_password" type="text" value="" class="inputtext" /></td>
    </tr>
    <tr>
      <td class="table_content" colspan="2">&nbsp;<br />
        <br />
        <br />
        <br /></td>
    </tr>
    <tr>
      <td class="table_content" align="center" colspan="2"><input type="submit" value="Step 3 &raquo;" class="button" /></td>
    </tr>
    <tr>
      <td class="table_line" colspan="2"><img src="spacer.gif" width="1" height="2" alt="" /></td>
    </tr>
  </table>
</form>
</body>
</html>
