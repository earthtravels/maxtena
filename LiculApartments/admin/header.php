<?php
include_once ("../includes/db.conn.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Admin Panel</title>
	<link href="style.css" rel="stylesheet" type="text/css" />
	<link href="style1.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="dhtml-menu.js"></script>
</head>

<body>
<table align="center" width="950" border="0" cellspacing="0"
	cellpadding="0">
	<tr>
		<td width="366">&nbsp;</td>
		<td width="584">&nbsp;</td>
	</tr>
	<tr>
		<td align="left" valign="bottom"><img src="images/adminis.gif" alt="" /></td>
		<td align="right" valign="bottom" class="bodyMenutext8pt"><a
			href="../index.php" target="_blank" class="bodyMenutext8pt">Visit
		Site</a> | <a href="logout.php" class="bodyMenutext8pt">Log Out</a></td>
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
		<td height="8"></td>
	</tr>
	<tr>
		<td align="left">
		<table border="0" style="background-image: url(images/menu_tile.gif);"
			cellspacing="0" cellpadding="0">
			<tr>
				<td><img src="images/menubar_left.gif" alt="" width="13" height="40" /></td>
				<td align="left" class="bodytext8pt">
				<div class="sample1">

				<div class="horz_menu">
<?php
$sql_parent = mysql_query("select * from bsi_adminmenu where parent_id=0 and status='Y' order by ord");
while ($row_parent = mysql_fetch_array($sql_parent))
{
	echo "<a id=\"" . $row_parent[0] . "\"      href=\"" . $row_parent[2] . "\">&nbsp;&nbsp;&nbsp;" . $row_parent[1] . "</a>";
}
?>
<br clear="both" />
				</div>
				<script type="text/javascript">
<?php
$sql_parent111 = mysql_query("select * from bsi_adminmenu where parent_id=0 and status='Y' order by ord");
while ($row_parent111 = mysql_fetch_array($sql_parent111))
{
	$sql_parent222 = mysql_query("select * from bsi_adminmenu where parent_id=" . $row_parent111[0] . " and status='Y' order by ord");
	if (mysql_num_rows($sql_parent222))
	{
		while ($row_parent222 = mysql_fetch_array($sql_parent222))
		{
			$sql_parent333 = mysql_query("select * from bsi_adminmenu where parent_id=" . $row_parent222[0] . " and status='Y' order by ord");
			if (mysql_num_rows($sql_parent333))
			{
				while ($row_parent333 = mysql_fetch_array($sql_parent333))
				{
					$sql_parent444 = mysql_query("select * from bsi_adminmenu where parent_id=" . $row_parent333[0] . " and status='Y' order by ord");
					$n777 = mysql_num_rows($sql_parent444);
					if ($n777)
					{
						$menu_leaf1 = 'leaf_' . $row_parent333[0];
						echo 'var ' . $menu_leaf1 . ' = {"-" : "index.php",';
						$n888 = 1;
						while ($row_parent444 = mysql_fetch_array($sql_parent444))
						{
							if ($n888 == $n777)
								echo '"&nbsp;&nbsp;&nbsp;' . $row_parent444[1] . '":"' . $row_parent444[2] . '"';
							else
								echo '"&nbsp;&nbsp;&nbsp;' . $row_parent444[1] . '":"' . $row_parent444[2] . '",';
							
							$n888 ++;
						}
						echo '};';
					}
				}
			}
		}
	}
}

//************************************************************************************************
$sql_parent11 = mysql_query("select * from bsi_adminmenu where parent_id=0 and status='Y' order by ord");
while ($row_parent11 = mysql_fetch_array($sql_parent11))
{
	$sql_parent22 = mysql_query("select * from bsi_adminmenu where parent_id=" . $row_parent11[0] . " and status='Y' order by ord");
	if (mysql_num_rows($sql_parent22))
	{
		while ($row_parent22 = mysql_fetch_array($sql_parent22))
		{
			$sql_parent33 = mysql_query("select * from bsi_adminmenu where parent_id=" . $row_parent22[0] . " and status='Y' order by ord");
			$n77 = mysql_num_rows($sql_parent33);
			if ($n77)
			{
				$menu_leaf = 'tree_' . $row_parent22[0];
				echo 'var ' . $menu_leaf . ' = {"-" : "#",';
				$n88 = 1;
				while ($row_parent33 = mysql_fetch_array($sql_parent33))
				{
					$sql_parent544 = mysql_query("select * from bsi_adminmenu where parent_id=" . $row_parent33[0] . " and status='Y' order by ord");
					$n544 = mysql_num_rows($sql_parent544);
					if ($n544)
					{
						if ($n88 == $n77)
							echo '"&nbsp;&nbsp;&nbsp;' . $row_parent33[1] . '": leaf_' . $row_parent33[0] . '';
						else
							echo '"&nbsp;&nbsp;&nbsp;' . $row_parent33[1] . '": leaf_' . $row_parent33[0] . ',';
					}
					else
					{
						if ($n88 == $n77)
							echo '"&nbsp;&nbsp;&nbsp;' . $row_parent33[1] . '":"' . $row_parent33[2] . '"';
						else
							echo '"&nbsp;&nbsp;&nbsp;' . $row_parent33[1] . '":"' . $row_parent33[2] . '",';
					}
					$n88 ++;
				}
				echo '};';
			}
		
		}
	}
}

//**********************************************************************************************
$sql_parent1 = mysql_query("select * from bsi_adminmenu where parent_id=0 and status='Y' order by ord");
while ($row_parent1 = mysql_fetch_array($sql_parent1))
{
	$sql_parent2 = mysql_query("select * from bsi_adminmenu where parent_id=" . $row_parent1[0] . " and status='Y' order by ord");
	$n2 = mysql_num_rows($sql_parent2);
	if ($n2)
	{
		
		$menu_id = 'menu_' . $row_parent1[0];
		$menu_id1 = 'menu1_' . $row_parent1[0];
		echo 'var ' . $menu_id . ' = {';
		$n3 = 1;
		while ($row_parent2 = mysql_fetch_array($sql_parent2))
		{
			$sql_parent54 = mysql_query("select * from bsi_adminmenu where parent_id=" . $row_parent2[0] . " and status='Y' order by ord");
			$n54 = mysql_num_rows($sql_parent54);
			if ($n54)
			{
				if ($n3 == $n2)
					echo '"&nbsp;&nbsp;&nbsp;' . $row_parent2[1] . '": tree_' . $row_parent2[0] . '';
				else
					echo '"&nbsp;&nbsp;&nbsp;' . $row_parent2[1] . '": tree_' . $row_parent2[0] . ',';
			}
			else
			{
				if ($n3 == $n2)
					echo '"&nbsp;&nbsp;&nbsp;' . $row_parent2[1] . '":"' . $row_parent2[2] . '"';
				else
					echo '"&nbsp;&nbsp;&nbsp;' . $row_parent2[1] . '":"' . $row_parent2[2] . '",';
			}
			$n3 ++;
		}
		echo '};';
		echo 'var ' . $menu_id1 . ' = { "' . $row_parent1[0] . '" : ' . $menu_id . ' };';
		echo 'dhtmlmenu_build(' . $menu_id1 . ');';
	}
}
?>
</script></div>
				</td>
				<td><img src="images/menubar_right.gif" alt="" width="13"
					height="40" /></td>
			</tr>
		</table>