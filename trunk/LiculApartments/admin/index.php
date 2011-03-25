<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>BSI Admin Panel</title>
<link href="style.css" rel="stylesheet" type="text/css" />
</head>
<body>
<form action="login.php" method="post" >
<table align="center" width="950" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="366">&nbsp;</td>
    <td width="584">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" valign="bottom"><img src="images/adminis.gif" alt="" /></td>
    <td align="right" valign="bottom" class="bodyMenutext8pt"><a href="#" class="bodyMenutext8pt">Visit Site</a><a href="#" class="bodyMenutext8pt"></a></td>
  </tr>
  <tr>
    <td height="10"></td>
    <td></td>
  </tr>
  <tr>
    <td colspan="2" height="2" bgcolor="#718d9d"></td>
  </tr>
</table>
<table align="center" width="950" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="left">&nbsp;</td>
  </tr>
  <tr>
    <td height="400" align="center" valign="middle">
	<table width="337" style="background-image:url(images/box_tile.gif);" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td colspan="4" height="30" valign="top"><img src="images/box_top.gif" alt="" width="337" height="10" /></td>
  </tr>
  <tr>
    <td width="25" align="right" class="Titlewhite10pt" height="30" valign="middle">&nbsp;</td>
    <td width="85" align="left" class="Titlewhite10pt" valign="middle">Email ID</td>
    <td width="25">&nbsp;</td>
    <td width="202"  align="left" class="Titlewhite10pt" height="30" valign="middle"><input type="text" name="username" size="30" style="height:18px;" /></td>
  </tr>
  <tr>
    <td colspan="4" height="10"></td>
  </tr>
  <tr>
    <td height="30" align="right" valign="middle" class="Titlewhite10pt">&nbsp;</td>
    <td height="30" align="left" valign="middle" class="Titlewhite10pt">Password</td>
    <td width="25">&nbsp;</td>
    <td width="202" valign="middle" align="left"><input type="password" name="password" size="30" style="height:18px;" /></td>
  </tr>
  <tr>
    <td colspan="4" height="10"></td>
  </tr>
  <tr>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center"><input type="image" src="images/but_enter.gif"  name="submit" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
  </tr>
  <tr>
    <td colspan="4"><img src="images/box_bottom.gif" alt="" width="337" height="10" /></td>
  </tr>
</table><?php
			if(isset($_REQUEST['error']))
			{
			echo "<div class=\"bodytext\"><font color=red>Username and password do not match. Try again.</font></div>";
			}
			?>
</td>
  </tr>
</table>
<table align="center" width="950" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="right" class="bodytext">&nbsp;</td>
  </tr>
  <?php include("footer.php"); ?>
</table>
</form>
</body>
</html>
