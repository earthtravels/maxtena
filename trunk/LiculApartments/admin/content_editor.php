<?php
error_reporting(0);
include ("access.php");
include ("header.php");
include("../includes/conf.class.php");
$content_id=$bsiCore->ClearInput($_REQUEST['id']);
if(isset($_REQUEST['act'])){
    // contents..........
   	if(isset($_POST['contents_en']))
	$contents_en = htmlentities(($_POST['contents_en']));
	else
	$contents_en = "";
	
	if(isset($_POST['contents_es']))
	$contents_es = htmlentities(($_POST['contents_es']));
	else
	$contents_es = "";
	
	if(isset($_POST['contents_de']))
	$contents_de = htmlentities(($_POST['contents_de']));
	else
	$contents_de = "";
	
	if(isset($_POST['contents_fr']))
	$contents_fr = htmlentities(($_POST['contents_fr']));
	else
	$contents_fr = "";
	// contents..........
$c_update=mysql_query("update bsi_contents set contents_en ='".$contents_en."', contents_es ='".$contents_es."', contents_de ='".$contents_de."', contents_fr ='".$contents_fr."' where id=".$content_id);
}
$content_details=mysql_fetch_assoc(mysql_query("select * from bsi_contents where id=".$content_id));

?>
<script type="text/javascript" src="../ckeditor/ckeditor.js"></script>
</td>
  </tr> 
  <tr>
    <td  valign="top" align="left"><br />
    <form action="<?=$_SERVER['PHP_SELF']?>?id=<?=$content_id?>&act=1" method="post" enctype="multipart/form-data">
        <table cellpadding="5" cellspacing="0" border="0" width="900" >
        <tr><td align="center" style="font-family:Arial, Helvetica, sans-serif; font-size:16px; font-weight:bold;"><?=$content_details['cont_title']?></td></tr>
        <tr><td >
        <?php
		$sql_lang_content=mysql_query("select * from  bsi_language where status=true order by lang_order");
		while($row_lang_content=mysql_fetch_assoc($sql_lang_content)){
		?>
         <b><?=$row_lang_content['language']?></b> <img src="../graphics/language_icons/<?=$row_lang_content['lang_code']?>.png" border="0"  title="<?=$row_lang_content['language']?>" alt="<?=$row_lang_content['language']?>"  align="absmiddle"/><br />
        <textarea class="ckeditor"  name="contents_<?=$row_lang_content['lang_code']?>"   ><?=$content_details['contents_'.$row_lang_content['lang_code']]?></textarea>
        <?php } ?>
</td></tr>
<tr><td align="center"><input type="submit" value="UPDATE CONTENT" /></td></tr>
<?php
if ($c_update) {
?>
<tr><td style="font-family:Arial, Helvetica, sans-serif;  font-size:14px; color:#009900; font-weight:bold;">Content is updated!!</td></tr>
<?php } ?>
        </table>
      </form>
    </td>
  </tr>
  <?php include("footer.php"); ?>
</table>


</body>
</html>
