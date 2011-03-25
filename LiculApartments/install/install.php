<?php
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>BSI Script Installation</title>
<link href="main.css" rel="stylesheet" type="text/css" /><script language="javascript">
function licenceAggrement() {
	var radio = document['lincence'].elements['aggrement'][0];	
 	if(radio.checked){ 
		return true;
	}
	alert('Before proceed please read end user licence agreement and accept it');
	return false;
} 
</script>
</head>

<body>
<br />
<br />
<br />
<form name="lincence" action="install.step1.php" method="post" onsubmit="return licenceAggrement();">
  <table align="center" width="60%" cellspacing="0" cellpadding="0" border="0"  class="table1">
 
      <td align="left" class="table_bg" colspan="2">BSI ADVANCE HOTEL BOOKING SYSTEM</td>
    </tr>
    <tr>
      <td class="table_line"><img src="spacer.gif" height="2" alt="" /></td>
    </tr>
    <tr>
      <td class="table_content"><p class="block1" align="center">END USER LICENSE AGREEMENT</p>
        <p class="block2" align="center">(http://www.bestsoftinc.com) </p>
    </tr>
    <tr>
      <td class="table_content" align="justify" colspan="2"> <table align="center" width="70%" cellspacing="0" cellpadding="0" border="0"  class="table1">
    <tr><td  class="table_content" align="justify" colspan="2"> All copyrights to BSI Advance Hotel Booking System are exclusively owned by the authors: Best Soft Inc (http://www.bestsoftinc.com) </td>
    </tr>
    <tr>
      <td class="table_content" align="justify" colspan="2">The software is commercial software, only users who have purchased a valid license through Bestsoftinc.com agree to the terms of this EULA can Install this product.</td>
    </tr>
    <tr>
      <td class="table_content" align="justify" colspan="2">This EULA is a CONTRACT between you and BestSoft Inc which covers your use of the software product that accompanies this EULA. The software will be referred to herein as the "Software Product". A software license, issued to a designated user only by BestSoft Inc, is required for each user of the Software Product.</td>
    </tr>
    <tr>
      <td class="table_content" align="justify" colspan="2">A software license is required for each installation of the software purchased by explicitly accepting this EULA or by purchasing/downloading or installing the software you are acknowledging and agreeing to the terms outlined below: </td>
    </tr>
    <tr>
      <td class="table_content" align="justify" colspan="2">You may not emulate, clone, rent, lease, sell, or transfer the licensed software, or any subset of the licensed program, except as provided for in this agreement. Any such unauthorized use shall result in immediate and automatic termination of this license and may result in criminal and/or civil prosecution.</td>
    </tr>
    <tr>
      <td class="table_content" align="center" colspan="2"> All rights not expressly granted here are reserved by: H Khan </td>
    </tr></table></td></tr>
    <tr>
      <td class="table_content" align="center" colspan="2"> Thank you for using BSI Advance Hotel Booking System.<br />
        (http://www.bestsoftinc.com)<br />
        (c) Copyright 2010. http://www.bestsoftinc.com</td>
    </tr>
    <tr>
      <td class="table_content" colspan="2">&nbsp;</td>
    </tr>
    <tr>
      <td class="table_content" colspan="2" align="center" valign="middle">PLEASE ACCEPT END USER LICENSE AGREEMENT BEFORE INSTALLATION<br />
        <input type="radio" name="aggrement" value="yes" />
        Yes
        <input type="radio" name="aggrement" value="no" />
        No <br /></td>
    </tr>
    <tr>
      <td class="table_content" colspan="2">&nbsp;</td>
    </tr>
    <tr>
      <td class="table_content" align="center" colspan="2"><input type="submit" value="Start Installation" class="button" /></td>
    </tr>
    <tr>
      <td class="table_line"><img src="spacer.gif" width="1" height="2" alt="" /></td>
    </tr>
  </table>
</form>
</body>
</html>
