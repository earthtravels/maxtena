/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50154
Source Host           : localhost:3306
Source Database       : rabaccroatia

Target Server Type    : MYSQL
Target Server Version : 50154
File Encoding         : 65001

Date: 2011-03-24 23:46:41
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `bsi_admin`
-- ----------------------------
DROP TABLE IF EXISTS `bsi_admin`;
CREATE TABLE `bsi_admin` (
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of bsi_admin
-- ----------------------------
INSERT INTO bsi_admin VALUES ('1', '21232f297a57a5a743894a0e4a801fc3', 'admin', '1', 'Administrator', '', 'admin@aaaa.com', 'CEO', '2010-09-16 06:12:49', '11111111');

-- ----------------------------
-- Table structure for `bsi_adminmenu`
-- ----------------------------
DROP TABLE IF EXISTS `bsi_adminmenu`;
CREATE TABLE `bsi_adminmenu` (
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
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of bsi_adminmenu
-- ----------------------------
INSERT INTO bsi_adminmenu VALUES ('1', 'HOME', 'admin_home.php', '', '0', 'Y', '1', '0');
INSERT INTO bsi_adminmenu VALUES ('2', 'CONTENTS MANGER', '#', '', '0', 'Y', '5', '0');
INSERT INTO bsi_adminmenu VALUES ('6', 'SETTING', '#', '', '0', 'Y', '9', '0');
INSERT INTO bsi_adminmenu VALUES ('31', 'Global Setting', 'global_setting.php', '', '6', 'Y', '1', '1');
INSERT INTO bsi_adminmenu VALUES ('33', 'HOTEL MANAGER', '#', '', '0', 'Y', '2', '1');
INSERT INTO bsi_adminmenu VALUES ('34', 'Room Manager', 'room_list.php', '', '33', 'Y', '1', '1');
INSERT INTO bsi_adminmenu VALUES ('35', 'RoomType Manager', 'roomtype.php', '', '33', 'Y', '2', '1');
INSERT INTO bsi_adminmenu VALUES ('36', 'Price Plan by Date Range', 'priceplan.php', '', '63', 'Y', '4', '1');
INSERT INTO bsi_adminmenu VALUES ('37', 'BOOKING MANAGER', '#', '', '0', 'Y', '4', '1');
INSERT INTO bsi_adminmenu VALUES ('38', 'Booking by Administrator', 'admin.rmsearch.php', '', '37', 'Y', '1', '1');
INSERT INTO bsi_adminmenu VALUES ('39', 'View Active Booking', 'view_bookings.php', '', '37', 'Y', '2', '1');
INSERT INTO bsi_adminmenu VALUES ('41', 'PHOTO GALLERY', '#', '', '0', 'Y', '6', '1');
INSERT INTO bsi_adminmenu VALUES ('43', 'Payment Gateway', 'payment_gateway.php', '', '6', 'Y', '4', '1');
INSERT INTO bsi_adminmenu VALUES ('44', 'Email Contents', '#', '', '2', 'Y', '5', '1');
INSERT INTO bsi_adminmenu VALUES ('45', 'Change Password', 'change_pass.php', '', '6', 'Y', '6', '1');
INSERT INTO bsi_adminmenu VALUES ('51', 'Main Photo Gallery', 'admin_photo_gallery.php', '', '41', 'Y', '1', '1');
INSERT INTO bsi_adminmenu VALUES ('52', 'Home Slider Gallery', 'admin_home_slider_gallery.php', '', '41', 'Y', '2', '1');
INSERT INTO bsi_adminmenu VALUES ('53', 'Page Content Manager', 'content.list.php', '', '2', 'Y', '3', '1');
INSERT INTO bsi_adminmenu VALUES ('54', 'Terms & Conditions', 'content_editor.php?id=5', '', '2', 'Y', '12', '1');
INSERT INTO bsi_adminmenu VALUES ('55', 'Confirmation Email', 'email_content_editor.php?id=1', '', '44', 'Y', '1', '1');
INSERT INTO bsi_adminmenu VALUES ('57', 'Cancellation Email', 'email_content_editor.php?id=2', '', '44', 'Y', '3', '1');
INSERT INTO bsi_adminmenu VALUES ('58', 'View Booking History', 'view_archive.php', '', '37', 'Y', '6', '1');
INSERT INTO bsi_adminmenu VALUES ('59', 'Capacity Manager', 'admin_capacity.php', '', '33', 'Y', '3', '1');
INSERT INTO bsi_adminmenu VALUES ('60', 'Availability & Block Room', 'calendar_availabilty.php', '', '37', 'Y', '8', '1');
INSERT INTO bsi_adminmenu VALUES ('61', 'Monthly Discount & Deposit', 'discount_deposit.php', '', '63', 'Y', '6', '1');
INSERT INTO bsi_adminmenu VALUES ('62', 'Hotel Extras ', 'hotel_extras.php', '', '63', 'Y', '5', '1');
INSERT INTO bsi_adminmenu VALUES ('63', 'PRICE MANAGER', '#', '', '0', 'Y', '3', '1');
INSERT INTO bsi_adminmenu VALUES ('64', 'Discount Coupon', 'discount_coupon.php', '', '63', 'Y', '8', '1');
INSERT INTO bsi_adminmenu VALUES ('65', 'Language', 'admin_language.php', '', '6', 'Y', '5', '1');
INSERT INTO bsi_adminmenu VALUES ('66', 'Hotel Details', 'admin_hotel_details.php', '', '33', 'Y', '0', '1');

-- ----------------------------
-- Table structure for `bsi_bookings`
-- ----------------------------
DROP TABLE IF EXISTS `bsi_bookings`;
CREATE TABLE `bsi_bookings` (
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of bsi_bookings
-- ----------------------------

-- ----------------------------
-- Table structure for `bsi_capacity`
-- ----------------------------
DROP TABLE IF EXISTS `bsi_capacity`;
CREATE TABLE `bsi_capacity` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `capacity` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of bsi_capacity
-- ----------------------------
INSERT INTO bsi_capacity VALUES ('2', 'Double', '2');
INSERT INTO bsi_capacity VALUES ('5', 'Quad', '4');
INSERT INTO bsi_capacity VALUES ('6', 'Five', '5');
INSERT INTO bsi_capacity VALUES ('7', 'Six', '6');

-- ----------------------------
-- Table structure for `bsi_clients`
-- ----------------------------
DROP TABLE IF EXISTS `bsi_clients`;
CREATE TABLE `bsi_clients` (
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of bsi_clients
-- ----------------------------

-- ----------------------------
-- Table structure for `bsi_configure`
-- ----------------------------
DROP TABLE IF EXISTS `bsi_configure`;
CREATE TABLE `bsi_configure` (
  `conf_id` int(11) NOT NULL AUTO_INCREMENT,
  `conf_key` varchar(100) NOT NULL,
  `conf_value` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`conf_id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=latin1 COMMENT='bsi hotel configurations';

-- ----------------------------
-- Records of bsi_configure
-- ----------------------------
INSERT INTO bsi_configure VALUES ('1', 'conf_hotel_name', 'The Rabac');
INSERT INTO bsi_configure VALUES ('2', 'conf_hotel_streetaddr', 'Lo&#353;injska 26');
INSERT INTO bsi_configure VALUES ('3', 'conf_hotel_city', 'Rabac');
INSERT INTO bsi_configure VALUES ('4', 'conf_hotel_state', '');
INSERT INTO bsi_configure VALUES ('5', 'conf_hotel_country', 'Croatia');
INSERT INTO bsi_configure VALUES ('6', 'conf_hotel_zipcode', '');
INSERT INTO bsi_configure VALUES ('7', 'conf_hotel_phone', '++385 52 872 153');
INSERT INTO bsi_configure VALUES ('8', 'conf_hotel_fax', '');
INSERT INTO bsi_configure VALUES ('9', 'conf_hotel_email', 'info@rabaccroatia.com');
INSERT INTO bsi_configure VALUES ('10', 'conf_hotel_sitetitle', 'Hotel Villa Bruna');
INSERT INTO bsi_configure VALUES ('11', 'conf_hotel_sitedesc', 'Hotel Villa Bruna');
INSERT INTO bsi_configure VALUES ('12', 'conf_hotel_sitekeywords', 'hotel internet reservation softwareinternet reservation softwarehotel internet reservation softwarereservation softwarehotel reservation softwareonline reservation softwarereservation software systemhotel online reservation softwarehotel reservation software systemonline reservation softwarehotel softwarehotel management software');
INSERT INTO bsi_configure VALUES ('13', 'conf_currency_symbol', '$');
INSERT INTO bsi_configure VALUES ('14', 'conf_currency_code', 'USD');
INSERT INTO bsi_configure VALUES ('15', 'conf_smtp_mail', 'false');
INSERT INTO bsi_configure VALUES ('16', 'conf_smtp_host', 'smtp.gmail.com');
INSERT INTO bsi_configure VALUES ('17', 'conf_smtp_port', '587');
INSERT INTO bsi_configure VALUES ('18', 'conf_smtp_username', 'name@gmail.com');
INSERT INTO bsi_configure VALUES ('19', 'conf_smtp_password', 'ssss');
INSERT INTO bsi_configure VALUES ('20', 'conf_tax_amount', '12.5');
INSERT INTO bsi_configure VALUES ('21', 'conf_dateformat', 'dd/mm/yyyy');
INSERT INTO bsi_configure VALUES ('22', 'conf_booking_exptime', '500');
INSERT INTO bsi_configure VALUES ('23', 'conf_license_key', 'dfd924c73621e8a5d527ea51caabfc91');
INSERT INTO bsi_configure VALUES ('24', 'conf_enabled_discount', '1');
INSERT INTO bsi_configure VALUES ('25', 'conf_enabled_deposit', '1');
INSERT INTO bsi_configure VALUES ('26', 'conf_hotel_timezone', 'America/Indiana/Indianapolis');
INSERT INTO bsi_configure VALUES ('27', 'conf_booking_turn_off', '0');
INSERT INTO bsi_configure VALUES ('28', 'conf_min_night_booking', '2');

-- ----------------------------
-- Table structure for `bsi_contents`
-- ----------------------------
DROP TABLE IF EXISTS `bsi_contents`;
CREATE TABLE `bsi_contents` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cont_title` varchar(200) NOT NULL DEFAULT '',
  `contents_en` longtext,
  `contents_es` longtext,
  `contents_de` longtext,
  `contents_fr` longtext,
  `status` enum('Y','N') DEFAULT 'Y',
  PRIMARY KEY (`id`),
  UNIQUE KEY `cont_title` (`cont_title`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of bsi_contents
-- ----------------------------
INSERT INTO bsi_contents VALUES ('5', 'Terms & Conditions', '&lt;p&gt;\r\n	&lt;strong&gt;test1&lt;/strong&gt;&lt;/p&gt;\r\n', '&lt;p&gt;\r\n	hffff&lt;/p&gt;\r\n', '&lt;p&gt;\r\n	fhjghjghjg&lt;/p&gt;\r\n', '&lt;p&gt;\r\n	e333&lt;/p&gt;\r\n', 'Y');

-- ----------------------------
-- Table structure for `bsi_deposit_discount`
-- ----------------------------
DROP TABLE IF EXISTS `bsi_deposit_discount`;
CREATE TABLE `bsi_deposit_discount` (
  `month_num` int(11) NOT NULL AUTO_INCREMENT,
  `month` varchar(255) NOT NULL,
  `discount_percent` decimal(10,2) NOT NULL,
  `deposit_percent` decimal(10,2) NOT NULL,
  PRIMARY KEY (`month_num`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of bsi_deposit_discount
-- ----------------------------
INSERT INTO bsi_deposit_discount VALUES ('1', 'January', '0.00', '0.00');
INSERT INTO bsi_deposit_discount VALUES ('2', 'February', '0.00', '0.00');
INSERT INTO bsi_deposit_discount VALUES ('3', 'March', '0.00', '0.00');
INSERT INTO bsi_deposit_discount VALUES ('4', 'April', '0.00', '0.00');
INSERT INTO bsi_deposit_discount VALUES ('5', 'May', '0.00', '0.00');
INSERT INTO bsi_deposit_discount VALUES ('6', 'June', '0.00', '0.00');
INSERT INTO bsi_deposit_discount VALUES ('7', 'July', '0.00', '0.00');
INSERT INTO bsi_deposit_discount VALUES ('8', 'August', '0.00', '0.00');
INSERT INTO bsi_deposit_discount VALUES ('9', 'September', '0.00', '0.00');
INSERT INTO bsi_deposit_discount VALUES ('10', 'October', '0.00', '0.00');
INSERT INTO bsi_deposit_discount VALUES ('11', 'November', '0.00', '0.00');
INSERT INTO bsi_deposit_discount VALUES ('12', 'December', '0.00', '0.00');

-- ----------------------------
-- Table structure for `bsi_email_contents`
-- ----------------------------
DROP TABLE IF EXISTS `bsi_email_contents`;
CREATE TABLE `bsi_email_contents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email_name` varchar(500) NOT NULL,
  `email_subject` varchar(500) NOT NULL,
  `email_text` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of bsi_email_contents
-- ----------------------------
INSERT INTO bsi_email_contents VALUES ('1', 'Confirmation Email', 'Confirmation Email subject', '<p><strong>Text can be chnage in admin panel</strong></p>\r\n');
INSERT INTO bsi_email_contents VALUES ('2', 'Cancellation Email ', 'Cancellation Email subject', '<p><strong>Text can be chnage in admin panel</strong></p>\r\n');

-- ----------------------------
-- Table structure for `bsi_extras`
-- ----------------------------
DROP TABLE IF EXISTS `bsi_extras`;
CREATE TABLE `bsi_extras` (
  `extras_id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(500) NOT NULL,
  `fees` decimal(10,2) NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`extras_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of bsi_extras
-- ----------------------------

-- ----------------------------
-- Table structure for `bsi_gallery`
-- ----------------------------
DROP TABLE IF EXISTS `bsi_gallery`;
CREATE TABLE `bsi_gallery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `img_path` varchar(500) NOT NULL,
  `description` varchar(500) DEFAULT NULL,
  `gallery_type` varchar(255) NOT NULL,
  `link` varchar(1024) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of bsi_gallery
-- ----------------------------
INSERT INTO bsi_gallery VALUES ('1', 'banner1.jpg', 'Your text here...', '2', '#');
INSERT INTO bsi_gallery VALUES ('2', 'banner2.jpg', 'Your text here...', '2', '#');
INSERT INTO bsi_gallery VALUES ('3', 'banner3.jpg', 'Your text here...', '2', '#');
INSERT INTO bsi_gallery VALUES ('4', 'banner4.jpg', 'Your text here...', '2', '#');
INSERT INTO bsi_gallery VALUES ('5', 'banner5.jpg', '', '2', '#');
INSERT INTO bsi_gallery VALUES ('6', '1292793426_1283154255_gallery_01_lg.jpg', null, '1', '');
INSERT INTO bsi_gallery VALUES ('7', '1292793426_1283154255_gallery_02_lg.jpg', null, '1', '');
INSERT INTO bsi_gallery VALUES ('8', '1292793426_1283154255_gallery_03_lg.jpg', null, '1', '');
INSERT INTO bsi_gallery VALUES ('9', '1292793426_1283156358_1224973846.jpg', null, '1', '');
INSERT INTO bsi_gallery VALUES ('10', '1292793426_1283156358_1224973866.jpg', null, '1', '');
INSERT INTO bsi_gallery VALUES ('11', '1292793524_1283156358_gallery_05_lg.jpg', null, '1', '');
INSERT INTO bsi_gallery VALUES ('12', '1292793524_1283156358_gallery_06_lg.jpg', null, '1', '');
INSERT INTO bsi_gallery VALUES ('13', '1292793524_1283156390_1224973898.jpg', null, '1', '');
INSERT INTO bsi_gallery VALUES ('14', '1292793524_1283156391_1224973901.jpg', null, '1', '');
INSERT INTO bsi_gallery VALUES ('15', '1292793524_1283156391_1224973924.jpg', null, '1', '');
INSERT INTO bsi_gallery VALUES ('16', '1292793591_1283156391_1224973931.jpg', null, '1', '');
INSERT INTO bsi_gallery VALUES ('17', '1292793591_1283156391_1224973935.jpg', null, '1', '');
INSERT INTO bsi_gallery VALUES ('18', '1292793591_1283156407_1224973953.jpg', null, '1', '');
INSERT INTO bsi_gallery VALUES ('19', '1292793591_1283156407_1224973987.jpg', null, '1', '');
INSERT INTO bsi_gallery VALUES ('20', '1292793592_1283156407_1224973999.jpg', null, '1', '');
INSERT INTO bsi_gallery VALUES ('21', '1292793680_1283156407_1224974043.jpg', null, '1', '');
INSERT INTO bsi_gallery VALUES ('22', '1292793680_1283156408_1224974155.jpg', null, '1', '');
INSERT INTO bsi_gallery VALUES ('23', '1292793680_1283156445_1225249683.jpg', null, '1', '');
INSERT INTO bsi_gallery VALUES ('24', '1292793680_1283156445_1231954613.jpg', null, '1', '');
INSERT INTO bsi_gallery VALUES ('25', '1292793717_1283156445_1233038322.jpg', null, '1', '');

-- ----------------------------
-- Table structure for `bsi_invoice`
-- ----------------------------
DROP TABLE IF EXISTS `bsi_invoice`;
CREATE TABLE `bsi_invoice` (
  `booking_id` int(10) NOT NULL,
  `client_name` varchar(500) NOT NULL,
  `client_email` varchar(500) NOT NULL,
  `invoice` longtext NOT NULL,
  PRIMARY KEY (`booking_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of bsi_invoice
-- ----------------------------

-- ----------------------------
-- Table structure for `bsi_language`
-- ----------------------------
DROP TABLE IF EXISTS `bsi_language`;
CREATE TABLE `bsi_language` (
  `lang_id` int(11) NOT NULL AUTO_INCREMENT,
  `language` varchar(255) NOT NULL,
  `lang_code` varchar(4) NOT NULL,
  `lang_file_name` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `default` tinyint(1) NOT NULL,
  `lang_order` int(11) NOT NULL,
  PRIMARY KEY (`lang_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of bsi_language
-- ----------------------------
INSERT INTO bsi_language VALUES ('1', 'English', 'en', 'english.php', '1', '1', '1');
INSERT INTO bsi_language VALUES ('2', 'Croatian', 'hr', 'croatian.php', '1', '0', '2');
INSERT INTO bsi_language VALUES ('3', 'Deutch', 'de', 'german.php', '1', '0', '3');
INSERT INTO bsi_language VALUES ('4', 'Italiano', 'it', 'italian.php', '1', '0', '4');

-- ----------------------------
-- Table structure for `bsi_payment_gateway`
-- ----------------------------
DROP TABLE IF EXISTS `bsi_payment_gateway`;
CREATE TABLE `bsi_payment_gateway` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gateway_name` varchar(255) NOT NULL,
  `gateway_code` varchar(50) NOT NULL,
  `account` varchar(255) DEFAULT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of bsi_payment_gateway
-- ----------------------------
INSERT INTO bsi_payment_gateway VALUES ('1', 'PayPal', 'pp', 'phpdev_1250978662_biz@aol.com', '1');
INSERT INTO bsi_payment_gateway VALUES ('2', '2Checkout', '2co', '4221613239', '0');
INSERT INTO bsi_payment_gateway VALUES ('4', 'Manual : Pay On Arrival', 'poa', null, '0');
INSERT INTO bsi_payment_gateway VALUES ('6', 'Authorize.Net', 'an', '72dP2gVRt6=|=6Pr5s6y759B9xYHx', '0');

-- ----------------------------
-- Table structure for `bsi_priceplan`
-- ----------------------------
DROP TABLE IF EXISTS `bsi_priceplan`;
CREATE TABLE `bsi_priceplan` (
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
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of bsi_priceplan
-- ----------------------------
INSERT INTO bsi_priceplan VALUES ('1', '1', '5', null, null, '100.00', '0.00', '1');
INSERT INTO bsi_priceplan VALUES ('3', '2', '5', null, null, '100.00', '0.00', '1');
INSERT INTO bsi_priceplan VALUES ('4', '3', '7', null, null, '200.00', '0.00', '1');
INSERT INTO bsi_priceplan VALUES ('6', '5', '2', null, null, '100.00', '0.00', '1');
INSERT INTO bsi_priceplan VALUES ('7', '4', '2', null, null, '100.00', '0.00', '1');
INSERT INTO bsi_priceplan VALUES ('8', '6', '6', null, null, '250.00', '0.00', '1');
INSERT INTO bsi_priceplan VALUES ('9', '1', '5', '2011-03-12', '2012-01-01', '150.00', '0.00', '0');

-- ----------------------------
-- Table structure for `bsi_promocode`
-- ----------------------------
DROP TABLE IF EXISTS `bsi_promocode`;
CREATE TABLE `bsi_promocode` (
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of bsi_promocode
-- ----------------------------

-- ----------------------------
-- Table structure for `bsi_reservation`
-- ----------------------------
DROP TABLE IF EXISTS `bsi_reservation`;
CREATE TABLE `bsi_reservation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bookings_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `room_type_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of bsi_reservation
-- ----------------------------

-- ----------------------------
-- Table structure for `bsi_room`
-- ----------------------------
DROP TABLE IF EXISTS `bsi_room`;
CREATE TABLE `bsi_room` (
  `room_ID` int(10) NOT NULL AUTO_INCREMENT,
  `roomtype_id` int(10) DEFAULT NULL,
  `room_no` varchar(255) DEFAULT NULL,
  `capacity_id` int(10) DEFAULT NULL,
  `no_of_child` int(11) NOT NULL DEFAULT '0',
  `extra_bed` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`room_ID`),
  KEY `roomtype_id` (`roomtype_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of bsi_room
-- ----------------------------
INSERT INTO bsi_room VALUES ('1', '1', '1', '5', '2', '0');
INSERT INTO bsi_room VALUES ('2', '1', '2', '5', '1', '0');
INSERT INTO bsi_room VALUES ('3', '1', '3', '5', '1', '0');

-- ----------------------------
-- Table structure for `bsi_roomtype`
-- ----------------------------
DROP TABLE IF EXISTS `bsi_roomtype`;
CREATE TABLE `bsi_roomtype` (
  `roomtype_ID` int(10) NOT NULL AUTO_INCREMENT,
  `type_name` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`roomtype_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of bsi_roomtype
-- ----------------------------
INSERT INTO bsi_roomtype VALUES ('1', 'Sea View Apartment');
INSERT INTO bsi_roomtype VALUES ('2', 'Garden View Apartment');
INSERT INTO bsi_roomtype VALUES ('3', 'Master Apartment');
INSERT INTO bsi_roomtype VALUES ('4', 'Patio Sea View Room');
INSERT INTO bsi_roomtype VALUES ('5', 'Sea View Room');
INSERT INTO bsi_roomtype VALUES ('6', 'Grand View Apartment');

-- ----------------------------
-- Table structure for `bsi_site_contents`
-- ----------------------------
DROP TABLE IF EXISTS `bsi_site_contents`;
CREATE TABLE `bsi_site_contents` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title_en` varchar(200) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `title_hr` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `title_de` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `title_it` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `status` enum('Y','N') CHARACTER SET latin1 DEFAULT 'Y',
  `file` text CHARACTER SET latin1,
  `url` varchar(100) CHARACTER SET latin1 DEFAULT '',
  `parent_id` int(10) DEFAULT '0',
  `ord` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `cont_title` (`title_en`,`parent_id`)
) ENGINE=InnoDB AUTO_INCREMENT=72 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of bsi_site_contents
-- ----------------------------
INSERT INTO bsi_site_contents VALUES ('1', 'Home', 'Po&#269;etna', 'Startseite', 'Home', 'Y', null, 'index.php', '0', '1');
INSERT INTO bsi_site_contents VALUES ('2', 'Reservations', 'Rezervacije', 'Reservierung', 'Prenotazioni', 'Y', null, 'reservations.php', '0', '2');
INSERT INTO bsi_site_contents VALUES ('3', 'Accommodations', 'Smje&#353;taj', 'Unterkünfte', 'Alloggi', 'Y', null, '#', '0', '3');
INSERT INTO bsi_site_contents VALUES ('4', 'Location', 'Lokacija', 'Lage', 'Posizione', 'Y', null, '#', '0', '4');
INSERT INTO bsi_site_contents VALUES ('5', 'About', 'O nama', 'Über uns', 'Chi siamo', 'Y', null, 'about.php', '0', '5');
INSERT INTO bsi_site_contents VALUES ('6', 'Photos', 'Slike', 'Fotos', 'Foto', 'Y', null, 'photos.php', '0', '6');
INSERT INTO bsi_site_contents VALUES ('7', 'Contact', 'Kontakt', 'Kontakt', 'Contattaci', 'Y', null, 'contact.php', '0', '7');
INSERT INTO bsi_site_contents VALUES ('20', 'Reserve Now', 'Rezervirajte odmah', 'Reservieren Sie jetzt', 'Prenota ora', 'Y', null, 'reservations.php', '2', '1');
INSERT INTO bsi_site_contents VALUES ('21', 'Group Reservations', 'Grupne rezervacije', 'Gruppenbuchungen', 'Prenotazione gruppi', 'Y', null, 'reserve_group.php', '2', '2');
INSERT INTO bsi_site_contents VALUES ('30', 'Rooms', 'Sobe', 'Zimmer', 'Camere', 'Y', null, 'rooms.php', '3', '1');
INSERT INTO bsi_site_contents VALUES ('31', 'Apartments', 'Apartmani', 'Wohnungen', 'Appartamenti', 'Y', null, 'apartments.php', '3', '2');
INSERT INTO bsi_site_contents VALUES ('40', 'Rabac', 'Rabac', 'Rabac', 'Rabac', 'Y', null, 'rabac_restaurants.php', '4', '1');
INSERT INTO bsi_site_contents VALUES ('41', 'Labin', 'Labin', 'Labin', 'Labin', 'Y', null, 'labin_restaurants.php', '4', '2');
INSERT INTO bsi_site_contents VALUES ('60', 'Videos', 'Video', 'Videos', 'Video', 'Y', null, 'videos.php', '6', '1');
INSERT INTO bsi_site_contents VALUES ('70', 'Map &amp; Directions', 'Karta i direkcije', 'Karte & Wegbeschreibung', 'Come raggiungerci', 'Y', null, 'map.php', '7', '1');
INSERT INTO bsi_site_contents VALUES ('71', 'FAQs', '&#268;esta pitanja', 'Häufig gestellte Fragen', 'Le domande più frequenti', 'Y', null, 'faq.php', '7', '2');

-- ----------------------------
-- Table structure for `news_posts`
-- ----------------------------
DROP TABLE IF EXISTS `news_posts`;
CREATE TABLE `news_posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title_en` varchar(256) CHARACTER SET utf8 NOT NULL,
  `title_hr` varchar(256) CHARACTER SET utf8 DEFAULT NULL,
  `title_de` varchar(256) CHARACTER SET utf8 DEFAULT NULL,
  `title_it` varchar(256) CHARACTER SET utf8 DEFAULT NULL,
  `contents_en` text CHARACTER SET utf8 NOT NULL,
  `contents_hr` text CHARACTER SET utf8,
  `contents_de` text CHARACTER SET utf8,
  `contents_it` text CHARACTER SET utf8,
  `date_posted` date NOT NULL,
  `image_small` varchar(256) CHARACTER SET utf8 NOT NULL,
  `image_medium` varchar(256) CHARACTER SET utf8 NOT NULL,
  `image_large` varchar(256) CHARACTER SET utf8 NOT NULL,
  `category_en` varchar(256) CHARACTER SET utf8 NOT NULL,
  `category_hr` varchar(256) CHARACTER SET utf8 DEFAULT NULL,
  `category_de` varchar(256) CHARACTER SET utf8 DEFAULT NULL,
  `category_it` varchar(256) CHARACTER SET utf8 DEFAULT NULL,
  `poster_name` varchar(256) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of news_posts
-- ----------------------------
INSERT INTO news_posts VALUES ('1', 'What to See and Do in Rabac', null, null, null, '<p>Most visitors that come to Rabac are content to spend their holidays simply lying by the beach and\r\nenjoying the crystal clear, refreshing Adriatic. And we don\'t blame them, since these are our favorite\r\npastimes as well.</p>\r\n\r\n<p>Still, if you prefer a little more action, there are many options available. Snorkeling is another of our\r\nfavorite activities, and if you come unprepared, many shops in Rabac offer affordable equipment,\r\nincluding masks, snorkels, and fins. You can spend hours lazily drifting in and out of the numerous and\r\nsecluded coves as you watch passing schools of fish.</p>\r\n\r\n<p>If you prefer eating them, there are many good fishing spots to make your own catch. Or if you would\r\nrather have someone do it for you, you can enjoy a fun day on one of the Fish Picnic boats. Enjoy a short\r\nboat ride to the nearby island of Cres and a tasty fish lunch.</p>\r\n\r\n<p>Other boat rentals and water sports can also be found along the harbor. Be sure to ask us for\r\nsuggestions.</p>\r\n\r\n<p>For a break from the sea, you can explore the land and the many hiking trails up and along the coast. For\r\nmore exertion, try the hike from Rabac up to Labin. It promises amazing views at the top.</p>\r\n\r\n<p>In the evening, enjoy a lovely stroll along the harbor. The boardwalk stretches for miles. You can dine at\r\none of the many seafood restaurants, do a bit of shopping, or sip a glass of Croatian wine while watching\r\nthe sea. Local music and entertainment is also often at hand.</p>\r\n\r\n<p>The apartments can accommodate up to a maximum of 45 guests - perfect for family reunions, retreats\r\nor other group events.</p>\r\n\r\n<p>Please inquire on our group reservations page about special rates for reserving the building as a whole.</p>', null, null, null, '2011-03-23', 'news1.jpg', 'news1_med.jpg', '143_614.JPG', 'General', null, null, null, 'Mensur');
