<?php
/**
 * @package BSI
 * @author BestSoft Inc see README.php
 * @copyright BestSoft Inc.
 * See COPYRIGHT.php for copyright notices and details.
 */

$bsiCore = new bsiHotelCore ();

class bsiHotelCore
{
	public $config = array();
	public $userDateFormat = "";
	
	function bsiHotelCore()
	{
		$this->getBSIConfig ();
		$this->getUserDateFormat ();
		date_default_timezone_set ($this->config['conf_hotel_timezone']);
	}
	
	private function getBSIConfig()
	{
		$sql = mysql_query ("SELECT conf_id, IFNULL(conf_key, false) AS conf_key, IFNULL(conf_value,false) AS conf_value FROM bsi_configure");
		while ($currentRow = mysql_fetch_assoc ($sql))
		{
			if ($currentRow["conf_key"])
			{
				if ($currentRow["conf_value"])
				{
					$this->config[trim ($currentRow["conf_key"])] = trim ($currentRow["conf_value"]);
				}
				else
				{
					$this->config[trim ($currentRow["conf_key"])] = false;
				}
			}
		}
		mysql_free_result ($sql);
	}
	
	public function getMySqlDate($date)
	{
		if ($date == "")
			return "";
		$dateformatter = split ("[/.-]", $this->config['conf_dateformat']);
		$date_part = split ("[/.-]", $date);
		$date_array = array();
		for($i = 0; $i < 3; $i ++)
		{
			$date_array[$dateformatter[$i]] = $date_part[$i];
		}
		return $date_array['yyyy'] . "-" . $date_array['mm'] . "-" . $date_array['dd'];
	}
	
	public function clearExpiredBookings()
	{
		$sql = mysql_query ("SELECT booking_id FROM bsi_bookings WHERE payment_success = false AND ((NOW() - booking_time) > " . intval ($this->config['conf_booking_exptime']) . " )");
		while ($currentRow = mysql_fetch_assoc ($sql))
		{
			mysql_query ("DELETE FROM bsi_invoice WHERE booking_id = '" . $currentRow["booking_id"] . "'");
			mysql_query ("DELETE FROM bsi_reservation WHERE bookings_id = '" . $currentRow["booking_id"] . "'");
			mysql_query ("DELETE FROM bsi_bookings WHERE booking_id = '" . $currentRow["booking_id"] . "'");
		}
		mysql_free_result ($sql);
	}
	
	public function loadPaymentGateways()
	{
		$paymentGateways = array();
		$sql = mysql_query ("SELECT * FROM bsi_payment_gateway where enabled=true");
		while ($currentRow = mysql_fetch_assoc ($sql))
		{
			$paymentGateways[$currentRow["gateway_code"]] = array('name'=>$currentRow["gateway_name"], 'account'=>$currentRow["account"]);
		}
		mysql_free_result ($sql);
		return $paymentGateways;
	}
	
	private function getUserDateFormat()
	{
		$dtformatter = array('dd'=>'%d', 'mm'=>'%m', 'yyyy'=>'%Y', 'yy'=>'%y');
		$dtformat = split ("[/.-]", $this->config['conf_dateformat']);
		$dtseparator = ($dtformat[0] === 'yyyy') ? substr ($this->config['conf_dateformat'], 4, 1) : substr ($this->config['conf_dateformat'], 2, 1);
		$this->userDateFormat = $dtformatter[$dtformat[0]] . $dtseparator . $dtformatter[$dtformat[1]] . $dtseparator . $dtformatter[$dtformat[2]];
	}
	
	public function ClearInput($dirty)
	{
		$dirty = preg_replace (sql_regcase ("/(select|insert|delete|where|drop|union|like|show|\'|'\| |=|-|;|,|\|'||#|\*|–|\\\\)/"), "", $dirty);
		$dirty = trim ($dirty);
		$dirty = strip_tags ($dirty);
		$dirty = (get_magic_quotes_gpc ()) ? stripslashes ($dirty) : mysql_real_escape_string ($dirty);
		$dirty = htmlentities ($dirty);
		return $dirty;
	}
	public function getweburl()
	{
		$host_info = pathinfo ($_SERVER["PHP_SELF"]);
		if ($host_info['dirname'] == chr (92))
			$url = "http://" . $_SERVER['SERVER_NAME'] . "";
		else
			$url = "http://" . $_SERVER['SERVER_NAME'] . $host_info['dirname'] . "";
		
		return $url;
	}

}
?>