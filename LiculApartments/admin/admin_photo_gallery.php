<?php
include ("access.php");
if(isset($_REQUEST['pid'])){
	include("../includes/db.conn.php");
	include("../includes/conf.class.php");
	include("../includes/admin.class.php");
	$is_delete=$bsiAdminMain->gallery_photo_delete(1);
	if($is_delete)
	header("location:admin_photo_gallery.php");
}
if(isset($_POST['sbt_lang'])){
    include("../includes/db.conn.php");
	include("../includes/admin.class.php");
	$bsiAdminMain->main_gallery_img_upload();
	header("location:admin_photo_gallery.php");
}
include ("header.php");
include("../includes/admin.class.php");

?>
<link href="css/pagination.css" rel="stylesheet" type="text/css">
	</td>
  </tr> 
  
  <tr>
    <td height="400" valign="top" align="left">
    <?php
	if(isset($_REQUEST['upload_photo_form']))
	{
		$upload_image_limit = 5;
		$i=0;
		$form_img="";
		$htmo="";
	   ############################### HTML FORM
		while($i++ < $upload_image_limit){
			$form_img .= '<tr><td>Image '.$i.': </td><td><input type="file" name="uplimg'.$i.'"></td></tr>';
		}
	
		$htmo .= '
			<table cellpadding="6" cellspacing="0" border="0" style="font-size:13px; border:solid #999999 2px;">
			<tr><td colspan="2" class="TitleRed11pt" align="center">Main Gallery Photo Upload</td></tr>
			<form method="post" enctype="multipart/form-data"> <input  type="hidden" value="11" name="sbt_lang" />
				'.$form_img.' 
				<tr><td align="center" colspan="2"><input type="submit" value="Upload Images!" style="margin-left: 50px;" /></td></tr>
			</form>
			</table>
			';	
	
		echo $htmo;
	} else { ?>
    <table cellpadding="5" cellspacing="0" border="0">
    <tr><td colspan="4" style="padding-left:30px;"><a href="<?=$_SERVER['PHP_SELF']?>?upload_photo_form=1"><img src="images/bttn_upload.png" border="0" /></a></td></tr>
    
    	<?php
		if(isset($_GET['page']))
		$pagination_gallery=$bsiAdminMain->pagination('bsi_gallery',8,$_GET['page'],'admin_photo_gallery.php',1);
		else
		$pagination_gallery=$bsiAdminMain->pagination('bsi_gallery',8,0,'admin_photo_gallery.php',1);
		
		$s_count=$pagination_gallery['total_pages'];
		$s_limit=$pagination_gallery['limit'];
		echo "<tr>";
		$num_column=4;
		while($row_gallery = mysql_fetch_array($pagination_gallery['pagination_return_sql']))
		{
	    
		echo '<td width="200" align="center"><img src="../gallery/thumb_'.$row_gallery[1].'" style="border:solid 3px #FFCC00"><br><a href="'.$_SERVER['PHP_SELF'].'?pid='.base64_encode($row_gallery[0]).'"><img src="images/button_delete.gif" border="0"  align="center" style="padding-top:5px;"/></a></td>';
		
		$s_limit--;
		  $num_column--;
		   if($s_limit==0 && $num_column==3)
		   echo "<td width=\"200\"></td><td width=\"200\"></td><td width=\"200\"></td></tr><tr><td colspan=\"4\">&nbsp;</td></tr>";
		   if($s_limit==0 && $num_column==2)
		   echo "<td width=\"200\"></td><td width=\"200\"></td></tr><tr><td colspan=\"4\">&nbsp;</td></tr>";
		   if($s_limit==0 && $num_column==1)
		   echo "<td width=\"200\"></td></tr><tr><td colspan=\"4\">&nbsp;</td></tr>";
		   if($s_limit==0 && $num_column==0)
		   echo "</tr><tr><td colspan=\"4\">&nbsp;</td></tr>";
		   if($s_limit > 0 && $num_column==0){
		   echo "</tr><tr><td colspan=\"4\">&nbsp;</td></tr>";
		   $num_column=4;
		   }
	
		}
	?>
    
    <tr><td colspan="4"><?=$pagination_gallery['page_list']?></td></tr>
    </table>
    <?php } ?>
    </td>
  </tr>
  <?php include("footer.php"); ?>
</table>


</body>
</html>
