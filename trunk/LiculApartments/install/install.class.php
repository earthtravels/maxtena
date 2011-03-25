<?php
class bsiInstallStart
{	
	private $bsiCoreRoot = '';
	private $bsiHostPath = '';
	private $bsiGallery = 'gallery/';
	private $bsiDBCONFile = '/includes/db.conn.php';
	
	public $installinfo = array('php_version'=>false, 'gd_version'=>false, 'config_file'=>false, 'gallery_path'=>false);
	public $installerror = array('session_disabled'=>false, 'config_notwritable'=>false, 'gallery_notwritable'=>false, 'gd_notinstalled'=>false, 'gd_versionnotpermit'=>false, 'mysql_notavailable'=>false);
	
	function bsiInstallStart(){
		$this->getPathInfo();
		$this->getInstallInfo(); 
	}

	private function getPathInfo(){
		$path_info = pathinfo($_SERVER["SCRIPT_FILENAME"]);
		preg_match("/(.*[\/\\\])/",$path_info['dirname'],$tmpvar);
		$this->bsiCoreRoot = $tmpvar[1];		
		$host_info = pathinfo($_SERVER["PHP_SELF"]);
		$this->bsiHostPath = "http://".$_SERVER['HTTP_HOST'].$host_info['dirname']."/";		
	}
	public function getInstallInfo(){
		$this->installinfo['php_version'] = phpversion();
		
		if (!session_id()) $this->installerror['session_disabled'] = true;
		
		$this->installinfo['config_file'] = $this->bsiCoreRoot.$this->bsiDBCONFile;
		
		// check writable settings file
		if (!is_writable($this->installinfo['config_file'])) $this->installerror['config_notwritable'] = true; 
		
		$this->installinfo['gallery_path'] = $this->bsiCoreRoot.$this->bsiGallery;
		if (!$this->checkFolder($this->installinfo['gallery_path'])) $this->installerror['gallery_notwritable'] = true;
				
		if (!in_array("gd",get_loaded_extensions())) {
			$this->installerror['gd_notinstalled'] = true;
			$this->installerror['gd_versionnotpermit'] = true;
		}
		
		if (!$this->installerror['gd_notinstalled'] && function_exists('gd_info')){
			$info = gd_info();
			$this->installinfo['gd_version'] = preg_replace("/[^\d\.]/","",$info['GD Version'])*1;	
			if ($this->installinfo['gd_version'] < 2) $this->installerror['gd_versionnotpermit'] = true;
		}
		
		if (!in_array("mysql",get_loaded_extensions())) $this->installerror['mysql_notavailable'] = true;			
	}
	
	private function checkFolder($folderPath){
		if ( !($fileHandler=@fopen($folderPath."sample_bsi_dir_test.php","a+"))) return false;
		if (!@fwrite($fileHandler,"test")) return false;
		if (!@fclose($fileHandler)) return false;
		if (!@unlink($folderPath."sample_bsi_dir_test.php")) return false;
		
		return true;
	}	
} 

class bsiInstallFinish
{	
	public $adminUserName = '';
	public $adminUserPass = '';
	public $userSitePath = '';
	public $adminSitePath = '';
	
	private $encAdminPass = '';
	private $hotelName = '';
	private $hotelEmail = '';
		
	function bsiInstallFinish(){
		$this->getAuthParams();		
		$this->updateConfigData();
		$this->getHostPaths();
	}

	private function getAuthParams(){
		if(trim($_POST["admin_login"])){
			$this->adminUserName = trim($_POST["admin_login"]);
		}else{
			$this->adminUserName = "admin@".$_SERVER['HTTP_HOST'];
		}		
		
		if(trim($_POST["admin_password"])){
			$this->adminUserPass = trim($_POST["admin_password"]);
		}else{
			$this->adminUserPass = $this->autoGeneratePassword(8,8);
		}
		$this->encAdminPass = md5($this->adminUserPass);		
		
		if(trim($_POST["hotel_name"])){
			$this->hotelName = trim($_POST["hotel_name"]);
		}else{
			$this->hotelName = false;
		}
		
		if(trim($_POST["hotel_email"])){
			$this->hotelEmail = trim($_POST["hotel_email"]);
		}else{
			$this->hotelEmail = false;
		}				
	}
	
	private function autoGeneratePassword($length=10, $strength=0) {
		$vowels = 'aeuy';
		$consonants = 'bdghjmnpqrstvz';
		if ($strength & 1) {
			$consonants .= 'BDGHJLMNPQRSTVWXZ';
		}
		if ($strength & 2) {
			$vowels .= "AEUY";
		}
		if ($strength & 4) {
			$consonants .= '23456789';
		}
		if ($strength & 8) {
			$consonants .= '@#$%~';
		}
	 
		$password = '';
		$alt = time() % 2;
		for ($i = 0; $i < $length; $i++) {
			if ($alt == 1) {
				$password .= $consonants[(rand() % strlen($consonants))];
				$alt = 0;
			} else {
				$password .= $vowels[(rand() % strlen($vowels))];
				$alt = 1;
			}
		}
		return $password;
	}	
	
	private function updateConfigData(){
		mysql_query("UPDATE bsi_admin SET username = '".$this->adminUserName."', pass = '".$this->encAdminPass."' WHERE id = 1");
				
		if($this->hotelName){
			mysql_query("UPDATE bsi_configure SET conf_value = '".$this->hotelName."' WHERE conf_key = 'conf_hotel_name'");
		}
		if($this->hotelEmail){
			mysql_query("UPDATE bsi_configure SET conf_value = '".$this->hotelEmail."' WHERE conf_key = 'conf_hotel_email'");
		}
	}
	private function getHostPaths(){
		$host_info = pathinfo($_SERVER["PHP_SELF"]);		
		$bsiHostPath = "http://".$_SERVER['HTTP_HOST'].substr($host_info['dirname'], 0, strrpos($host_info['dirname'], '/'))."/";
		$this->adminSitePath = $bsiHostPath."admin/index.php";
		$this->userSitePath = $bsiHostPath."index.php";	
	}
}

class bsiInstallScript
{	
	private $bsiCoreRoot = '';
	private $bsiDBCONFile = '/includes/db.conn.php';
	public  $installerror = array('save_conn'=>false, 'mysql_conn'=>false, 'create_db'=>false, 'create_table'=>false);
	
	function bsiInstallScript(){
		$this->setConfigPath();
		$this->doInstallScript();
	}

	private function setConfigPath(){
		$path_info = pathinfo($_SERVER["SCRIPT_FILENAME"]);
		preg_match("/(.*[\/\\\])/",$path_info['dirname'],$tmpvar);
		$this->bsiCoreRoot = $tmpvar[1];			
	}
	
	private function cleanString($string){	
		$string = preg_replace("/[\'\/\\\]/","",stripslashes($string));
		return $string;
	}
	
	public function writeFile($filestring){		
		$this->bsiDBCONFile = $this->bsiCoreRoot.$this->bsiDBCONFile;		
		$fhandle = fopen($this->bsiDBCONFile,"w");
		if (!$fhandle) {
			return false;
		}	
		if (fwrite($fhandle, $filestring) === FALSE) {
			return false;
		}
		fclose ($fhandle);
		return true;
	}		
		
	public function doInstallScript(){		
		$mysql_host = $this->cleanString($_POST['mysql_host']);
		$mysql_host = !$mysql_host?"localhost":$mysql_host;	
		
		$mysql_user = $this->cleanString($_POST['mysql_login']);
		$mysql_pass = $this->cleanString($_POST['mysql_password']);
		$mysql_db   = $this->cleanString($_POST['mysql_db']);
				
		$filestring = "<?php\ndefine(\"MYSQL_SERVER\", \"".$mysql_host."\");\ndefine(\"MYSQL_USER\", \"".$mysql_user."\");\ndefine(\"MYSQL_PASSWORD\", \"".$mysql_pass."\");\ndefine(\"MYSQL_DATABASE\", \"".$mysql_db."\");\n\nmysql_connect(MYSQL_SERVER,MYSQL_USER,MYSQL_PASSWORD) or die ('I cannot connect to the database because 1: ' . mysql_error());\nmysql_select_db(MYSQL_DATABASE) or die ('I cannot connect to the database because 2: ' . mysql_error());\n?>";		
						
		if(!$this->writeFile($filestring)){  // save settings
			$this->installerror['save_conn'] = true;
		}

		$mysql_link = @mysql_connect ($mysql_host,$mysql_user,$mysql_pass);	
	
		
		if ($mysql_link){		
			if(!mysql_select_db($mysql_db,$mysql_link)){
				// attempt to create db when doesn't exists
				if(!mysql_query ("create database ".$mysql_db, $mysql_link)) {
					$this->installerror['create_db'] = true; 
				}else{
					mysql_select_db ($mysql_db, $mysql_link);
				}
			}
		}else{			
			$this->installerror['mysql_conn'] = true;
			$this->installerror['create_db'] = true; 
		}
		

		// no errors if mysql connection successful and db is exists or was created		
		if (!$this->installerror['mysql_conn'] && !$this->installerror['create_db']){

			//install dbscripts
			$this->installDBScripts();
			
			// check if all tables was created correctly
             $allowed_tables = array(1=>"bsi_admin", "bsi_adminmenu", "bsi_bookings", "bsi_capacity", "bsi_clients", "bsi_configure", "bsi_contents", "bsi_email_contents", "bsi_gallery", "bsi_invoice", "bsi_payment_gateway", "bsi_priceplan", "bsi_reservation", "bsi_room", "bsi_roomtype", "bsi_site_contents");
			 
             $res = mysql_query("show tables");			
             while ($row =@mysql_fetch_row($res)){				 
                  $table = preg_replace("/(.*)/","$1",$row[0]); 
                  if ($key = array_search($table,$allowed_tables)) {
                      unset ($allowed_tables[$key]);
                  }
             }

             if (count($allowed_tables)>0) $this->installerror['create_table'] = true;  // not all tables was created			
		}else{
			$this->installerror['create_table'] = true;
		}		
	}	
	
	private function installDBScripts(){
		mysql_query("drop table if exists `bsi_admin`");
		mysql_query("CREATE TABLE IF NOT EXISTS `bsi_admin` (
							  `id` int(11) NOT NULL AUTO_INCREMENT,
							  `pass` varchar(255) NOT NULL,
							  `username` varchar(50) NOT NULL DEFAULT 'admin',
							  `access_id` int(1) NOT NULL DEFAULT '0',
							  `f_name` varchar(255) NOT NULL,
							  `l_name` varchar(255) NOT NULL,
							  `email` varchar(255) NOT NULL,
							  `designation` varchar(255) NOT NULL,
							  `last_login` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
							  `phone` varchar(20) NOT NULL,
							  PRIMARY KEY (`id`)
							) ENGINE=InnoDB  DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;");
		mysql_query("INSERT INTO `bsi_admin` (`id`, `pass`, `username`, `access_id`, `f_name`, `l_name`, `email`, `designation`, `last_login`, `phone`) VALUES
(1, '21232f297a57a5a743894a0e4a801fc3', 'admin', 1, 'Administrator', '', 'admin@aaaa.com', 'CEO', '2010-09-16 06:12:49', '11111111');");
		
		mysql_query("drop table if exists `bsi_adminmenu`");
		mysql_query("CREATE TABLE IF NOT EXISTS `bsi_adminmenu` (
							  `id` int(4) NOT NULL AUTO_INCREMENT,
							  `name` varchar(200) NOT NULL DEFAULT '',
							  `url` varchar(200) DEFAULT NULL,
							  `menu_desc` varchar(200) NOT NULL DEFAULT '',
							  `parent_id` int(4) DEFAULT '0',
							  `status` enum('Y','N') DEFAULT 'Y',
							  `ord` int(5) NOT NULL DEFAULT '0',
							  `privileges` int(11) NOT NULL,
							  PRIMARY KEY (`id`),
							  UNIQUE KEY `kid` (`name`,`parent_id`)
							) ENGINE=InnoDB  DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;");
		mysql_query("INSERT INTO `bsi_adminmenu` (`id`, `name`, `url`, `menu_desc`, `parent_id`, `status`, `ord`, `privileges`) VALUES
							(1, 'HOME', 'admin_home.php', '', 0, 'Y', 1, 0),
							(2, 'CONTENTS MANGER', '#', '', 0, 'Y', 5, 0),
							(6, 'SETTING', '#', '', 0, 'Y', 9, 0),
							(31, 'Global Setting', 'global_setting.php', '', 6, 'Y', 1, 1),
							(33, 'HOTEL MANAGER', '#', '', 0, 'Y', 2, 1),
							(34, 'Room Manager', 'room_list.php', '', 33, 'Y', 1, 1),
							(35, 'RoomType Manager', 'roomtype.php', '', 33, 'Y', 2, 1),
							(36, 'Price Plan by Date Range', 'priceplan.php', '', 63, 'Y', 4, 1),
							(37, 'BOOKING MANAGER', '#', '', 0, 'Y', 4, 1),
							(38, 'Booking by Administrator', 'admin.rmsearch.php', '', 37, 'Y', 1, 1),
							(39, 'View Active Booking', 'view_bookings.php', '', 37, 'Y', 2, 1),
							(41, 'PHOTO GALLERY', '#', '', 0, 'Y', 6, 1),
							(43, 'Payment Gateway', 'payment_gateway.php', '', 6, 'Y', 4, 1),
							(44, 'Email Contents', '#', '', 2, 'Y', 5, 1),
							(45, 'Change Password', 'change_pass.php', '', 6, 'Y', 6, 1),
							(51, 'Main Photo Gallery', 'admin_photo_gallery.php', '', 41, 'Y', 1, 1),
							(52, 'Home Slider Gallery', 'admin_home_slider_gallery.php', '', 41, 'Y', 2, 1),
							(53, 'Page Content Manager', 'content.list.php', '', 2, 'Y', 3, 1),
							(54, 'Terms & Conditions', 'content_editor.php?id=5', '', 2, 'Y', 12, 1),
							(55, 'Confirmation Email', 'email_content_editor.php?id=1', '', 44, 'Y', 1, 1),
							(57, 'Cancellation Email', 'email_content_editor.php?id=2', '', 44, 'Y', 3, 1),
							(58, 'View Booking History', 'view_archive.php', '', 37, 'Y', 6, 1),
							(59, 'Capacity Manager', 'admin_capacity.php', '', 33, 'Y', 3, 1),
							(60, 'Availability & Block Room', 'calendar_availabilty.php', '', 37, 'Y', 8, 1),
							(61, 'Monthly Discount & Deposit', 'discount_deposit.php', '', 63, 'Y', 6, 1),
							(62, 'Hotel Extras ', 'hotel_extras.php', '', 63, 'Y', 5, 1),
							(63, 'PRICE MANAGER', '#', '', 0, 'Y', 3, 1),
							(64, 'Discount Coupon', 'discount_coupon.php', '', 63, 'Y', 8, 1),
							(65, 'Language', 'admin_language.php', '', 6, 'Y', 5, 1),
							(66, 'Hotel Details', 'admin_hotel_details.php', '', 33, 'Y', 0, 1);");
		
		mysql_query("drop table if exists `bsi_bookings`");
		mysql_query("CREATE TABLE IF NOT EXISTS `bsi_bookings` (
							  `booking_id` int(10) unsigned NOT NULL,
							  `booking_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
							  `start_date` date NOT NULL DEFAULT '0000-00-00',
							  `end_date` date NOT NULL DEFAULT '0000-00-00',
							  `client_id` int(10) unsigned DEFAULT NULL,
							  `child_count` int(2) NOT NULL DEFAULT '0',
							  `extra_guest_count` int(2) NOT NULL DEFAULT '0',
							  `discount_coupon` varchar(50) DEFAULT NULL,
							  `total_cost` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
							  `payment_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
							  `payment_type` varchar(255) NOT NULL,
							  `payment_success` tinyint(1) NOT NULL DEFAULT '0',
							  `payment_txnid` varchar(100) DEFAULT NULL,
							  `paypal_email` varchar(500) DEFAULT NULL,
							  `special_id` int(10) unsigned NOT NULL DEFAULT '0',
							  `special_requests` text,
							  `is_block` tinyint(4) NOT NULL DEFAULT '0',
							  `is_deleted` tinyint(4) NOT NULL DEFAULT '0',
							  PRIMARY KEY (`booking_id`),
							  KEY `start_date` (`start_date`),
							  KEY `end_date` (`end_date`),
							  KEY `booking_time` (`discount_coupon`)
							) ENGINE=InnoDB DEFAULT CHARSET=latin1;");
		
		mysql_query("drop table if exists `bsi_capacity`");
		mysql_query("CREATE TABLE IF NOT EXISTS `bsi_capacity` (
							  `id` int(11) NOT NULL AUTO_INCREMENT,
							  `title` varchar(255) NOT NULL,
							  `capacity` int(11) NOT NULL,
							  PRIMARY KEY (`id`)
							) ENGINE=InnoDB  DEFAULT CHARSET=latin1;");
		mysql_query("INSERT INTO `bsi_capacity` (`id`, `title`, `capacity`) VALUES
							(1, 'Single', 1),
							(2, 'Double', 2),
							(3, 'Twin', 2),
							(4, 'Triple', 3),
							(5, 'Family', 4);");
		
		mysql_query("drop table if exists `bsi_clients`");
		mysql_query("CREATE TABLE IF NOT EXISTS `bsi_clients` (
							  `client_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
							  `first_name` varchar(64) DEFAULT NULL,
							  `surname` varchar(64) DEFAULT NULL,
							  `title` varchar(16) DEFAULT NULL,
							  `street_addr` text,
							  `city` varchar(64) DEFAULT NULL,
							  `province` varchar(128) DEFAULT NULL,
							  `zip` varchar(64) DEFAULT NULL,
							  `country` varchar(64) DEFAULT NULL,
							  `phone` varchar(64) DEFAULT NULL,
							  `fax` varchar(64) DEFAULT NULL,
							  `email` varchar(128) DEFAULT NULL,
							  `additional_comments` text,
							  `ip` varchar(32) DEFAULT NULL,
							  `existing_client` tinyint(1) NOT NULL DEFAULT '0',
							  PRIMARY KEY (`client_id`),
							  KEY `email` (`email`)
							) ENGINE=InnoDB  DEFAULT CHARSET=latin1;");					
		
		mysql_query("drop table if exists `bsi_configure`");
		mysql_query("CREATE TABLE IF NOT EXISTS `bsi_configure` (
							  `conf_id` int(11) NOT NULL AUTO_INCREMENT,
							  `conf_key` varchar(100) NOT NULL,
							  `conf_value` varchar(500) DEFAULT NULL,
							  PRIMARY KEY (`conf_id`)
							) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='bsi hotel configurations';");	
		mysql_query("INSERT INTO `bsi_configure` (`conf_id`, `conf_key`, `conf_value`) VALUES
							(1, 'conf_hotel_name', 'BSI Demo Hotel'),
							(2, 'conf_hotel_streetaddr', '99 xxxxx Road'),
							(3, 'conf_hotel_city', 'Your City'),
							(4, 'conf_hotel_state', 'Your State'),
							(5, 'conf_hotel_country', 'Your Country'),
							(6, 'conf_hotel_zipcode', '14301'),
							(7, 'conf_hotel_phone', '9999999999'),
							(8, 'conf_hotel_fax', ''),
							(9, 'conf_hotel_email', 'sales@bestsoftinc.com'),
							(10, 'conf_hotel_sitetitle', 'BSI Hotel Booking System Demo'),
							(11, 'conf_hotel_sitedesc', 'BSI Hotel Booking System {Demo}'),
							(12, 'conf_hotel_sitekeywords', 'hotel internet reservation softwareinternet reservation softwarehotel internet reservation softwarereservation softwarehotel reservation softwareonline reservation softwarereservation software systemhotel online reservation softwarehotel reservation software systemonline reservation softwarehotel softwarehotel management software'),
							(13, 'conf_currency_symbol', '$'),
							(14, 'conf_currency_code', 'USD'),
							(15, 'conf_smtp_mail', 'false'),
							(16, 'conf_smtp_host', 'smtp.gmail.com'),
							(17, 'conf_smtp_port', '587'),
							(18, 'conf_smtp_username', 'bestsoftinc@gmail.com'),
							(19, 'conf_smtp_password', 'ssss'),
							(20, 'conf_tax_amount', '12.5'),
							(21, 'conf_dateformat', 'dd/mm/yyyy'),
							(22, 'conf_booking_exptime', '500'),
							(23, 'conf_license_key', 'dfd924c73621e8a5d527ea51caabfc91'),
							(24, 'conf_enabled_discount', '1'),
							(25, 'conf_enabled_deposit', '1'),
							(26, 'conf_hotel_timezone', 'Asia/Calcutta'),
							(27, 'conf_booking_turn_off', '0'),
							(28, 'conf_min_night_booking', '2');");			
		
		mysql_query("drop table if exists `bsi_contents`");
		mysql_query("CREATE TABLE IF NOT EXISTS `bsi_contents` (
							  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
							  `cont_title` varchar(200) NOT NULL DEFAULT '',
							  `contents_en` longtext,
							  `contents_es` longtext,
							  `contents_de` longtext,
							  `contents_fr` longtext,
							  `status` enum('Y','N') DEFAULT 'Y',
							  PRIMARY KEY (`id`),
							  UNIQUE KEY `cont_title` (`cont_title`)
							) ENGINE=InnoDB  DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT AUTO_INCREMENT=6 ;");
	   mysql_query("INSERT INTO `bsi_contents` (`id`, `cont_title`, `contents_en`, `contents_es`, `contents_de`, `contents_fr`, `status`) VALUES (5, 'Terms & Conditions', '&lt;p&gt;\r\n	&lt;strong&gt;test1&lt;/strong&gt;&lt;/p&gt;\r\n', '&lt;p&gt;\r\n	hffff&lt;/p&gt;\r\n', '&lt;p&gt;\r\n	fhjghjghjg&lt;/p&gt;\r\n', '&lt;p&gt;\r\n	e333&lt;/p&gt;\r\n', 'Y');");
		
		mysql_query("drop table if exists `bsi_deposit_discount`");
		mysql_query("CREATE TABLE IF NOT EXISTS `bsi_deposit_discount` (
							  `month_num` int(11) NOT NULL AUTO_INCREMENT,
							  `month` varchar(255) NOT NULL,
							  `discount_percent` decimal(10,2) NOT NULL,
							  `deposit_percent` decimal(10,2) NOT NULL,
							  PRIMARY KEY (`month_num`)
							) ENGINE=InnoDB  DEFAULT CHARSET=latin1;");
		
		mysql_query("INSERT INTO `bsi_deposit_discount` (`month_num`, `month`, `discount_percent`, `deposit_percent`) VALUES
							(1, 'January', '0.00', '0.00'),
							(2, 'February', '0.00', '0.00'),
							(3, 'March', '0.00', '0.00'),
							(4, 'April', '0.00', '0.00'),
							(5, 'May', '0.00', '0.00'),
							(6, 'June', '0.00', '0.00'),
							(7, 'July', '0.00', '0.00'),
							(8, 'August', '0.00', '0.00'),
							(9, 'September', '0.00', '0.00'),
							(10, 'October', '0.00', '0.00'),
							(11, 'November', '0.00', '0.00'),
							(12, 'December', '0.00', '0.00');");
							
		mysql_query("drop table if exists `bsi_email_contents`");
		mysql_query("CREATE TABLE IF NOT EXISTS `bsi_email_contents` (
							  `id` int(11) NOT NULL AUTO_INCREMENT,
							  `email_name` varchar(500) NOT NULL,
							  `email_subject` varchar(500) NOT NULL,
							  `email_text` longtext NOT NULL,
							  PRIMARY KEY (`id`)
							) ENGINE=InnoDB  DEFAULT CHARSET=latin1;");
		mysql_query("INSERT INTO `bsi_email_contents` (`id`, `email_name`, `email_subject`, `email_text`) VALUES
							(1, 'Confirmation Email', 'Confirmation Email subject', '<p><strong>Text can be chnage in admin panel</strong></p>\r\n'),
							(2, 'Cancellation Email ', 'Cancellation Email subject', '<p><strong>Text can be chnage in admin panel</strong></p>\r\n');");
							
		mysql_query("drop table if exists `bsi_extras`");
		mysql_query("CREATE TABLE IF NOT EXISTS `bsi_extras` (
							  `extras_id` int(11) NOT NULL AUTO_INCREMENT,
							  `description` varchar(500) NOT NULL,
							  `fees` decimal(10,2) NOT NULL,
							  `enabled` tinyint(1) NOT NULL DEFAULT '1',
							  PRIMARY KEY (`extras_id`)
							) ENGINE=InnoDB  DEFAULT CHARSET=latin1;");
		
		mysql_query("drop table if exists `bsi_gallery`");
		mysql_query("CREATE TABLE IF NOT EXISTS `bsi_gallery` (
							  `id` int(11) NOT NULL AUTO_INCREMENT,
							  `img_path` varchar(500) NOT NULL,
							  `description` varchar(500) DEFAULT NULL,
							  `gallery_type` varchar(255) NOT NULL,
							  PRIMARY KEY (`id`)
							) ENGINE=InnoDB  DEFAULT CHARSET=latin1;");
		mysql_query("INSERT INTO `bsi_gallery` (`id`, `img_path`, `description`, `gallery_type`) VALUES
							(1, '1292792899_1283167454_home_photo_01.jpg', 'Your text here...', '2'),
							(2, '1292792899_1283193501_home_photo_03.jpg', 'Your text here...', '2'),
							(3, '1292793105_1288863944_data-mining.jpg', 'Your text here...', '2'),
							(4, '1292793105_1288863963_BEACH-smart-couple-1.jpg', 'Your text here...', '2'),
							(5, '1292793141_1288864703_599.jpg', '', '2'),
							(6, '1292793426_1283154255_gallery_01_lg.jpg', NULL, '1'),
							(7, '1292793426_1283154255_gallery_02_lg.jpg', NULL, '1'),
							(8, '1292793426_1283154255_gallery_03_lg.jpg', NULL, '1'),
							(9, '1292793426_1283156358_1224973846.jpg', NULL, '1'),
							(10, '1292793426_1283156358_1224973866.jpg', NULL, '1'),
							(11, '1292793524_1283156358_gallery_05_lg.jpg', NULL, '1'),
							(12, '1292793524_1283156358_gallery_06_lg.jpg', NULL, '1'),
							(13, '1292793524_1283156390_1224973898.jpg', NULL, '1'),
							(14, '1292793524_1283156391_1224973901.jpg', NULL, '1'),
							(15, '1292793524_1283156391_1224973924.jpg', NULL, '1'),
							(16, '1292793591_1283156391_1224973931.jpg', NULL, '1'),
							(17, '1292793591_1283156391_1224973935.jpg', NULL, '1'),
							(18, '1292793591_1283156407_1224973953.jpg', NULL, '1'),
							(19, '1292793591_1283156407_1224973987.jpg', NULL, '1'),
							(20, '1292793592_1283156407_1224973999.jpg', NULL, '1'),
							(21, '1292793680_1283156407_1224974043.jpg', NULL, '1'),
							(22, '1292793680_1283156408_1224974155.jpg', NULL, '1'),
							(23, '1292793680_1283156445_1225249683.jpg', NULL, '1'),
							(24, '1292793680_1283156445_1231954613.jpg', NULL, '1'),
							(25, '1292793717_1283156445_1233038322.jpg', NULL, '1');");

		
		mysql_query("drop table if exists `bsi_invoice`");
		mysql_query("CREATE TABLE IF NOT EXISTS `bsi_invoice` (
							  `booking_id` int(10) NOT NULL,
							  `client_name` varchar(500) NOT NULL,
							  `client_email` varchar(500) NOT NULL,
							  `invoice` longtext NOT NULL,
							  PRIMARY KEY (`booking_id`)
							) ENGINE=InnoDB DEFAULT CHARSET=latin1;");

		mysql_query("drop table if exists `bsi_language`");
		mysql_query("CREATE TABLE IF NOT EXISTS `bsi_language` (
							  `lang_id` int(11) NOT NULL AUTO_INCREMENT,
							  `language` varchar(255) NOT NULL,
							  `lang_code` varchar(4) NOT NULL,
							  `lang_file_name` varchar(255) NOT NULL,
							  `status` tinyint(1) NOT NULL,
							  `default` tinyint(1) NOT NULL,
							  `lang_order` int(11) NOT NULL,
							  PRIMARY KEY (`lang_id`)
							) ENGINE=InnoDB  DEFAULT CHARSET=latin1;");
		mysql_query("INSERT INTO `bsi_language` (`lang_id`, `language`, `lang_code`, `lang_file_name`, `status`, `default`, `lang_order`) VALUES
							(1, 'English', 'en', 'english.php', 1, 1, 1),
							(2, 'Deutsch', 'de', 'german.php', 1, 0, 2),
							(3, 'Español', 'es', 'espanol.php', 1, 0, 3),
							(4, 'Française', 'fr', 'french.php', 1, 0, 4);");


		mysql_query("drop table if exists `bsi_payment_gateway`");
		mysql_query("CREATE TABLE IF NOT EXISTS `bsi_payment_gateway` (
							  `id` int(11) NOT NULL AUTO_INCREMENT,
							  `gateway_name` varchar(255) NOT NULL,
							  `gateway_code` varchar(50) NOT NULL,
							  `account` varchar(255) DEFAULT NULL,
							  `enabled` tinyint(1) NOT NULL DEFAULT '0',
							  PRIMARY KEY (`id`)
							) ENGINE=InnoDB  DEFAULT CHARSET=latin1;");
		mysql_query("INSERT INTO `bsi_payment_gateway` (`id`, `gateway_name`, `gateway_code`, `account`, `enabled`) VALUES
							(1, 'PayPal', 'pp', 'phpdev_1250978662_biz@aol.com', 1),
							(2, '2Checkout', '2co', '4221613239', 1),
							(4, 'Manual : Pay On Arrival', 'poa', NULL, 1),
							(6, 'Authorize.Net', 'an', '72dP2gVRt6=|=6Pr5s6y759B9xYHx', 1);");		
		
		mysql_query("drop table if exists `bsi_priceplan`");
		mysql_query("CREATE TABLE IF NOT EXISTS `bsi_priceplan` (
						  `plan_id` int(10) NOT NULL AUTO_INCREMENT,
						  `roomtype_id` int(10) DEFAULT NULL,
						  `capacity_id` int(11) NOT NULL,
						  `start_date` date DEFAULT NULL,
						  `end_date` date DEFAULT NULL,
						  `price` decimal(10,2) DEFAULT '0.00',
						  `extrabed` decimal(10,2) DEFAULT '0.00',
						  `default_plan` tinyint(1) NOT NULL DEFAULT '0',
						  PRIMARY KEY (`plan_id`),
						  KEY `priceplan` (`roomtype_id`,`capacity_id`,`start_date`,`end_date`)
						) ENGINE=InnoDB  DEFAULT CHARSET=latin1;");
						
		mysql_query("drop table if exists `bsi_promocode`");
		mysql_query("CREATE TABLE IF NOT EXISTS `bsi_promocode` (
						  `promo_id` int(11) NOT NULL AUTO_INCREMENT,
						  `promo_code` varchar(50) NOT NULL,
						  `discount` decimal(10,2) NOT NULL DEFAULT '0.00',
						  `min_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
						  `percentage` tinyint(1) NOT NULL DEFAULT '1',
						  `promo_category` int(1) NOT NULL COMMENT '1 - All customer, 2 - Existing Customer, 3 - one selected customer',
						  `customer_email` varchar(255) DEFAULT NULL,
						  `exp_date` date DEFAULT NULL,
						  `reuse_promo` tinyint(1) NOT NULL DEFAULT '1',
						  PRIMARY KEY (`promo_id`)
						) ENGINE=InnoDB  DEFAULT CHARSET=latin1;");
		
		
		mysql_query("drop table if exists `bsi_reservation`");
		mysql_query("CREATE TABLE IF NOT EXISTS `bsi_reservation` (
						  `id` int(11) NOT NULL AUTO_INCREMENT,
						  `bookings_id` int(11) NOT NULL,
						  `room_id` int(11) NOT NULL,
						  `room_type_id` int(11) NOT NULL,
						  PRIMARY KEY (`id`)
						) ENGINE=InnoDB  DEFAULT CHARSET=latin1;");


		mysql_query("drop table if exists `bsi_room`");
		mysql_query("CREATE TABLE IF NOT EXISTS `bsi_room` (
						  `room_ID` int(10) NOT NULL AUTO_INCREMENT,
						  `roomtype_id` int(10) DEFAULT NULL,
						  `room_no` varchar(255) DEFAULT NULL,
						  `capacity_id` int(10) DEFAULT NULL,
						  `no_of_child` int(11) NOT NULL DEFAULT '0',
						  `extra_bed` tinyint(1) DEFAULT '0',
						  PRIMARY KEY (`room_ID`),
						  KEY `roomtype_id` (`roomtype_id`)
						) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;");
		
		
		mysql_query("drop table if exists `bsi_roomtype`");
		mysql_query("CREATE TABLE IF NOT EXISTS `bsi_roomtype` (
						  `roomtype_ID` int(10) NOT NULL AUTO_INCREMENT,
						  `type_name` varchar(500) DEFAULT NULL,
						  PRIMARY KEY (`roomtype_ID`)
						) ENGINE=InnoDB  DEFAULT CHARSET=latin1;");
		
		
		mysql_query("drop table if exists `bsi_site_contents`");
		mysql_query("CREATE TABLE IF NOT EXISTS `bsi_site_contents` (
						  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
						  `cont_title_en` varchar(200) NOT NULL DEFAULT '',
						  `cont_title_es` varchar(255) DEFAULT NULL,
						  `cont_title_de` varchar(255) DEFAULT NULL,
						  `cont_title_fr` varchar(255) DEFAULT NULL,
						  `contents_en` mediumtext,
						  `contents_es` mediumtext,
						  `contents_de` mediumtext,
						  `contents_fr` mediumtext,
						  `status` enum('Y','N') DEFAULT 'Y',
						  `file` text,
						  `url` varchar(100) DEFAULT '',
						  `image` varchar(50) DEFAULT '',
						  `menu` int(1) DEFAULT '0',
						  `parent_id` int(10) DEFAULT '0',
						  `ord` int(11) DEFAULT '0',
						  `page_title` varchar(200) DEFAULT '',
						  `page_keywords` text,
						  `page_desc` text,
						  `header_type` enum('0','1') DEFAULT '0',
						  `footer_type` enum('0','1') DEFAULT '0',
						  PRIMARY KEY (`id`),
						  UNIQUE KEY `cont_title` (`cont_title_en`,`parent_id`)
						) ENGINE=InnoDB  DEFAULT CHARSET=latin1;");		
		mysql_query("INSERT INTO `bsi_site_contents` (`id`, `cont_title_en`, `cont_title_es`, `cont_title_de`, `cont_title_fr`, `contents_en`, `contents_es`, `contents_de`, `contents_fr`, `status`, `file`, `url`, `image`, `menu`, `parent_id`, `ord`, `page_title`, `page_keywords`, `page_desc`, `header_type`, `footer_type`) VALUES
					(1, 'Home', 'Inicio', 'home', 'Accueil', '&lt;p&gt;\r\n	&lt;strong&gt;Your Hotel&lt;/strong&gt; are located approximately between &lt;strong&gt;20 to 35 minutes&lt;/strong&gt; drive from the &lt;strong&gt;Airport&lt;/strong&gt;. Guests will also have easy access to the heart of Singapore where both commercial and leisure entities are located. Thus, guests will definitely enjoy the savings of travelling time.ss&lt;/p&gt;\r\n&lt;p&gt;\r\n	&lt;strong&gt;Your Hotel&lt;/strong&gt; situated in the City District is just a stone&amp;rsquo;s throw from the Bugis MRT Station and in the vicinity are the famous Shopping Malls such as Bugis Junction, Suntec City, Raffles City etc. This area is also famous for its many landmark buildings such as the National Library, City Hall, Singapore Management University and many art galleries and museums.fff&lt;/p&gt;\r\n&lt;p&gt;\r\n	Come and experience the Hospitality and immersed in an entirely new and refreshing ambience which will make your stay a memorable and enjoyable one.&lt;/p&gt;\r\n&lt;p style=&quot;text-align: center;&quot;&gt;\r\n	&lt;strong&gt;&amp;nbsp;[Text can be change in admin panel.] &lt;/strong&gt;&lt;/p&gt;\r\n', '&lt;p style=&quot;text-align: justify;&quot;&gt;\r\n	&lt;span id=&quot;result_box&quot; lang=&quot;es&quot;&gt;&lt;span style=&quot;background-color: rgb(230, 236, 249); color: rgb(0, 0, 0);&quot; title=&quot;&quot;&gt;Su hotel se encuentran la unidad de aproximadamente entre 20 a 35 minutos del aeropuerto. &lt;/span&gt;&lt;span title=&quot;&quot;&gt;Los hu&amp;eacute;spedes tambi&amp;eacute;n tienen acceso f&amp;aacute;cil al coraz&amp;oacute;n de Singapur, donde ambas entidades comerciales y de ocio se encuentran. &lt;/span&gt;&lt;span title=&quot;&quot;&gt;Por lo tanto, los hu&amp;eacute;spedes disfrutar&amp;aacute;n sin duda el ahorro de tiempo de viaje.&lt;/span&gt;&lt;br /&gt;\r\n	&lt;br /&gt;\r\n	&lt;span style=&quot;&quot; title=&quot;&quot;&gt;Su Hotel situado en el distrito de la ciudad est&amp;aacute; a s&amp;oacute;lo un tiro de piedra de la estaci&amp;oacute;n de Bugis MRT y en las proximidades se encuentran los famosos centros comerciales como Bugis, Suntec City, la ciudad de Raffles, etc Esta zona es tambi&amp;eacute;n famosa por sus edificios emblem&amp;aacute;ticos, como muchos &lt;/span&gt;&lt;span style=&quot;&quot; title=&quot;&quot;&gt;la Biblioteca Nacional, el Ayuntamiento, Singapore Management University y muchas galer&amp;iacute;as de arte y museos.&lt;/span&gt;&lt;br /&gt;\r\n	&lt;br /&gt;\r\n	&lt;span style=&quot;&quot; title=&quot;&quot;&gt;Venga y disfrute de la hospitalidad y se sumerge en un ambiente totalmente nuevo y refrescante que har&amp;aacute;n de su estancia una experiencia memorable y divertida.&lt;br /&gt;\r\n	&lt;br /&gt;\r\n	&lt;strong&gt;&amp;nbsp;&lt;/strong&gt;&lt;/span&gt;&lt;strong&gt;&lt;span style=&quot;&quot; title=&quot;&quot;&gt;[El texto se puede cambiar en el panel de administraci&amp;oacute;n.]&lt;/span&gt;&lt;/strong&gt;&lt;/span&gt;&lt;/p&gt;\r\n', '&lt;p&gt;\r\n	ggggggggg&lt;/p&gt;\r\n', '&lt;p&gt;\r\n	test test&lt;/p&gt;\r\n', 'Y', NULL, 'index.php', '', 0, 0, 1, '', NULL, NULL, '0', '0'),
					(10, 'About Us', 'Qui&eacute;nes somos', '&Uuml;ber uns', 'Notre H&ocirc;tel', '&lt;p&gt;\r\n	&lt;font color=&quot;#999944&quot; face=&quot;Georgia&quot;&gt;Your Hotel&lt;/font&gt;&lt;font color=&quot;#999944&quot; size=&quot;5&quot;&gt; &lt;/font&gt;&lt;font color=&quot;#330000&quot; face=&quot;Verdana, Arial, Helvetica, sans-serif&quot; size=&quot;2&quot;&gt;is in kuala Lumpur leading budget hotel, catering to the needs of the corporate and visiting guests from India and around the world. The hotel is located in the down-town of the city (city center) and is walking distance from major Commercial / Business, Entertainment and Shopping centers. Some of the cities best restaurants are located right next to us. The hotel provides a comfortable accommodation facility with its well furnished 45 rooms that caters to every individual&amp;#39;s budget. As the hotel is owned and managed by family members themselves, who are in the hotel business for more than years, &lt;/font&gt;&lt;font color=&quot;#330000&quot; face=&quot;Verdana, Arial, Helvetica, sans-serif&quot; size=&quot;2&quot;&gt;Guests are sure to receive the best in guest service with personal attention to the individual needs of each guest. The hotel speaks loud and clear about it&amp;#39;s motto, which is Neatness and Cleanliness!&lt;/font&gt;&lt;/p&gt;\r\n&lt;p style=&quot;text-align: center;&quot;&gt;\r\n	&lt;strong&gt;&amp;nbsp;[Text can be change in admin panel.] &lt;/strong&gt;&lt;/p&gt;\r\n', '&lt;p&gt;\r\n	test&lt;/p&gt;\r\n', '&lt;p&gt;\r\n	test&lt;/p&gt;\r\n', '&lt;p&gt;\r\n	test&lt;/p&gt;\r\n', 'Y', NULL, '', '', 0, 0, 2, '', NULL, NULL, '0', '0'),
					(11, 'Reservation', 'Reserva', 'Reservierung', 'R&eacute;servation', '', '', '', '', 'Y', NULL, '', '', 0, 0, 3, '', NULL, NULL, '0', '0'),
					(12, 'Offers', 'Ofertas', 'Angebote', 'Offres', '&lt;p&gt;\r\n	Take advantage of these hot offers on HP products. Choose from offers tailored for home, business, government, health, and education customers, and check back often, as we&amp;#39;ll be making updates frequently.&lt;/p&gt;\r\n&lt;p style=&quot;text-align: center;&quot;&gt;\r\n	&lt;strong&gt;&amp;nbsp;[Text can be change in admin panel.] &lt;/strong&gt;&lt;/p&gt;\r\n', '&lt;p&gt;\r\n	hhhhhh&lt;/p&gt;\r\n', '&lt;p&gt;\r\n	hhhhhh&lt;/p&gt;\r\n', '&lt;p&gt;\r\n	hhhhhhhhh&lt;/p&gt;\r\n', 'Y', NULL, '', '', 0, 0, 4, '', NULL, NULL, '0', '0'),
					(13, 'Services', 'Servicios', 'Anlage', 'Services', '&lt;p align=&quot;left&quot;&gt;\r\n	&lt;b&gt;Your Hotel Name &lt;/b&gt; is located near the residential and business areas at Lavender with many coffee shops and food courts around and guests will never be hungry for food and is easily accessible by public transport and the nearest Lavender MRT Station is just within 2 minutes walking distance from the hotel and our guests will definitely enjoy the saving on travelling time.&lt;/p&gt;\r\n&lt;p align=&quot;left&quot;&gt;\r\n	The hotel being strategically located, is well connected by MRT and buses to the city, airport, the famous shopping street Orchard Road, Bugis Village, Central Business District and the must see The Esplanade Theatres by the Bay and Merlion Park.&lt;/p&gt;\r\n&lt;p align=&quot;left&quot;&gt;\r\n	&lt;b&gt;Room Facilities&lt;/b&gt;&lt;/p&gt;\r\n&lt;ul&gt;\r\n	&lt;li&gt;\r\n		&lt;strong&gt;74&lt;/strong&gt; guest rooms&lt;/li&gt;\r\n	&lt;li&gt;\r\n		&lt;strong&gt;In-room Wi-Fi available&lt;/strong&gt;&lt;span class=&quot;style7&quot;&gt;*&lt;/span&gt;&lt;/li&gt;\r\n	&lt;li&gt;\r\n		&lt;strong&gt;LCD&lt;/strong&gt; colour television&lt;/li&gt;\r\n	&lt;li&gt;\r\n		&lt;strong&gt;DVD&lt;/strong&gt; Player&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Individually controlled air-conditioning&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Hair dryer&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Coded key card entry&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Ensuite bathroom with heater&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Phone with IDD facility (subject to call charges)&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Complimentary drinks&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Tea/Coffee making facility&lt;/li&gt;\r\n	&lt;li&gt;\r\n		&lt;strong&gt;Mini Fridge&lt;/strong&gt;&lt;/li&gt;\r\n&lt;/ul&gt;\r\n&lt;p&gt;\r\n	&lt;b&gt;Hotel Facilities &amp;amp; Services&lt;/b&gt;&lt;/p&gt;\r\n&lt;ul&gt;\r\n	&lt;li&gt;\r\n		&lt;strong&gt;Cafe &lt;/strong&gt;(room service available)&lt;span class=&quot;style7&quot;&gt;*&lt;/span&gt;&lt;/li&gt;\r\n	&lt;li&gt;\r\n		&lt;strong&gt;Complimentary breakfast (ABF set)&lt;/strong&gt;&lt;/li&gt;\r\n	&lt;li&gt;\r\n		&lt;strong&gt;Rooftop swimming pool&lt;/strong&gt;&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Broadband internet station&lt;span class=&quot;style7&quot;&gt;*&lt;/span&gt;&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Fax and laundry services&lt;span class=&quot;style7&quot;&gt;*&lt;/span&gt;&lt;/li&gt;\r\n	&lt;li&gt;\r\n		24-hr CCTV cameras to public areas&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Iron &amp;amp; ironing board available upon request&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Complimentary car park&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Airport transfer arrangement &lt;span class=&quot;style7&quot;&gt;*&lt;/span&gt;&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Guided sightseeing tours &lt;span class=&quot;style7&quot;&gt;**&lt;/span&gt;&lt;/li&gt;\r\n&lt;/ul&gt;\r\n&lt;p&gt;\r\n	&lt;span class=&quot;style7&quot;&gt;*&lt;/span&gt; Available upon request and the usual fee applies.&lt;br /&gt;\r\n	&lt;span class=&quot;style7&quot;&gt;**&lt;/span&gt; Available upon request. Services provided by the local tour agencies.&lt;/p&gt;\r\n&lt;p style=&quot;text-align: center;&quot;&gt;\r\n	&lt;strong&gt;&amp;nbsp;[Text can be change in admin panel.] &lt;/strong&gt;&lt;/p&gt;\r\n', '&lt;p&gt;\r\n	fgdfgdfg&lt;/p&gt;\r\n', '&lt;p&gt;\r\n	dfgdfgdfg&lt;/p&gt;\r\n', '&lt;p&gt;\r\n	ffff&lt;/p&gt;\r\n', 'Y', NULL, '', '', 0, 0, 5, '', NULL, NULL, '0', '0'),
					(14, 'Photo Gallery', 'Galer&iacute;a', 'Galerie', 'Galerie', '', '', '', '', 'Y', NULL, 'gallery.php', '', 0, 0, 6, '', NULL, NULL, '0', '0'),
					(17, 'Booking Rooms', 'Reservas Habitaciones', 'Buchung Zimmer', 'R&eacute;servation des chambres', '', '', '', '', 'Y', NULL, 'index.php', '', 0, 11, 1, '', NULL, NULL, '0', '0'),
					(19, 'Booking Status', 'Estado de la reservaci&oacute;n', 'Buchung Status', 'Statut de la r&eacute;servation', '', '', '', '', 'Y', NULL, 'index.php', '', 0, 11, 3, '', NULL, NULL, '0', '0'),
					(20, 'Contacts', 'Contactos', 'Kontakte', 'Contacts', '', '', '', '', 'Y', NULL, 'contact.php', '', 0, 0, 7, '', NULL, NULL, '0', '0'),
					(22, 'Room Tariff', 'Sala de arancel', 'Raumtarif', 'Tarif chambre', '', '', '', '', 'Y', NULL, 'rooms-tariff.php', '', 0, 11, 2, '', NULL, NULL, '0', '0');");	
	}	
}

?>
