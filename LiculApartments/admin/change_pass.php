<?php
error_reporting(0);
include ("access.php");
include ("header.php");
$id=$_SESSION['id'];
?>
	</td>
  </tr> 
  <tr>
    <td height="400" align="center">
    <form method="post" action="<?=$_SERVER[PHP_SELF]?>" onSubmit="javascript:return ValidPass(this);"><br>
		<table cellpadding="4" cellspacing="0" border=0 align="center" width="30%" style="border:solid 1px <?=$light?>">
			<?php 
			if($_POST['SBMT'])
			{
				$r=mysql_query("select * from bsi_admin where pass=\"" . md5($_POST['old_pass']) . "\" and id = ".$id);
   				if(@mysql_num_rows($r))
   				{     
 					$r=mysql_query("update bsi_admin set pass='".md5($_POST['pass'])."' where id=".$id);
     				if($r)
     				{
        				echo"<tr  bgcolor='#ffffff' color='red'><td colspan='2' align='center'>Password Change Successful!<br /></td></tr>";
     				}
   				}
				else
				{				  	
					echo"<tr  bgcolor='#ffffff' class='lnk' ><td colspan='2' align='center'>Old Password is Invalid</td></tr>";
				}	 
			}   
			?>
			<tr bgcolor="#666666">
				<td colspan="2" align="center" class="header"><font color="#FFFFFF">Change Password</font></td>
			</tr>
			<tr class="lnk" bgcolor="#ffffff"> 
				<td>Old Password </td><td> <input type="text" class="lnk" name="old_pass"  size="15"></td>
			</tr>
			<tr class="lnk" bgcolor="#ffffff">
				<td>New Password </td><td> <input type="text" class="lnk" name="pass"  maxlength="12" size="15"></td>
			</tr>
			<tr class="lnk" bgcolor="<?=$light?>">
				<td align="center" colspan="2"><input type="submit" name="SBMT" value="Submit" class="lnk"></td>
			</tr>
		</table>
	</form>
    </td>
  </tr>
  <?php include("footer.php"); ?>
</table>


</body>
</html>
