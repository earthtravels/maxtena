<?php
error_reporting(0);
include ("access.php");
include ("header.php");
include("../includes/conf.class.php");
$light="#666666"; 
?>
<script type="text/javascript" src="../ckeditor/ckeditor.js"></script>
</td>
</tr>

<tr>
  <td height="400" valign="top">
  <?php

if(isset($_REQUEST['SBMT_REG'])):
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
	
	// contents title..........
	if(isset($_POST['cont_title_en']))
	$cont_title_en = htmlentities(($_POST['cont_title_en']));
	else
	$cont_title_en = "";

	if(isset($_POST['cont_title_es']))
	$cont_title_es = htmlentities(($_POST['cont_title_es']));
	else
	$cont_title_es = "";
	
	if(isset($_POST['cont_title_de']))
	$cont_title_de = htmlentities(($_POST['cont_title_de']));
	else
	$cont_title_de = "";
	
	if(isset($_POST['cont_title_fr']))
	$cont_title_fr = htmlentities(($_POST['cont_title_fr']));
	else
	$cont_title_fr = "";
	// contents title..........
	
   	 if($_REQUEST['id']):
	     $r=mysql_query("update bsi_site_contents set cont_title_en='".$bsiCore->ClearInput($cont_title_en)."', cont_title_es='".$bsiCore->ClearInput($cont_title_es)."', cont_title_de='".$bsiCore->ClearInput($cont_title_de)."', cont_title_fr='".$bsiCore->ClearInput($cont_title_fr)."', ord=".$bsiCore->ClearInput($_POST['ord']).", parent_id=".$bsiCore->ClearInput($_POST['parent_id']).", status='".$bsiCore->ClearInput($_POST['status'])."', url='".$bsiCore->ClearInput($_POST['url'])."', contents_en='".$contents_en."', contents_es='".$contents_es."', contents_de='".$contents_de."', contents_fr='".$contents_fr."'  where id=" . $bsiCore->ClearInput($_REQUEST['id']));
   	    
	 else:
	     $n1234=mysql_num_rows(mysql_query("select * from bsi_site_contents where parent_id=0"));
		 if($_POST['parent_id'] != 0){
	      $r=mysql_query("insert into bsi_site_contents(cont_title_en, cont_title_es, cont_title_de, cont_title_fr, ord, parent_id, status, url, contents_en, contents_es, contents_de, contents_fr ) values('".$bsiCore->ClearInput($cont_title_en)."', '".$bsiCore->ClearInput($cont_title_es)."','".$bsiCore->ClearInput($cont_title_de)."', '".$bsiCore->ClearInput($cont_title_fr)."', ".$bsiCore->ClearInput($_POST['ord']).",".$bsiCore->ClearInput($_POST['parent_id']).", '".$bsiCore->ClearInput($_POST['status'])."', '".$bsiCore->ClearInput($_POST['url'])."', '".$contents_en."', '".$contents_es."', '".$contents_de."', '".$contents_fr."')");
		 
		 }else{
		  if($n1234 < 7 )
		  {
		    $r=mysql_query("insert into bsi_site_contents(cont_title_en, cont_title_es, cont_title_de, cont_title_fr, ord, parent_id, status, url, contents_en, contents_es, contents_de, contents_fr ) values('".$bsiCore->ClearInput($cont_title_en)."', '".$bsiCore->ClearInput($cont_title_es)."','".$bsiCore->ClearInput($cont_title_de)."', '".$bsiCore->ClearInput($cont_title_fr)."', ".$bsiCore->ClearInput($_POST['ord']).",".$bsiCore->ClearInput($_POST['parent_id']).", '".$bsiCore->ClearInput($_POST['status'])."', '".$bsiCore->ClearInput($_POST['url'])."', '".$contents_en."', '".$contents_es."', '".$contents_de."', '".$contents_fr."')");
		  }else{
		 	$msg="<font face='verdana' size='2' color='#FF3300'><b>Error: You can't add more than 7 root level menu.</b></font>";	
		 } 
		 }
	     
		 $_REQUEST['id']=mysql_insert_id();
	 endif;
	 if($r):	 
		 $msg="<font face='verdana' size='2' color='#111111'><b>Update successful.</b></font>";	  
	 endif;	

endif;
if(!$d[status]){$d[status]="Y";}

if($_REQUEST[id]):
	$id=$_REQUEST[id];
	$r=mysql_query("select * from bsi_site_contents where id=" . $bsiCore->ClearInput($_REQUEST[id]));
	$d=@mysql_fetch_array($r);  
endif;	
if($msg){echo"<div align=center>" . $msg ."</div>";}
?>
     <table width="99%" border="0" align="center" cellspacing="1" cellpadding="4" bgcolor="<?=$light?>" style="border:solid 1px <?=$light?>">
                                    <form method="post" action="<?=$_SERVER['PHP_SELF']?>" name='form1' enctype="multipart/form-data" onSubmit="javscript:return ValidEditor(this);">
                                      <input type="hidden" name="id" value="<?=$_REQUEST['id']?>">
                                      <tr class="header_tr">
                                        <td height="30" width="100%" colspan="2" class="big_title"><a title="Back to Contents Management" href="javascript:window.parent.location.href='content.list.php'" class="big_title">CONTENT MANAGEMENT</a> &gt;
                                          <?=$d['cont_title']?>
                                        </td>
                                      </tr>
                                      <tr bgcolor="#ffffff" class="lnk">
                                        <td  valign="top" width="20%">Menu Title <a class="lnkred">*</a></td>
                                        <td align="left">
                                        <?php
										$sql_lang_title=mysql_query("select * from  bsi_language where status=true order by lang_order");
										while($row_lang_title=mysql_fetch_assoc($sql_lang_title)){
										?>
                                        <img src="../graphics/language_icons/<?=$row_lang_title['lang_code']?>.png" border="0"  title="<?=$row_lang_title['language']?>" alt="<?=$row_lang_title['language']?>"  align="absmiddle"/> <input type="text" name='cont_title_<?=$row_lang_title['lang_code']?>'  size="55" value="<?=$d['cont_title_'.$row_lang_title['lang_code']]?>" /><br />
                                        <?php } ?>
                                        </td>
                                      </tr>
                                      <tr class=lnk bgcolor=#ffffff>
                                        <td valign=top>Menu Order</td>
                                        <td align="left"><input type="text" size=5 name="ord" value="<?=$d['ord']?>"  >
                                          [Example. 2]</td>
                                      </tr>
                                      
                                      <tr bgcolor="#ffffff" class="lnk">
                                        <td valign="top" colspan="2">(<font color="#CE8EA2">If above menu is submenu then you have to choose a parent header</font>)</td>
                                      </tr>
                                      <tr bgcolor="#ffffff" class="lnk">
                                        <td  valign="top" width="20%" >Parent header (Optional) </td>
                                        <td align="left"><select name=parent_id size=1 style="width:50%; height:20">
                                            <option value="0">Select Header-----></option>
                                            <?
										$rr=mysql_query("select * from bsi_site_contents where parent_id=0 order by ord");
										//echo"select * from h_contents where parent_id=0 and menu=1 order by ord";
										while($dd=@mysql_fetch_array($rr)):
											if($dd[id]==$d[parent_id]):
												echo"<option value='" . $dd[id] . "' selected>" . $dd[cont_title_en] . "</option>\n";
											elseif($dd[id]==$_REQUEST[parent_id]):
											   echo"<option value='" . $dd[id] . "' selected>" . $dd[cont_title_en] . "</option>\n";
											else:
												echo"<option value='" . $dd[id] . "'>" . $dd[cont_title_en] . "</option>\n";
											endif;	
										$vv=mysql_query("select * from bsi_site_contents where parent_id='" . $dd[id] . "' order by ord");
										//echo"select * from h_contents where parent_id='" . $dd[id] . "' and menu='1' order by ord";
										while($c=mysql_fetch_array($vv)):
											if($c[id]==$d[parent_id]):
												echo"<option value='" . $c[id] . "' selected>|__" . $c[cont_title_en] . "</option>\n";
											elseif($c[id]==$_REQUEST[parent_id]):
												echo"<option value='" . $c[id] . "' selected>|__" . $c[cont_title_en] . "</option>\n";
											else:
												echo"<option value='" . $c[id] . "'>|__" . $c[cont_title_en] . "</option>\n";
											endif;	
										endwhile;
										endwhile;   
											?>
                                          </select></td>
                                      </tr>
                                      <tr bgcolor="#ffffff" class="lnk">
                                        <td  valign="top" width="20%">Status </td>
                                        <td align="left"><input type="radio" value='Y' name="status" <?if($d[status]=="Y"){echo"checked";}?>>
                                          Active
                                          <input type="radio" name="status" value='N' <?if($d[status]=="N"){echo"checked";}?>>
                                          Hidden</td>
                                      </tr>
                                      <tr bgcolor="#ffffff" class="lnk">
                                        <td valign="top" >Url </td>
                                        <td align="left"><input type=text name=url value="<?=$d['url']?>" size=55 ></td>
                                      </tr>
                                      <tr bgcolor="#ffffff" class="lnk">
                                        <td valign="top" colspan="2" align="left">Page Contents (<font color="#CE8EA2">If url field is entered then no content below is required</font>)</td>
                                      </tr>
                                      <tr  bgcolor="#ffffff" class="lnk">
                                        <td colspan="2" height="400">
                                        <?php
                                        $sql_lang_content=mysql_query("select * from  bsi_language where status=true order by lang_order");
										while($row_lang_content=mysql_fetch_assoc($sql_lang_content)){
                                        ?>
                                         <b><?=$row_lang_content['language']?></b> <img src="../graphics/language_icons/<?=$row_lang_content['lang_code']?>.png" border="0"  title="<?=$row_lang_content['language']?>" alt="<?=$row_lang_content['language']?>"  align="absmiddle"/><br />
                                        <textarea class="ckeditor"  name="contents_<?=$row_lang_content['lang_code']?>"  ><?=$d['contents_'.$row_lang_content['lang_code']]?></textarea>
                                        <?php } ?>
                                        </td>
                                      </tr>
                                      <tr>
                                        <td colspan="2" height="20" align="center" ><input type="image" value="1" src="images/button_update.gif"  name='SBMT_REG' >
                                         </td>
                                      </tr>
                                    </form>
                                  </table>
  </td>
</tr>
<?php include("footer.php"); ?>
</table>
<br />
</body></html>