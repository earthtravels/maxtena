<?php
//include_once ("SystemConfiguration.class.php");


class RoomPricePlan
{
	public $id = 0;
	public $roomId = 0;
	public $startDate;
	public $endDate;
	public $price = 0;
	public $extraBedPrice = 0;
	public $isDefault = false;
	
	public $errors = array();
	
	public function __construct()
	{
		$this->startDate = new Date();
		$this->endDate = new Date();
	}
	
	public static function fetchFromParameters($params)
	{
		$roomPricePlan = new RoomPricePlan();
		if (isset($params['id']) && is_numeric($params['id']))
		{
			$roomPricePlan->id = intval($params['id']);
		}
		if (isset($params['room_id']))
		{
			$roomPricePlan->roomId = intval($params['room_id']);
		}
		if (isset($params['start_date']))
		{
			$roomPricePlan->startDate = Date::parse($params['start_date']);
		}
		if (isset($params['end_date']))
		{
			$roomPricePlan->endDate = Date::parse($params['end_date']);
		}
		if (isset($params['price']))
		{
			$roomPricePlan->price = floatval($params['price']);
		}
		if (isset($params['extrabed']))
		{
			$roomPricePlan->extraBedPrice = floatval($params['extrabed']);
		}
		if (isset($params['default_plan']))
		{
			$roomPricePlan->isDefault = intval($params['default_plan']) == 1;
		}
		return $roomPricePlan;
	}
	
	public static function fetchAllFromDb()
	{
		$rooms = array();
		$sql = "SELECT * FROM bsi_room_price";
		$query = mysql_query($sql);
		if (! $query)
		{
			die("Database error: " . mysql_errno() . ". Message: " . mysql_error());
		}
		while ($row = mysql_fetch_assoc($query))
		{
			$roomPricePlan = RoomPricePlan::fetchFromParameters($row);
			$rooms[sizeof($rooms)] = $roomPricePlan;
		}
		mysql_free_result($query);
		return $rooms;
	}
	
	public static function fetchAllFromDbForRoom($roomId)
	{
		$rooms = array();
		$sql = "SELECT * FROM bsi_room_price WHERE room_id = " . $roomId;
		$query = mysql_query($sql);
		if (! $query)
		{
			global $logger;
			$logger->LogFatal("Error executing query: $sql");
			$logger->LogFatal("Database error: " . mysql_errno() . ". Message: " . mysql_error());
			die("Database error: " . mysql_errno() . ". Message: " . mysql_error());
		}
		while ($row = mysql_fetch_assoc($query))
		{
			$roomPricePlan = RoomPricePlan::fetchFromParameters($row);
			$rooms[sizeof($rooms)] = $roomPricePlan;
		}
		mysql_free_result($query);
		return $rooms;
	}
	
	public static function fetchFromDbDefaultForRoom($roomId)
	{
		$sql = "SELECT * FROM bsi_room_price WHERE room_id = $roomId AND default_plan = 1";
		$query = mysql_query($sql);
		if (! $query)
		{
			global $logger;
			$logger->LogFatal("Error executing query: $sql");
			$logger->LogFatal("Database error: " . mysql_errno() . ". Message: " . mysql_error());
			die("Database error: " . mysql_errno() . ". Message: " . mysql_error());
		}
		if ($row = mysql_fetch_assoc($query))
		{
			$roomPricePlan = RoomPricePlan::fetchFromParameters($row);
			return $roomPricePlan;
		}
		mysql_free_result($query);
		return null;
	}
	
	public static function fetchAllFromDbForRoomAndDates($roomId, $fromDate, $toDate)
	{
		if (($fromDate != null && !($fromDate instanceof Date)) || ($toDate != null && !($toDate instanceof Date)))
		{
			global $logger;
			$logger->LogFatal("Error executing query: $sql");
			$logger->LogFatal("Database error: " . mysql_errno() . ". Message: " . mysql_error());
			die("Invalid dates passed in.");
		}
		else if (($fromDate == null && $toDate != null) && ($fromDate != null && $toDate == null)) 
		{
			global $logger;
			$logger->LogFatal("Error executing query: $sql");
			$logger->LogFatal("Database error: " . mysql_errno() . ". Message: " . mysql_error());
			die("One date is null the other is not.");
		}
		
		$pricePlans = array();
		$sql = "SELECT * FROM bsi_room_price WHERE room_id = " . $roomId;
		if ($fromDate == null && $toDate == null)
		{
			$sql.= " AND ((start_date = NULL AND end_date = NULL) OR default_plan = 1) ORDER BY start_date"; 
		}
		else
		{		
			$sql .= " AND (start_date BETWEEN CAST('" . $fromDate->formatMySql() . "' AS DATE) AND CAST('" . $toDate->formatMySql() . "' AS DATE) OR end_date BETWEEN CAST('" . $fromDate->formatMySql() . "' AS DATE) AND CAST('" . $toDate->formatMySql() . "' AS DATE) OR (start_date < CAST('" . $fromDate->formatMySql() . "' AS DATE) AND end_date > CAST('" . $toDate->formatMySql() . "' AS DATE)) OR  (start_date > CAST('" . $fromDate->formatMySql() . "' AS DATE) AND end_date < CAST('" . $toDate->formatMySql() . "' AS DATE)) OR default_plan = 1) ORDER BY start_date";
		}
		$query = mysql_query($sql);
		if (! $query)
		{
			global $logger;
			$logger->LogFatal("Error executing query: $sql");
			$logger->LogFatal("Database error: " . mysql_errno() . ". Message: " . mysql_error());
			die("Database error: " . mysql_errno() . ". Message: " . mysql_error());
		}
		while ($row = mysql_fetch_assoc($query))
		{
			$roomPricePlan = RoomPricePlan::fetchFromParameters($row);
			$pricePlans[sizeof($pricePlans)] = $roomPricePlan;
		}
		mysql_free_result($query);
		return $pricePlans;
	}
	
	public static function fetchFromDb($id)
	{
		if (! is_numeric($id))
		{
			return NULL;
		}
		
		$sql = "SELECT * FROM bsi_room_price WHERE id = " . $id;
		$query = mysql_query($sql);
		if (! $query)
		{
			global $logger;
			$logger->LogFatal("Error executing query: $sql");
			$logger->LogFatal("Database error: " . mysql_errno() . ". Message: " . mysql_error());
			die("Database error: " . mysql_errno() . ". Message: " . mysql_error());
		}
		if ($row = mysql_fetch_assoc($query))
		{
			$roomPricePlan = RoomPricePlan::fetchFromParameters($row);
			mysql_free_result($query);
			return $roomPricePlan;
		}
		else
		{
			$this->setError("No room with id " . $id . " could be found.");
			mysql_free_result($query);
			return NULL;
		}
	}
	
	public function isValid()
	{
		$this->errors = array();
		if (strlen(trim($this->roomId)) == 0)
		{
			$this->setError("Room id cannot be empty");
		}
		if ($this->startDate == NULL && ! $this->isDefault)
		{
			$this->setError("Start date name cannot be empty");
		}
		if ($this->endDate == NULL && ! $this->isDefault)
		{
			$this->setError("End date name cannot be empty");
		}
		if (! is_float($this->price))
		{
			$this->setError("Price is invalid");
		}
		if ($this->price <= 0)
		{
			$this->setError("Price must be greater than zero");
		}
		if (! is_float($this->extraBedPrice))
		{
			$this->setError("Extra bed price is invalid");
		}
		if ($this->extraBedPrice < 0)
		{
			$this->setError("Extra bed price must be greater than zero");
		}
		if (sizeof($this->errors) == 0)
		{
			$existingPricePlans = RoomPricePlan::fetchAllFromDbForRoomAndDates($this->roomId, $this->startDate, $this->endDate);
			foreach ($existingPricePlans as $existingPricePlan)
			{
				if (!$existingPricePlan->isDefault && $existingPricePlan->id != $this->id)
				{
					$this->setError("Plan whose dates overlap already exists");
					break;
				}
			}
		}
		return sizeof($this->errors) == 0;
	}
	
	public function save()
	{
		$this->errors = array();
		if (! $this->isValid())
		{
			return false;
		}
		
		else if ($this->id == 0)
		{
			// Run INSERT
			$sql = "INSERT INTO bsi_room_price (room_id, start_date, end_date, price, extrabed, default_plan) VALUES (";
			$sql.= "$this->roomId, ";
			if ($this->startDate != null)
			{ 
				$sql.= "'" . $this->startDate->formatMySql() . "', ";
			}
			else
			{
				$sql.= "NULL, ";
			}
			if ($this->endDate != null)
			{ 
				$sql.= "'" . $this->endDate->formatMySql() . "', ";
			}
			else
			{
				$sql.= "NULL, ";
			}
			$sql.= "$this->price, $this->extraBedPrice, " . ($this->isDefault ? "1" : "0") . ")";			
			$query = mysql_query($sql);
			if (!$query)
			{
				global $logger;
				$logger->LogFatal("Error executing query: $sql");
				$logger->LogFatal("Database error: " . mysql_errno() . ". Message: " . mysql_error());
				die('Error: ' . mysql_error());
			}
			$this->id = mysql_insert_id();			
		}
		else
		{
			// Run UPDATE
			$sql = "UPDATE bsi_room_price ";
			$sql .= "SET room_id = $this->roomId, ";
			if ($this->startDate != null)
			{ 
				$sql .= "start_date = '" . $this->startDate->formatMySql() . "', ";
			}
			else
			{
				$sql .= "start_date = NULL, ";
			}
			if ($this->endDate != null)
			{ 
				$sql .= "end_date = '" . $this->endDate->formatMySql() . "', ";
			}
			else
			{
				$sql .= "end_date = NULL, ";
			}					
			$sql .= "price = $this->price, extrabed = $this->extraBedPrice, default_plan = " . ($this->isDefault ? "1" : "0");
			$sql .= " WHERE id = " . $this->id;
			$query = mysql_query($sql);
			if (! $query)
			{
				global $logger;
				$logger->LogFatal("Error executing query: $sql");
				$logger->LogFatal("Database error: " . mysql_errno() . ". Message: " . mysql_error());
				die('Error: ' . mysql_error());
			}			
		}
		return true;
	}
	
	public static function delete($id)
	{
		$this->errors = array();
		if (is_numeric($id))
		{
			// Run DELETE
			$sql = "DELETE FROM bsi_rooms WHERE id = " . $id;
			if (! mysql_query($sql))
			{
				global $logger;
				$logger->LogFatal("Error executing query: $sql");
				$logger->LogFatal("Database error: " . mysql_errno() . ". Message: " . mysql_error());
				die('Error: ' . mysql_error());
			}
		}
	}
	
	private function setError($errorMessage)
	{
		$this->errors[] = $errorMessage;
	}
}