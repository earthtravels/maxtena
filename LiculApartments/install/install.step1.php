<?php
session_start();
include ('install.class.php');
$bsiStep = new bsiInstallStart;
$hasError = false;
if(array_filter($bsiStep->installerror)){
	 $hasError = true;
	 if(isset($_SESSION['step1']))
	 	unset($_SESSION['step1']);	
}else{
	$_SESSION['step1'] = true; // step 1 passed
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
    <td align="left" class="table_bg" colspan="2">CHECKING BASIC REQUIREMENTS</td>
  </tr>
  <tr>
    <td class="table_line" colspan="2"><img src="spacer.gif" height="2" alt="" /></td>
  </tr>
  <tr>
    <td class="table_content" colspan="2" align="center"><br />
      If you see any messages in <span class="red">red</span>, you should correct those errors before continuing.<br />
      Click on "Refresh" after correcting all the errors marked in <span class="red">red</span>.<br />
      Set 0777 attributes to make the file or folder writable.<br />
      <br /></td>
  </tr>
  <tr>
    <td class="table_content_l" align="right" width="50%"><b>PHP Sessions:</b></td>
    <td class="table_content_r" align="left" width="50%"><?php 	
	if($bsiStep->installerror['session_disabled']){ echo '<span class="red">Failed</span>';}
	else { echo '<span class="green">Passed</span>';}
	?></td>
  </tr>
  <tr>
    <td class="table_content_l" align="right"><b>Config File:</b><br />
      <?php echo '"'.$bsiStep->installinfo['config_file'].'"'; ?></td>
    <td class="table_content_r" align="left"><?php 	
	if($bsiStep->installerror['config_notwritable']){ echo '<span class="red">Failed</span>';}
	else { echo '<span class="green">Passed</span>';}
	?></td>
  </tr>
  <tr>
    <td class="table_content_l" align="right"><b>Folder for Gallery Images:</b><br />
      <?php echo '"'.$bsiStep->installinfo['gallery_path'].'"'; ?></td>
    <td class="table_content_r" align="left"><?php 	
	if($bsiStep->installerror['gallery_notwritable']){ echo '<span class="red">Failed</span>';}
	else { echo '<span class="green">Passed</span>';}
	?></td>
  </tr>
  <tr>
    <td class="table_content_l" align="right"><b>GD Library:</b></td>
    <td class="table_content_r" align="left"><?php 		
	if($bsiStep->installerror['gd_notinstalled']){ echo '<span class="red">Failed</span>';}
	else { echo '<span class="green">Passed</span>';} 
	?></td>
  </tr>
  <tr>
    <td class="table_content_l" align="right"><b>GD Library Version:</b></td>
    <td class="table_content_r" align="left"><?php 
	if($bsiStep->installerror['gd_versionnotpermit']){ echo '<span class="red">Failed</span>';}
	else { echo '<span class="green">Passed</span>';}?></td>
  </tr>
  <tr>
    <td class="table_content_l" align="right"><b>MySQL server:</b></td>
    <td class="table_content_r" align="left"><?php 
	if($bsiStep->installerror['mysql_notavailable']){ echo '<span class="red">Failed</span>';}
	else { echo '<span class="green">Passed</span>';}	
	?></td>
  </tr>
  <tr>
    <td class="table_content" colspan="2">&nbsp;<br />
      <br />
      <br />
      <br /></td>
  </tr>
  <tr>
    <td class="table_content_l" align="right"><form action="install.step1.php" method="post">
        <input type="submit" value="Refresh" class="button" />
      </form></td>
    <td class="table_content_r" align="left"><form action="install.step2.php" method="post">
        <input type="submit" value="Step 2 &raquo;" class="button" <?php if($hasError) echo 'disabled="disabled"'; ?> />
      </form></td>
  </tr>
  <tr>
    <td class="table_line" colspan="2"><img src="spacer.gif" width="1" height="2" alt="" /></td>
  </tr>
</table>
</body>
</html>