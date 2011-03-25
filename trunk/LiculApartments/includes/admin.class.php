<?php
$bsiAdminMain = new bsiAdminCore;
class bsiAdminCore{

	//global setting start
	public function global_setting(){
	global $bsiCore;
	$global_selects=array();
	//date format start
	$dt_format_array=array("mm/dd/yyyy","dd/mm/yyyy","mm-dd-yyyy","dd-mm-yyyy","mm.dd.yyyy","dd.mm.yyyy","yyyy-mm-dd");
	$select_dt_format="";
	for($p=0; $p<7; $p++){
	if($dt_format_array[$p]==$bsiCore->config['conf_dateformat'])
	$select_dt_format.='<option value="'.$dt_format_array[$p].'" selected="selected">'.strtoupper($dt_format_array[$p]).'</option>';
	else
	$select_dt_format.='<option value="'.$dt_format_array[$p].'" >'.strtoupper($dt_format_array[$p]).'</option>';
	}
	$global_selects['select_dt_format']=$select_dt_format;
	//date format end
	
	//room lock start
	$room_lock = array(
	        '200' => '2 Minute',
			'500' => '5 Minute',
			'1000' => '10 Minute',
			'2000' => '20 Minute',
			'3000' => '30 Minute');
			
	$select_room_lock="";
	foreach($room_lock as $key => $value) {
	    if($key==$bsiCore->config['conf_booking_exptime'])
		$select_room_lock.='		<option value="' . $key . '" selected="selected">' . $value . '</option>' . "\n";
		else
		$select_room_lock.='		<option value="' . $key . '">' . $value . '</option>' . "\n";
	}
	$global_selects['select_room_lock']=$select_room_lock;
	//room lock end
	
	//timezone_start
	$zonelist = array('Kwajalein' => '(GMT-12:00) International Date Line West',
			'Pacific/Midway' => '(GMT-11:00) Midway Island',
			'Pacific/Samoa' => '(GMT-11:00) Samoa',
			'Pacific/Honolulu' => '(GMT-10:00) Hawaii',
			'America/Anchorage' => '(GMT-09:00) Alaska',
			'America/Los_Angeles' => '(GMT-08:00) Pacific Time (US &amp; Canada)',
			'America/Tijuana' => '(GMT-08:00) Tijuana, Baja California',
			'America/Denver' => '(GMT-07:00) Mountain Time (US &amp; Canada)',
			'America/Chihuahua' => '(GMT-07:00) Chihuahua',
			'America/Mazatlan' => '(GMT-07:00) Mazatlan',
			'America/Phoenix' => '(GMT-07:00) Arizona',
			'America/Regina' => '(GMT-06:00) Saskatchewan',
			'America/Tegucigalpa' => '(GMT-06:00) Central America',
			'America/Chicago' => '(GMT-06:00) Central Time (US &amp; Canada)',
			'America/Mexico_City' => '(GMT-06:00) Mexico City',
			'America/Monterrey' => '(GMT-06:00) Monterrey',
			'America/New_York' => '(GMT-05:00) Eastern Time (US &amp; Canada)',
			'America/Bogota' => '(GMT-05:00) Bogota',
			'America/Lima' => '(GMT-05:00) Lima',
			'America/Rio_Branco' => '(GMT-05:00) Rio Branco',
			'America/Indiana/Indianapolis' => '(GMT-05:00) Indiana (East)',
			'America/Caracas' => '(GMT-04:30) Caracas',
			'America/Halifax' => '(GMT-04:00) Atlantic Time (Canada)',
			'America/Manaus' => '(GMT-04:00) Manaus',
			'America/Santiago' => '(GMT-04:00) Santiago',
			'America/La_Paz' => '(GMT-04:00) La Paz',
			'America/St_Johns' => '(GMT-03:30) Newfoundland',
			'America/Argentina/Buenos_Aires' => '(GMT-03:00) Georgetown',
			'America/Sao_Paulo' => '(GMT-03:00) Brasilia',
			'America/Godthab' => '(GMT-03:00) Greenland',
			'America/Montevideo' => '(GMT-03:00) Montevideo',
			'Atlantic/South_Georgia' => '(GMT-02:00) Mid-Atlantic',
			'Atlantic/Azores' => '(GMT-01:00) Azores',
			'Atlantic/Cape_Verde' => '(GMT-01:00) Cape Verde Is.',
			'Europe/Dublin' => '(GMT) Dublin',
			'Europe/Lisbon' => '(GMT) Lisbon',
			'Europe/London' => '(GMT) London',
			'Africa/Monrovia' => '(GMT) Monrovia',
			'Atlantic/Reykjavik' => '(GMT) Reykjavik',
			'Africa/Casablanca' => '(GMT) Casablanca',
			'Europe/Belgrade' => '(GMT+01:00) Belgrade',
			'Europe/Bratislava' => '(GMT+01:00) Bratislava',
			'Europe/Budapest' => '(GMT+01:00) Budapest',
			'Europe/Ljubljana' => '(GMT+01:00) Ljubljana',
			'Europe/Prague' => '(GMT+01:00) Prague',
			'Europe/Sarajevo' => '(GMT+01:00) Sarajevo',
			'Europe/Skopje' => '(GMT+01:00) Skopje',
			'Europe/Warsaw' => '(GMT+01:00) Warsaw',
			'Europe/Zagreb' => '(GMT+01:00) Zagreb',
			'Europe/Brussels' => '(GMT+01:00) Brussels',
			'Europe/Copenhagen' => '(GMT+01:00) Copenhagen',
			'Europe/Madrid' => '(GMT+01:00) Madrid',
			'Europe/Paris' => '(GMT+01:00) Paris',
			'Africa/Algiers' => '(GMT+01:00) West Central Africa',
			'Europe/Amsterdam' => '(GMT+01:00) Amsterdam',
			'Europe/Berlin' => '(GMT+01:00) Berlin',
			'Europe/Rome' => '(GMT+01:00) Rome',
			'Europe/Stockholm' => '(GMT+01:00) Stockholm',
			'Europe/Vienna' => '(GMT+01:00) Vienna',
			'Europe/Minsk' => '(GMT+02:00) Minsk',
			'Africa/Cairo' => '(GMT+02:00) Cairo',
			'Europe/Helsinki' => '(GMT+02:00) Helsinki',
			'Europe/Riga' => '(GMT+02:00) Riga',
			'Europe/Sofia' => '(GMT+02:00) Sofia',
			'Europe/Tallinn' => '(GMT+02:00) Tallinn',
			'Europe/Vilnius' => '(GMT+02:00) Vilnius',
			'Europe/Athens' => '(GMT+02:00) Athens',
			'Europe/Bucharest' => '(GMT+02:00) Bucharest',
			'Europe/Istanbul' => '(GMT+02:00) Istanbul',
			'Asia/Jerusalem' => '(GMT+02:00) Jerusalem',
			'Asia/Amman' => '(GMT+02:00) Amman',
			'Asia/Beirut' => '(GMT+02:00) Beirut',
			'Africa/Windhoek' => '(GMT+02:00) Windhoek',
			'Africa/Harare' => '(GMT+02:00) Harare',
			'Asia/Kuwait' => '(GMT+03:00) Kuwait',
			'Asia/Riyadh' => '(GMT+03:00) Riyadh',
			'Asia/Baghdad' => '(GMT+03:00) Baghdad',
			'Africa/Nairobi' => '(GMT+03:00) Nairobi',
			'Asia/Tbilisi' => '(GMT+03:00) Tbilisi',
			'Europe/Moscow' => '(GMT+03:00) Moscow',
			'Europe/Volgograd' => '(GMT+03:00) Volgograd',
			'Asia/Tehran' => '(GMT+03:30) Tehran',
			'Asia/Muscat' => '(GMT+04:00) Muscat',
			'Asia/Baku' => '(GMT+04:00) Baku',
			'Asia/Yerevan' => '(GMT+04:00) Yerevan',
			'Asia/Yekaterinburg' => '(GMT+05:00) Ekaterinburg',
			'Asia/Karachi' => '(GMT+05:00) Karachi',
			'Asia/Tashkent' => '(GMT+05:00) Tashkent',
			'Asia/Calcutta' => '(GMT+05:30) Calcutta',
			'Asia/Colombo' => '(GMT+05:30) Sri Jayawardenepura',
			'Asia/Katmandu' => '(GMT+05:45) Kathmandu',
			'Asia/Dhaka' => '(GMT+06:00) Dhaka',
			'Asia/Almaty' => '(GMT+06:00) Almaty',
			'Asia/Novosibirsk' => '(GMT+06:00) Novosibirsk',
			'Asia/Rangoon' => '(GMT+06:30) Yangon (Rangoon)',
			'Asia/Krasnoyarsk' => '(GMT+07:00) Krasnoyarsk',
			'Asia/Bangkok' => '(GMT+07:00) Bangkok',
			'Asia/Jakarta' => '(GMT+07:00) Jakarta',
			'Asia/Brunei' => '(GMT+08:00) Beijing',
			'Asia/Chongqing' => '(GMT+08:00) Chongqing',
			'Asia/Hong_Kong' => '(GMT+08:00) Hong Kong',
			'Asia/Urumqi' => '(GMT+08:00) Urumqi',
			'Asia/Irkutsk' => '(GMT+08:00) Irkutsk',
			'Asia/Ulaanbaatar' => '(GMT+08:00) Ulaan Bataar',
			'Asia/Kuala_Lumpur' => '(GMT+08:00) Kuala Lumpur',
			'Asia/Singapore' => '(GMT+08:00) Singapore',
			'Asia/Taipei' => '(GMT+08:00) Taipei',
			'Australia/Perth' => '(GMT+08:00) Perth',
			'Asia/Seoul' => '(GMT+09:00) Seoul',
			'Asia/Tokyo' => '(GMT+09:00) Tokyo',
			'Asia/Yakutsk' => '(GMT+09:00) Yakutsk',
			'Australia/Darwin' => '(GMT+09:30) Darwin',
			'Australia/Adelaide' => '(GMT+09:30) Adelaide',
			'Australia/Canberra' => '(GMT+10:00) Canberra',
			'Australia/Melbourne' => '(GMT+10:00) Melbourne',
			'Australia/Sydney' => '(GMT+10:00) Sydney',
			'Australia/Brisbane' => '(GMT+10:00) Brisbane',
			'Australia/Hobart' => '(GMT+10:00) Hobart',
			'Asia/Vladivostok' => '(GMT+10:00) Vladivostok',
			'Pacific/Guam' => '(GMT+10:00) Guam',
			'Pacific/Port_Moresby' => '(GMT+10:00) Port Moresby',
			'Asia/Magadan' => '(GMT+11:00) Magadan',
			'Pacific/Fiji' => '(GMT+12:00) Fiji',
			'Asia/Kamchatka' => '(GMT+12:00) Kamchatka',
			'Pacific/Auckland' => '(GMT+12:00) Auckland',
			'Pacific/Tongatapu' => '(GMT+13:00) Nukualofa');
			
	$select_timezone="";
	foreach($zonelist as $key => $value) {
	    if($key==$bsiCore->config['conf_hotel_timezone'])
		$select_timezone.='		<option value="' . $key . '" selected="selected">' . $value . '</option>' . "\n";
		else
		$select_timezone.='		<option value="' . $key . '">' . $value . '</option>' . "\n";
	}
     $global_selects['select_timezone']=$select_timezone;
	 
	 if($bsiCore->config['conf_booking_turn_off']==0){
		 $select_booking_turn='		<option value="0" selected="selected">Turn On</option>' . "\n";
		 $select_booking_turn.='		<option value="1">Turn Off</option>' . "\n";
	 }else{
		 $select_booking_turn='		<option value="1" selected="selected">Turn Off</option>' . "\n";
		 $select_booking_turn.='		<option value="0">Turn On</option>' . "\n";
	 }
	 $global_selects['select_booking_turn']=$select_booking_turn;
	 
	 $select_min_booking="";
	 for($k=1; $k<11; $k++){
	 	if($bsiCore->config['conf_min_night_booking']==$k){
		$select_min_booking.='		<option value="' . $k . '" selected="selected">' . $k . '</option>' . "\n";
		}else{
		$select_min_booking.='		<option value="' . $k . '">' . $k . '</option>' . "\n";
		}
	 }
	 $global_selects['select_min_booking']=$select_min_booking;
	 
	 return $global_selects;
	} //global setting end
	
	
	//global setting post function
	public function global_setting_post(){
		global $bsiCore;
		$this->configure_update('conf_hotel_sitetitle', $bsiCore->ClearInput($_POST['title']));
		$this->configure_update('conf_hotel_sitedesc', $bsiCore->ClearInput($_POST['desc']));
		$this->configure_update('conf_hotel_sitekeywords', $bsiCore->ClearInput($_POST['keywords']));
		$this->configure_update('conf_dateformat', $bsiCore->ClearInput($_POST['date_format']));
		$this->configure_update('conf_hotel_timezone', $bsiCore->ClearInput($_POST['timezone']));
		$this->configure_update('conf_tax_amount', $bsiCore->ClearInput($_POST['tax']));
		$this->configure_update('conf_smtp_mail', $bsiCore->ClearInput($_POST['email_send_by']));
		$this->configure_update('conf_smtp_host', $bsiCore->ClearInput($_POST['smtphost']));
		$this->configure_update('conf_smtp_port', $bsiCore->ClearInput($_POST['smtpport']));
		$this->configure_update('conf_smtp_username', $bsiCore->ClearInput($_POST['smtpuser']));
		$this->configure_update('conf_smtp_password', $bsiCore->ClearInput($_POST['smtppass']));
		$this->configure_update('conf_currency_code', $bsiCore->ClearInput($_POST['currency_code']));
		$this->configure_update('conf_currency_symbol', $bsiCore->ClearInput($_POST['currency_symbol'])); 	
		$this->configure_update('conf_booking_exptime', $bsiCore->ClearInput($_POST['room_lock']));
		$this->configure_update('conf_booking_turn_off', $bsiCore->ClearInput($_POST['booking_turn']));
		$this->configure_update('conf_min_night_booking', $bsiCore->ClearInput($_POST['minbooking']));
	}
	//global setting post function
	public function hotel_details_post(){
		global $bsiCore;
		$this->configure_update('conf_hotel_name', $bsiCore->ClearInput($_POST['hotel_name']));
		$this->configure_update('conf_hotel_streetaddr', $bsiCore->ClearInput($_POST['str_addr']));
		$this->configure_update('conf_hotel_city', $bsiCore->ClearInput($_POST['city']));
		$this->configure_update('conf_hotel_state', $bsiCore->ClearInput($_POST['state']));
		$this->configure_update('conf_hotel_country', $bsiCore->ClearInput($_POST['country']));
		$this->configure_update('conf_hotel_zipcode', $bsiCore->ClearInput($_POST['zipcode']));
		$this->configure_update('conf_hotel_phone', $bsiCore->ClearInput($_POST['phone']));
		$this->configure_update('conf_hotel_fax', $bsiCore->ClearInput($_POST['fax']));
		$this->configure_update('conf_hotel_email', $bsiCore->ClearInput($_POST['email']));
	}
	
	private function configure_update($key, $value){
		mysql_query("update bsi_configure set conf_value='".$value."' where conf_key='".$key."'");
	}
	
	//paygateway functions start
	public function payment_gateway(){
		$gateway_value=array();
		$pp_row=mysql_fetch_assoc(mysql_query("select * from bsi_payment_gateway where gateway_code='pp'"));
		$co_row=mysql_fetch_assoc(mysql_query("select * from bsi_payment_gateway where gateway_code='2co'"));
		$poa_row=mysql_fetch_assoc(mysql_query("select * from bsi_payment_gateway where gateway_code='poa'"));
		$an_row=mysql_fetch_assoc(mysql_query("select * from bsi_payment_gateway where gateway_code='an'"));
		$an_account=explode("=|=",$an_row['account']);
		
		$gateway_value['pp_enabled']=$pp_row['enabled'];
		$gateway_value['pp_gateway_name']=$pp_row['gateway_name'];
		$gateway_value['pp_account']=$pp_row['account'];
		
		$gateway_value['co_enabled']=$co_row['enabled'];
		$gateway_value['co_gateway_name']=$co_row['gateway_name'];
		$gateway_value['co_account']=$co_row['account'];
		
		$gateway_value['poa_enabled']=$poa_row['enabled'];
		$gateway_value['poa_gateway_name']=$poa_row['gateway_name'];
		
		$gateway_value['an_enabled']=$an_row['enabled'];
		$gateway_value['an_gateway_name']=$an_row['gateway_name'];
		$gateway_value['an_login']=$an_account[0];
		$gateway_value['an_txnkey']=$an_account[1];
		return $gateway_value;
	}
	
	public function payment_gateway_post(){
		global $bsiCore;
	    $pp = ((isset($_POST['pp'])) ? 1 : 0);
		$pp_title=$bsiCore->ClearInput($_POST['pp_title']);
		$paypal_id=$bsiCore->ClearInput($_POST['paypal_id']);
		
		$co = ((isset($_POST['2co'])) ? 1 : 0);
		$co_title=$bsiCore->ClearInput($_POST['2co_title']);
		$co_id=$bsiCore->ClearInput($_POST['2co_id']);
		
		$poa = ((isset($_POST['poa'])) ? 1 : 0);
		$poa_title=$bsiCore->ClearInput($_POST['poa_title']);
		
		$an = ((isset($_POST['an'])) ? 1 : 0);
		$an_title=$bsiCore->ClearInput($_POST['an_title']);
		$an_loginid=$bsiCore->ClearInput($_POST['an_loginid']);
		$an_txnkey=$bsiCore->ClearInput($_POST['an_txnkey']);
		$auth_account=$an_loginid."=|=".$an_txnkey;
		
		mysql_query("update bsi_payment_gateway set gateway_name='$pp_title', account='$paypal_id', enabled=$pp where gateway_code='pp'");
		mysql_query("update bsi_payment_gateway set gateway_name='$co_title', account='$co_id', enabled=$co where gateway_code='2co'");
		mysql_query("update bsi_payment_gateway set gateway_name='$poa_title',  enabled=$poa where gateway_code='poa'");
		mysql_query("update bsi_payment_gateway set gateway_name='$an_title', account='$auth_account', enabled=$an where gateway_code='an'");
	}
	//paygateway functions end
	
	//language function start
	public function langauge_setting(){
		global $bsiCore;
		$lang_sql2=mysql_query("select * from bsi_language order by lang_order");
		while($lang_row2=mysql_fetch_assoc($lang_sql2)){
			if($lang_row2['lang_code']==$_POST['lang_default']){
				mysql_query("update bsi_language set status=true, `default`=true, lang_order=".$bsiCore->ClearInput($_POST['order_'.$lang_row2['lang_code']])." where  lang_code='".$lang_row2['lang_code']."'");
			}else{
				if($lang_row2['status']==true and $lang_row2['default']==true){
				mysql_query("update bsi_language set status=true, `default`=false, lang_order=".$bsiCore->ClearInput($_POST['order_'.$lang_row2['lang_code']])." where  lang_code='".$lang_row2['lang_code']."'");
				}else{
				mysql_query("update bsi_language set status=".((is_null($_POST['lang_'.$lang_row2['lang_code']])) ? 0 : 1).", `default`=false, lang_order=".$bsiCore->ClearInput($_POST['order_'.$lang_row2['lang_code']])." where  lang_code='".$lang_row2['lang_code']."'");
				}
			 }
		}
	}	
	//langauge function end
	
	
	//pagination start**************************************************
	public function pagination($tbl_name, $noofperpage,$page,$targetpage,$type){
		/*
			Place code to connect to your DB here.
		*/
		$pagination_array=array();
		//your table name
		// How many adjacent pages should be shown on each side?
		$adjacents = 3;
		
		/* 
		   First get total number of rows in data table. 
		   If you have a WHERE clause in your query, make sure you mirror it here.
		*/
		$query = "SELECT COUNT(*) as num FROM $tbl_name where gallery_type=".$type;
		$total_pages = mysql_fetch_array(mysql_query($query));
		$total_pages = $total_pages['num'];
		
		/* Setup vars for query. */
		
		$limit =  $noofperpage; 
		if($page) 
		$start = ($page - 1) * $limit; 			//first item to display on this page
		else
			$start = 0;								//if no page var is given, set start to 0
		
		/* Get data. */
		$sql = "SELECT id, img_path,description FROM $tbl_name where gallery_type=$type order by id desc LIMIT $start, $limit  ";
		$result = mysql_query($sql);
		
		/* Setup page vars for display. */
		if ($page == 0) $page = 1;					//if no page var is given, default to 1.
		$prev = $page - 1;							//previous page is page - 1
		$next = $page + 1;							//next page is page + 1
		$lastpage = ceil($total_pages/$limit);		//lastpage is = total pages / items per page, rounded up.
		$lpm1 = $lastpage - 1;						//last page minus 1
		
		/* 
			Now we apply our rules and draw the pagination object. 
			We're actually saving the code to a variable in case we want to draw it more than once.
		*/
		$pagination = "";
		if($lastpage > 1)
		{	
			$pagination .= "<div class=\"pagination\">";
			//previous button
			if ($page > 1) 
				$pagination.= "<a href=\"$targetpage?page=$prev\">� previous</a>";
			else
				$pagination.= "<span class=\"disabled\">� previous</span>";	
			
			//pages	
			if ($lastpage < 7 + ($adjacents * 2))	//not enough pages to bother breaking it up
			{	
				for ($counter = 1; $counter <= $lastpage; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"$targetpage?page=$counter\">$counter</a>";					
				}
			}
			elseif($lastpage > 5 + ($adjacents * 2))	//enough pages to hide some
			{
				//close to beginning; only hide later pages
				if($page < 1 + ($adjacents * 2))		
				{
					for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
					{
						if ($counter == $page)
							$pagination.= "<span class=\"current\">$counter</span>";
						else
							$pagination.= "<a href=\"$targetpage?page=$counter\">$counter</a>";					
					}
					$pagination.= "...";
					$pagination.= "<a href=\"$targetpage?page=$lpm1\">$lpm1</a>";
					$pagination.= "<a href=\"$targetpage?page=$lastpage\">$lastpage</a>";		
				}
				//in middle; hide some front and some back
				elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
				{
					$pagination.= "<a href=\"$targetpage?page=1\">1</a>";
					$pagination.= "<a href=\"$targetpage?page=2\">2</a>";
					$pagination.= "...";
					for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
					{
						if ($counter == $page)
							$pagination.= "<span class=\"current\">$counter</span>";
						else
							$pagination.= "<a href=\"$targetpage?page=$counter\">$counter</a>";					
					}
					$pagination.= "...";
					$pagination.= "<a href=\"$targetpage?page=$lpm1\">$lpm1</a>";
					$pagination.= "<a href=\"$targetpage?page=$lastpage\">$lastpage</a>";		
				}
				//close to end; only hide early pages
				else
				{
					$pagination.= "<a href=\"$targetpage?page=1\">1</a>";
					$pagination.= "<a href=\"$targetpage?page=2\">2</a>";
					$pagination.= "...";
					for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
					{
						if ($counter == $page)
							$pagination.= "<span class=\"current\">$counter</span>";
						else
							$pagination.= "<a href=\"$targetpage?page=$counter\">$counter</a>";					
					}
				}
			}
			
			//next button
			if ($page < $counter - 1) 
				$pagination.= "<a href=\"$targetpage?page=$next\">next �</a>";
			else
				$pagination.= "<span class=\"disabled\">next �</span>";
			$pagination.= "</div>\n";		
		}
	    $pagination_array['pagination_return_sql']=$result;
		$pagination_array['page_list']=$pagination;
		$pagination_array['total_pages']=$total_pages;
		$pagination_array['limit']=$limit;
		return $pagination_array;
	}
	//pagination end**************************************************
	
	//gallery photo delete start.............
	public function gallery_photo_delete($g_category){
		global $bsiCore;
		$row_pic=mysql_fetch_assoc(mysql_query("select * from bsi_gallery where id=".$bsiCore->ClearInput(base64_decode($_REQUEST['pid']))));
		unlink("../gallery/".$row_pic['img_path']);
		if($g_category==1)
		unlink("../gallery/thumb_".$row_pic['img_path']);
		$is_delete=mysql_query("delete from bsi_gallery where id=".$bsiCore->ClearInput(base64_decode($_REQUEST['pid'])));
		return $is_delete;
	}
	//gallery photo delete end.............
	
	##################### THUMBNAIL CREATER FROM GIF / JPG / PNG
			
	private function make_thumbnails($updir, $img){
		    
			$thumbnail_width	= 146;
			$thumbnail_height	= 144;
			$thumb_preword		= "thumb_";
		
			$arr_image_details	= GetImageSize("$updir"."$img");
			$original_width		= $arr_image_details[0];
			$original_height	= $arr_image_details[1];
		
			if( $original_width > $original_height ){
				$new_width	= $thumbnail_width;
				$new_height	= intval($original_height*$new_width/$original_width);
			} else {
				$new_height	= $thumbnail_height;
				$new_width	= intval($original_width*$new_height/$original_height);
			}
		
			$dest_x = intval(($thumbnail_width - $new_width) / 2);
			$dest_y = intval(($thumbnail_height - $new_height) / 2);
		
		
		
			if($arr_image_details[2]==1) { $imgt = "ImageGIF"; $imgcreatefrom = "ImageCreateFromGIF";  }
			if($arr_image_details[2]==2) { $imgt = "ImageJPEG"; $imgcreatefrom = "ImageCreateFromJPEG";  }
			if($arr_image_details[2]==3) { $imgt = "ImagePNG"; $imgcreatefrom = "ImageCreateFromPNG";  }
		
		
			if( $imgt ) { 
				$old_image	= $imgcreatefrom("$updir"."$img");
				$new_image	= imagecreatetruecolor($thumbnail_width, $thumbnail_height);
				imageCopyResized($new_image,$old_image,0, 0,0,0,146,144,$original_width,$original_height);
				$imgt($new_image,"$updir"."$thumb_preword"."$img");
			}
		
	}
		################################# UPLOAD IMAGES
	public function main_gallery_img_upload(){
		$enable_thumbnails	= 1 ; // set 0 to disable thumbnail creation
		$max_image_size		= 1024000 ; // max image size in bytes, default 1MB
		$upload_dir			= "../gallery/"; // default script location, use relative or absolute path
		
		foreach($_FILES as $k => $v){ 

			$img_type = "";

			### $htmo .= "$k => $v<hr />"; 	### print_r($_FILES);

			if( !$_FILES[$k]['error'] && preg_match("#^image/#i", $_FILES[$k]['type']) && $_FILES[$k]['size'] < $max_image_size ){

				$img_type = ($_FILES[$k]['type'] == "image/jpeg") ? ".jpg" : $img_type ;
				$img_type = ($_FILES[$k]['type'] == "image/gif") ? ".gif" : $img_type ;
				$img_type = ($_FILES[$k]['type'] == "image/png") ? ".png" : $img_type ;

				$img_rname = time().'_'.$_FILES[$k]['name'];
				$img_path = $upload_dir.$img_rname;

				copy( $_FILES[$k]['tmp_name'], $img_path ); 
				if($enable_thumbnails) $this->make_thumbnails($upload_dir, $img_rname);
				$aa=mysql_query("insert into bsi_gallery(img_path, gallery_type) values('".$img_rname."','1')");
				//$feedback .= "Image and thumbnail created $img_rname<br />";
			}
		}	
	
	}
	//dddddddddddddddddddddddddddddd
	function slider_make_thumbnails($updir, $img){

		$thumbnail_width	= 980;
		$thumbnail_height	= 313;
		$thumb_preword		= "";
	
		$arr_image_details	= GetImageSize("$updir"."$img");
		$original_width		= $arr_image_details[0];
		$original_height	= $arr_image_details[1];
	
		if( $original_width > $original_height ){
			$new_width	= $thumbnail_width;
			$new_height	= intval($original_height*$new_width/$original_width);
		} else {
			$new_height	= $thumbnail_height;
			$new_width	= intval($original_width*$new_height/$original_height);
		}
	
		$dest_x = intval(($thumbnail_width - $new_width) / 2);
		$dest_y = intval(($thumbnail_height - $new_height) / 2);
	
	
	
		if($arr_image_details[2]==1) { $imgt = "ImageGIF"; $imgcreatefrom = "ImageCreateFromGIF";  }
		if($arr_image_details[2]==2) { $imgt = "ImageJPEG"; $imgcreatefrom = "ImageCreateFromJPEG";  }
		if($arr_image_details[2]==3) { $imgt = "ImagePNG"; $imgcreatefrom = "ImageCreateFromPNG";  }
	
	
		if( $imgt ) { 
			$old_image	= $imgcreatefrom("$updir"."$img");
			$new_image	= imagecreatetruecolor($thumbnail_width, $thumbnail_height);
			imageCopyResized($new_image,$old_image,0, 0,0,0,980,313,$original_width,$original_height);
			$imgt($new_image,"$updir"."$thumb_preword"."$img");
		}
	
	}
	public function slider_gallery_img_upload(){
		$k2=0;
		$enable_thumbnails	= 1 ; // set 0 to disable thumbnail creation
		$max_image_size		= 1024000 ; // max image size in bytes, default 1MB
		$upload_dir			= "../gallery/"; // default script location, use relative or absolute path
		foreach($_FILES as $k => $v){ 

			$img_type = "";

			### $htmo .= "$k => $v<hr />"; 	### print_r($_FILES);

			if( !$_FILES[$k]['error'] && preg_match("#^image/#i", $_FILES[$k]['type']) && $_FILES[$k]['size'] < $max_image_size ){

				$img_type = ($_FILES[$k]['type'] == "image/jpeg") ? ".jpg" : $img_type ;
				$img_type = ($_FILES[$k]['type'] == "image/gif") ? ".gif" : $img_type ;
				$img_type = ($_FILES[$k]['type'] == "image/png") ? ".png" : $img_type ;

				$img_rname = time().'_'.$_FILES[$k]['name'];
				$img_path = $upload_dir.$img_rname;

				copy( $_FILES[$k]['tmp_name'], $img_path ); 
				if($enable_thumbnails) $this->slider_make_thumbnails($upload_dir, $img_rname);
				$aa=mysql_query("insert into bsi_gallery(img_path, gallery_type, description) values('".$img_rname."','2', '".$_POST['desc'][$k2]."')");
				//$feedback .= "Image and thumbnail created $img_rname<br />";
				
                $k2++;
			}
		}
	
	}
	// image upload classes end
	//price manager functions start
	public function priceplan_listing(){
		global $bsiCore;
		$priceplan_array=array();
		
		return $priceplan_array;
			
	}
	
	//hotel extras
	public function hotel_extras(){
		global $bsiCore;
		$hotel_extra_return=array();
		$hotel_extra_view="";
		$sql_veiw_extras=mysql_query("select * from bsi_extras");
		while($row_veiw_extras=mysql_fetch_assoc($sql_veiw_extras)){
		$hotel_extra_view.='  <tr><td>'.$row_veiw_extras['description'].'</td><td>&nbsp;&nbsp;'.$bsiCore->config['conf_currency_symbol'].$row_veiw_extras['fees'].'</td><td>&nbsp;&nbsp;<input type="button" value="Delete" name="delete" onclick="javascript:window.location.href=\'hotel_extras.php?edid='.$row_veiw_extras['extras_id'].'\'"></td></tr>';
		}
		
		$hotel_extra_return['hotel_extra_view']=$hotel_extra_view;
		return 	$hotel_extra_return;
	}
	
	public function del_hotel_extras(){
		global $bsiCore;
		$delete_success=mysql_query("delete from  bsi_extras where extras_id=".$bsiCore->ClearInput($_GET['edid']));	
	}
	
		//hotel extras
	public function hotel_coupon(){
	    global $bsiCore;
		$hotel_coupon_return=array();
		$hotel_coupon_view="";
		$sql_veiw_coupon=mysql_query("select promo_id, promo_code, discount, min_amount, percentage, promo_category, customer_email, DATE_FORMAT(exp_date, '".$bsiCore->userDateFormat."') AS exp_date, reuse_promo from bsi_promocode");
		while($row_veiw_coupon=mysql_fetch_assoc($sql_veiw_coupon)){
		$amt=($row_veiw_coupon['percentage']==true)? $row_veiw_coupon['discount'].'%' : $bsiCore->config['conf_currency_symbol'].$row_veiw_coupon['discount'];
		$min_amt=($row_veiw_coupon['min_amount']=='0.00')? 'None' : $bsiCore->config['conf_currency_symbol'].$row_veiw_coupon['min_amount'];
		$exp_dt=($row_veiw_coupon['exp_date']=='')? 'None' : $row_veiw_coupon['exp_date'];
		switch($row_veiw_coupon['promo_category']){
			case '1': $customer="All customer"; break;
			case '2': $customer="Existing Customer"; break;
			case '3': $customer=$row_veiw_coupon['customer_email']; break;
		}
		$reuse_coupon=($row_veiw_coupon['reuse_promo']==true)? 'Yes' : 'No';
		
		$hotel_coupon_view.='  <tr><td>'.$row_veiw_coupon['promo_code'].'</td><td>'.$amt.'</td><td>'.$min_amt.'</td><td>'.$exp_dt.'</td><td>'.$customer.'</td><td>'.$reuse_coupon.'</td><td>&nbsp;&nbsp;<input type="button" value="Delete" name="delete" onclick="javascript:window.location.href=\'discount_coupon.php?edid='.$row_veiw_coupon['promo_id'].'\'"></td></tr>';
		}
		
		$hotel_coupon_return['hotel_coupon_view']=$hotel_coupon_view;
		return 	$hotel_coupon_return;
	}
	
	
	public function del_hotel_coupon(){
		global $bsiCore;
		$delete_success=mysql_query("delete from  bsi_promocode where promo_id=".$bsiCore->ClearInput($_GET['edid']));	
	}
	
	
	//capacity add/edit *************
	public function capacity_addedit(){
	    global $bsiCore;
		if($_POST['id']){
			mysql_query("update bsi_capacity set title='".$bsiCore->ClearInput($_POST['capacityTitle'])."' where id=".$bsiCore->ClearInput($_POST['id']));
		}else{
			mysql_query("insert into bsi_capacity(title, capacity) values('".$bsiCore->ClearInput($_POST['capacityTitle'])."',".$bsiCore->ClearInput($_POST['NoOfAdult']).")");
		}	
	}
	
	public function capacity_delete(){
		global $bsiCore;
		$delid=$bsiCore->ClearInput($_REQUEST['delid']);
		mysql_query("delete from bsi_capacity where id=".$delid);
		mysql_query("delete from bsi_room where capacity_id=".$delid);
		mysql_query("delete from bsi_priceplan where capacity_id=".$delid);
	}
	
	//room type classess********************************
		public function roomtype_delete(){
		global $bsiCore;
		$delid=$bsiCore->ClearInput($_REQUEST['delid']);
		mysql_query("delete from bsi_roomtype where roomtype_ID=".$delid);
		mysql_query("delete from bsi_priceplan where roomtype_id=".$delid);
		mysql_query("delete from bsi_room where roomtype_id=".$delid);
	}
	
	public function roomtype_addedit(){
		global $bsiCore;
		$id=$bsiCore->ClearInput($_REQUEST['id']);
		$roomtype=$bsiCore->ClearInput($_POST['roomtype']);
		
		$sql_capacity=mysql_query("select * from  bsi_capacity");
		
		
		if($id){
		mysql_query("update bsi_roomtype set type_name='$roomtype' where roomtype_ID=$id");
				if($_POST['extrabed']==""){
				$extrabed_pp=0.00;
				mysql_query("update bsi_priceplan set extrabed=".$extrabed_pp." where roomtype_ID=$id");
				}else{
				$extrabed_pp=$bsiCore->ClearInput($_POST['extrabed']);
				}
				
		while($row_capacity=mysql_fetch_assoc($sql_capacity)){
		
				if($_POST[strtolower($row_capacity['title'])] == ""){
				$sql_priceplan_del=mysql_query("SELECT * FROM `bsi_priceplan` WHERE `roomtype_id`=$id and `default_plan`=false and capacity_id=".$row_capacity['id']." group by `roomtype_id`,`start_date`");
					while($row_priceplan_del=mysql_fetch_assoc($sql_priceplan_del)){
					mysql_query("delete from bsi_priceplan where plan_id=".$row_priceplan_del['plan_id']);
					}
				mysql_query("delete from bsi_room where roomtype_id=$id and capacity_id =".$row_capacity['id']);
				}
				
				mysql_query("delete from bsi_priceplan where roomtype_id=$id  and default_plan=true and capacity_id=".$row_capacity['id']);
				if($_POST[strtolower($row_capacity['title'])] != ""){
				$count_priceplan=mysql_num_rows(mysql_query("SELECT * FROM `bsi_priceplan` WHERE `roomtype_id`=$id and `default_plan`=false and capacity_id=".$row_capacity['id']." group by `roomtype_id`,`start_date`"));
					if($count_priceplan==0){
						$sql_pp=mysql_query("SELECT * FROM `bsi_priceplan` WHERE `roomtype_id`=$id and `default_plan`=false  group by `roomtype_id`,`start_date`");
						while($row_pp=mysql_fetch_assoc($sql_pp)){
						mysql_query("insert into bsi_priceplan(roomtype_id, capacity_id, start_date, end_date, price, extrabed, default_plan) values($id, ".$row_capacity['id'].", '".$row_pp['start_date']."', '".$row_pp['end_date']."',".$bsiCore->ClearInput($_POST[strtolower($row_capacity['title'])]).", ".$row_pp['extrabed'].", false)");
						}
					
					}
				//echo "delete from bsi_priceplan where roomtype_id=$id  and default_plan=true and capacity_id=".$row_capacity['id'];
				mysql_query("insert into bsi_priceplan(roomtype_id, capacity_id, price, extrabed, default_plan) values($id, ".$row_capacity['id'].", ".$bsiCore->ClearInput($_POST[strtolower($row_capacity['title'])]).", ".$extrabed_pp.", true)");
				
				//echo "update bsi_priceplan set price=".mysql_real_escape_string($_POST[strtolower($row_capacity['title'])])." where roomtype_ID=$id and capacity_id=".$row_capacity['id'];
				}
		}
			
		} else {
			mysql_query("insert into bsi_roomtype(type_name) values('$roomtype')");
			$roomtype_id1=mysql_insert_id();
		
			while($row_capacity=mysql_fetch_assoc($sql_capacity)){
					if($_POST[strtolower($row_capacity['title'])] != ""){
					
					if($_POST['extrabed']=="")
					$extrabed_pp=0.00;
					else
					$extrabed_pp=$bsiCore->ClearInput($_POST['extrabed']);
					
					mysql_query("insert into bsi_priceplan(roomtype_id, capacity_id, price, extrabed, default_plan) values($roomtype_id1, ".$row_capacity['id'].", ".$bsiCore->ClearInput($_POST[strtolower($row_capacity['title'])]).", ".$extrabed_pp.", true)");
			
					}
			}
		
		}
	}
	
	
	//custom pagination************************************************
	public function pagination_global($tbl_name, $noofperpage,$page,$targetpage,$type){
	global $bsiCore;
		/*
			Place code to connect to your DB here.
		*/
		$pagination_array=array();
		//your table name
		// How many adjacent pages should be shown on each side?
		$adjacents = 3;
		
		/* 
		   First get total number of rows in data table. 
		   If you have a WHERE clause in your query, make sure you mirror it here.
		*/
		switch($type){
		case 1:
		$query = "SELECT COUNT(*) as num FROM $tbl_name where payment_success=true and CURDATE() <= end_date and is_block=false and is_deleted=false order by start_date";
		break;
		
		case 2:
		$query = "SELECT COUNT(*) as num FROM $tbl_name where payment_success=true and (CURDATE() > end_date OR is_deleted=true) and is_block=false ";
		break;	
		
		case 3:
		$query = "SELECT COUNT(*) as num FROM $tbl_name";
		break;	
		}
		$total_pages = mysql_fetch_array(mysql_query($query));
		$total_pages = $total_pages['num'];
		
		/* Setup vars for query. */
		
		$limit =  $noofperpage; 
		if($page) 
		$start = ($page - 1) * $limit; 			//first item to display on this page
		else
		$start = 0;								//if no page var is given, set start to 0
		switch($type){
		case 1:
		$sql = "SELECT booking_id, DATE_FORMAT(start_date, '".$bsiCore->userDateFormat."') AS start_date, DATE_FORMAT(end_date, '".$bsiCore->userDateFormat."') AS end_date, total_cost, DATE_FORMAT(booking_time, '".$bsiCore->userDateFormat."') AS booking_time, payment_type, client_id  FROM $tbl_name where payment_success=true and CURDATE() <= end_date and is_deleted=false and is_block=false order by start_date  LIMIT $start, $limit";
		break;
		
		case 2:
		$sql = "SELECT booking_id, DATE_FORMAT(start_date, '".$bsiCore->userDateFormat."') AS start_date, DATE_FORMAT(end_date, '".$bsiCore->userDateFormat."') AS end_date, total_cost, DATE_FORMAT(booking_time, '".$bsiCore->userDateFormat."') AS booking_time, payment_type, client_id, is_deleted  FROM $tbl_name where payment_success=true and (CURDATE() > end_date OR is_deleted=true)  and is_block=false order by start_date  LIMIT $start, $limit";
		break;
		
		case 3:
		$sql ="SELECT * FROM $tbl_name LIMIT $start, $limit";
		break;
		}
		/* Get data. */
		$result = mysql_query($sql);
		
		/* Setup page vars for display. */
		if ($page == 0) $page = 1;					//if no page var is given, default to 1.
		$prev = $page - 1;							//previous page is page - 1
		$next = $page + 1;							//next page is page + 1
		$lastpage = ceil($total_pages/$limit);		//lastpage is = total pages / items per page, rounded up.
		$lpm1 = $lastpage - 1;						//last page minus 1
		
		/* 
			Now we apply our rules and draw the pagination object. 
			We're actually saving the code to a variable in case we want to draw it more than once.
		*/
		$pagination = "";
		if($lastpage > 1)
		{	
			$pagination .= "<div class=\"pagination\">";
			//previous button
			if ($page > 1) 
				$pagination.= "<a href=\"$targetpage?page=$prev\">� previous</a>";
			else
				$pagination.= "<span class=\"disabled\">� previous</span>";	
			
			//pages	
			if ($lastpage < 7 + ($adjacents * 2))	//not enough pages to bother breaking it up
			{	
				for ($counter = 1; $counter <= $lastpage; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"$targetpage?page=$counter\">$counter</a>";					
				}
			}
			elseif($lastpage > 5 + ($adjacents * 2))	//enough pages to hide some
			{
				//close to beginning; only hide later pages
				if($page < 1 + ($adjacents * 2))		
				{
					for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
					{
						if ($counter == $page)
							$pagination.= "<span class=\"current\">$counter</span>";
						else
							$pagination.= "<a href=\"$targetpage?page=$counter\">$counter</a>";					
					}
					$pagination.= "...";
					$pagination.= "<a href=\"$targetpage?page=$lpm1\">$lpm1</a>";
					$pagination.= "<a href=\"$targetpage?page=$lastpage\">$lastpage</a>";		
				}
				//in middle; hide some front and some back
				elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
				{
					$pagination.= "<a href=\"$targetpage?page=1\">1</a>";
					$pagination.= "<a href=\"$targetpage?page=2\">2</a>";
					$pagination.= "...";
					for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
					{
						if ($counter == $page)
							$pagination.= "<span class=\"current\">$counter</span>";
						else
							$pagination.= "<a href=\"$targetpage?page=$counter\">$counter</a>";					
					}
					$pagination.= "...";
					$pagination.= "<a href=\"$targetpage?page=$lpm1\">$lpm1</a>";
					$pagination.= "<a href=\"$targetpage?page=$lastpage\">$lastpage</a>";		
				}
				//close to end; only hide early pages
				else
				{
					$pagination.= "<a href=\"$targetpage?page=1\">1</a>";
					$pagination.= "<a href=\"$targetpage?page=2\">2</a>";
					$pagination.= "...";
					for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
					{
						if ($counter == $page)
							$pagination.= "<span class=\"current\">$counter</span>";
						else
							$pagination.= "<a href=\"$targetpage?page=$counter\">$counter</a>";					
					}
				}
			}

			
			//next button
			if ($page < $counter - 1) 
				$pagination.= "<a href=\"$targetpage?page=$next\">next �</a>";
			else
				$pagination.= "<span class=\"disabled\">next �</span>";
			$pagination.= "</div>\n";		
		}
	    $pagination_array['pagination_return_sql']=$result;
		$pagination_array['page_list']=$pagination;
		$pagination_array['total_pages']=$total_pages;
		$pagination_array['limit']=$limit;
		return $pagination_array;
	}
	
	
	//booking cancel and delete *************************
	public function booking_cencel_delete($type){
		global $bsiCore;
		global $bsiMail;
		switch($type){
			case 1:
				$bsiMail = new bsiMail();
				$is_cancel=mysql_query("update bsi_bookings set is_deleted=true where booking_id=".$bsiCore->ClearInput(base64_decode($_REQUEST['cid'])));
				if($is_cancel){
				//$bsicommon = new bsiCommon();
				$cust_details=mysql_fetch_assoc(mysql_query("select * from bsi_invoice where booking_id=".$bsiCore->ClearInput(base64_decode($_REQUEST['cid']))));
				$email_details=mysql_fetch_assoc(mysql_query("select * from bsi_email_contents where id=2"));
				$cancel_emailBody="Dear ".$cust_details['client_name']."<br>";
				$cancel_emailBody.=$email_details['email_text']."<br>";
				$cancel_emailBody.="<b>Your Booking Details:</b><br>".$cust_details['invoice']."<br>";
				$cancel_emailBody.="<b>Regards</b><br>".$bsiCore->config['conf_hotel_name']."<BR>".$bsiCore->config['conf_hotel_phone']."<br>";
				 $bsiMail->sendEMail($cust_details['client_email'], $email_details['email_subject'], $cancel_emailBody);
				
				}
			break;
			
			case 2:
				mysql_query("delete from  bsi_bookings where booking_id=".$bsiCore->ClearInput(base64_decode($_REQUEST['delid'])));
				mysql_query("delete from  bsi_reservation where bookings_id=".$bsiCore->ClearInput(base64_decode($_REQUEST['delid'])));
				mysql_query("delete from  bsi_invoice where booking_id=".$bsiCore->ClearInput(base64_decode($_REQUEST['delid'])));
			break;
		
		}	
	}
	
	//room_add_edit***********************
	public function room_add_edit(){
		global $bsiCore;
		$id=$bsiCore->ClearInput($_REQUEST['id']);
		$roomno=$bsiCore->ClearInput($_POST['roomno']);
		$roomtype=$bsiCore->ClearInput($_POST['roomtype']);
		//$nochild=mysql_real_escape_string($_POST['nochild']);
		$nochild = ($_POST['nochild']=="") ? '0' : $bsiCore->ClearInput($_POST['nochild']);
		$extrabed1 = (isset($_POST['extrabed'])) ? 'true' : 'false';
		$roomcapid=$bsiCore->ClearInput($_POST['roomcapid']);
		$sql876=mysql_query("select * from bsi_room where room_no='".$roomno."'");
		if($id){
			mysql_query("update bsi_room set roomtype_id=$roomtype, room_no='$roomno', capacity_id=$roomcapid, no_of_child=$nochild, extra_bed=$extrabed1 where room_ID=".$id);
			header("location:room_list.php");
		} else {
			if(mysql_num_rows($sql876)){
			header("location:room_list.php?error=1");
			}else{
			mysql_query("insert into bsi_room(roomtype_id, room_no, capacity_id, no_of_child, extra_bed) values($roomtype,'$roomno', $roomcapid, $nochild, $extrabed1)");
			header("location:room_list.php");
			}
		}
		
	}
	
	//price plan******************
	public function priceplan_add_edit(){
		global $bsiCore;
		$start_date_old=$bsiCore->ClearInput($_REQUEST['start_date_old']);
		$roomtype=$bsiCore->ClearInput($_POST['roomtype']);
		$id=$bsiCore->ClearInput($_POST['roomtype_edit']);
		$startdate=$bsiCore->getMySqlDate($bsiCore->ClearInput($_POST['startdate']));
		$closingdate=$bsiCore->getMySqlDate($bsiCore->ClearInput($_POST['closingdate']));
		$sql_capacity=mysql_query("select * from  bsi_capacity");
		
		$date_exist=mysql_num_rows(mysql_query("select * from bsi_priceplan where roomtype_id=$roomtype and (('$startdate'  Between start_date and  end_date OR  '$closingdate' between  start_date and  end_date ) OR (start_date between '$startdate' and '$closingdate' OR end_date between '$startdate' and '$closingdate')) and start_date <> '$start_date_old' group by roomtype_id"));
		
		if(!$date_exist){
				if($id){
				   if($_POST['extrabed']=="")
					$extrabed_pp=0.00;
					else
					$extrabed_pp=$bsiCore->ClearInput($_POST['extrabed']);
					
				
					mysql_query("delete from bsi_priceplan where roomtype_id=$roomtype  and start_date='$start_date_old'");
					while($row_capacity=mysql_fetch_assoc($sql_capacity)){
					if(isset($_POST[strtolower($row_capacity['title'])])){
					mysql_query("insert into bsi_priceplan(roomtype_id, start_date, end_date, capacity_id, price, extrabed, default_plan) values($roomtype, '$startdate', '$closingdate', ".$row_capacity['id'].", ".$bsiCore->ClearInput($_POST[strtolower($row_capacity['title'])]).", ".$extrabed_pp.", false)");
					//echo "insert into bsi_priceplan(roomtype_id, start_date, end_date, capacity_id, price, extrabed, default_plan) values($roomtype, '$startdate', '$closingdate', ".$row_capacity['id'].", ".mysql_real_escape_string($_POST[strtolower($row_capacity['title'])]).", ".$extrabed_pp.", false)";
						}
					}
				} else {
				if(isset($_POST['extrabed']))
					$extrabed_pp=$bsiCore->ClearInput($_POST['extrabed']);
					else
					$extrabed_pp=0.00;
					
				while($row_capacity=mysql_fetch_assoc($sql_capacity)){
					if(isset($_POST[strtolower($row_capacity['title'])])){
					mysql_query("insert into bsi_priceplan(roomtype_id, start_date, end_date, capacity_id, price, extrabed, default_plan) values($roomtype, '$startdate', '$closingdate', ".$row_capacity['id'].", ".$bsiCore->ClearInput($_POST[strtolower($row_capacity['title'])]).", ".$extrabed_pp.", false)");
						}
					}
				}
			header("location:priceplan.php");
		
			}else{
			 header("location:priceplan.php?error_code=1");
			}
	}
} //class end
?>