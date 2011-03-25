<?php
session_start();
if (!$_SESSION['step1'] && !$_SESSION['step2']){
	header ("Location: install.php");
	die;
}
include ('install.class.php');
$bsiStep = new bsiInstallScript;
$hasError = false;

if(array_filter($bsiStep->installerror)){
	 $hasError = true;	
	 if(isset($_SESSION['step3'])) 
	 	unset($_SESSION['step3']);		 
}else{
	$_SESSION['step3'] = true; // step 3 passed
}
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
    <td align="left" class="table_bg" colspan="2">DATABASE INFORMATION</td>
  </tr>
  <tr>
    <td class="table_line" colspan="2"><img src="spacer.gif" height="2" alt="" /></td>
  </tr>
  <tr>
    <td class="table_content" colspan="2" align="center"><br /> If you see any messages in <span class="red">red</span>, you should correct those errors before continuing.<br />
      Click on "Step 3" to correct MySQL settings.<br /><br /> </td>
  </tr>
  <tr>
    <td class="table_content_l" align="right" width="50%"><b>Save Configuration Settings:</b></td>
    <td class="table_content_r" align="left" width="50%"><?php 
	if($bsiStep->installerror['save_conn']){ echo '<span class="red">Failed</span>';}
	else { echo '<span class="green">Passed</span>';}?></td>
  </tr>
  <tr>
    <td class="table_content_l" align="right" width="50%"><b>MySQL Connection Test:</b></td>
    <td class="table_content_r" align="left" width="50%"><?php 
	if($bsiStep->installerror['mysql_conn']){ echo '<span class="red">Failed</span>';}
	else { echo '<span class="green">Passed</span>';}?></td>
  </tr>
  <tr>
    <td class="table_content_l" align="right"><b>MySQL Database Creation:</b><br /></td>
    <td class="table_content_r" align="left"><?php 
	if($bsiStep->installerror['create_db']){ echo '<span class="red">Failed</span>';}
	else { echo '<span class="green">Passed</span>';}?></td>
  </tr>
  <tr>
    <td class="table_content_l" align="right"><b>MySQL Table Creation:</b><br /></td>
    <td class="table_content_r" align="left"><?php 
	if($bsiStep->installerror['create_table']){ echo '<span class="red">Failed</span>';}
	else { echo '<span class="green">Passed</span>';}?></td>
  </tr><tr>
    <td class="table_content" colspan="2">&nbsp;<br />
      <br />
      <br />
      <br /></td>
  </tr>
  <tr>
    <td class="table_content" align="right" ><form action="install.step2.php" method="post">
        <input type="submit" value="&laquo; Step 3" class="button" <?php if(!$hasError) echo 'disabled="disabled"'; ?> />
      </form></td><td class="table_content" align="left" colspan="2"><form action="install.step4.php" method="post">
        <input type="submit" value="Step 4 &raquo;" class="button" <?php if($hasError) echo 'disabled="disabled"'; ?>  />
      </form></td>
  </tr>
  <tr>
    <td class="table_line" colspan="2"><img src="spacer.gif" width="1" height="2" alt="" /></td>
  </tr>
</table>
</body>
</html>
