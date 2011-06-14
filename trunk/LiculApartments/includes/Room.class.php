<?php
class Room
{  	
	public $id = 0;
    public $roomNumber = "";
    public $roomName = "";
    private $roomDescriptions = array();
    public $hasExtraBed = false;
    public $capacity = 0;
    public $isApartment = false;
	
	public $errors = array();	
	public static $staticErrors = array();

    public function getDescription($languageCode)
    {
    	$this->errors = array();
        if (isset($this->roomDescriptions[$languageCode]))
        {
            return $this->roomDescriptions[$languageCode];
        }
        else
        {
            $this->setError("Language code " . $languageCode . " could not be found.");
        }
        return NULL;
    }
    
    /**
     * 
     * Returns an array of RoomPricePlan objects that are applicable to this room
     */
    public function getAllPricePlans()
    {
    	return RoomPricePlan::fetchAllFromDbForRoom($this->id);
    }
    
    public function getPricePlansForDates($fromDate, $toDate)
    {
    	return RoomPricePlan::fetchAllFromDbForRoomAndDates($this->id, $fromDate, $toDate);
    }
    
	public function getDefaultPricePlan()
    {
    	return RoomPricePlan::fetchFromDbDefaultForRoom($this->id);
    }    

    public function getImages()
    {
        //$images = GalleryImage::fetchFromDbWhere(" WHERE id IN (SELECT image_id FROM bsi_room_images WHERE room_id = " . $this->id . ")");
        $images = RoomImage::fetchFromDbForRoom($this->id);        
        return $images;
    }

    public function getBedPrice($fromDate, $toDate)
    {
        $prices =  $this->getRoomAndBedPrice($fromDate, $toDate);
        if ($prices == null)
        {
            return null;
        }
        return $prices[1];
    }

    public function getRoomPrice($fromDate, $toDate)
    {
        $prices =  $this->getRoomAndBedPrice($fromDate, $toDate);
        if ($prices == null)
        {
            return null;
        }
        return $prices[0];
    }


    public function getRoomAndBedPrice($fromDate, $toDate)
    {
        $prices = array();
    	$this->errors = array();
    	if (!($fromDate instanceof Date && $toDate instanceof Date))
		{
			$this->setError("Dates passed in are not valid Date objects.");
			return null;
		}
		
		// Fetch all applicable price plans
		$pricePlans = $this->getPricePlansForDates($fromDate, $toDate);
		if ($pricePlans == NULL)
		{
			$this->setError("Error getting room price plans.");
			return null;
		}
		
		// Find default price plan
		$defaultPricePlan = null;
		foreach ($pricePlans as $pricePlan) 
		{
			if ($pricePlan instanceof RoomPricePlan)
			{
				if ($pricePlan->isDefault)
				{
					$defaultPricePlan = $pricePlan;
					break;
				}
			}			
		}		
		if ($defaultPricePlan == null)
		{
			$this->setError("Room does not have a default price plan.");
			return null;			
		}
		
		// Calculate price
        $totalBedPrice = 0;
		$totalPrice = 0;		
		$currentDatePrice = 0;
        $currentDateBedPrice = 0;
		$currentDate = $fromDate;
		while ($currentDate->compareTo($toDate) == -1)
		{
			$currentDatePrice = $defaultPricePlan->price;
            $currentDateBedPrice = $defaultPricePlan->extraBedPrice;
	    	foreach ($pricePlans as $pricePlan) 
			{
				if ($pricePlan instanceof RoomPricePlan && !$pricePlan->isDefault)
				{
					$isBetween = $currentDate->isBetween($pricePlan->startDate, $pricePlan->endDate);
					if ($isBetween)
					{
						$currentDatePrice = $pricePlan->price;
                        $currentDateBedPrice = $pricePlan->extraBedPrice;
						break;
					}
				}			
			}
			$totalPrice += $currentDatePrice;
            $totalBedPrice += $currentDateBedPrice;
			$nextDay = $currentDate->nextDay();
			$currentDate = $nextDay; 			
		}
        $prices[0] = $totalPrice;
        $prices[1] = $totalBedPrice;
		return $prices;		
    }
	
	public static function fetchFromParameters($params) 
	{   
		$room = new Room();
		if (isset($params['id']) && is_numeric($params['id']))
		{
			$room->id = intval($params['id']);
		}
		if (isset($params['room_number']))
		{
			$room->roomNumber = $params['room_number'];
		}		
		if (isset($params['room_name']))
		{
			$room->roomName = $params['room_name'];
		}		
        foreach ($params as $key => $value)
        {
            if (preg_match('/room_desc_[A-Za-z]{2}/', $key))
            {
                $languageCode = substr($key, -2);  
                $room->roomDescriptions[$languageCode] = $value;
            }
        }
		if (isset($params['extra_bed']))
		{
			$room->hasExtraBed = intval($params['extra_bed']) == 1;
		}		
		if (isset($params['capacity']))
		{
			$room->capacity = intval($params['capacity']);
		}     
        if (isset($params['is_apartment']))
		{
			$room->isApartment = intval($params['is_apartment']) == 1;
		}
		return $room;
	}

	
	public static function fetchAllFromDb() 
	{		
		$rooms = array();
		$sql = "SELECT * FROM bsi_rooms ORDER BY capacity";
		$query = mysql_query($sql);
		if (!$query)
		{
			die("Database error: " . mysql_errno() . ". Message: " . mysql_error());			
		}
		while ($row = mysql_fetch_assoc($query))
		{
			$room = Room::fetchFromParameters($row);
			$rooms[sizeof($rooms)] = $room;
		}
		return $rooms;		
	}
	
	public static function fetchFromDb($id) 
	{
		Room::$staticErrors = array();
		if (!is_numeric($id))
		{
			$this->setError("Id " . $id . " is not numeric.");			
			return NULL;
		}
				
		$sql = "SELECT * FROM bsi_rooms WHERE id = " . $id;
		$query = mysql_query($sql);
		if (!$query)
		{
			global $logger;
			$logger->LogFatal("Error executing query: $sql");
			$logger->LogFatal("Database error: " . mysql_errno() . ". Message: " . mysql_error());
			die("Database error: " . mysql_errno() . ". Message: " . mysql_error());			
		}
		if ($row = mysql_fetch_assoc($query))
		{
			$room = Room::fetchFromParameters($row);
			return $room;
		}
		else
		{
			Room::$staticErrors[] = "No room with id " . $id . " could be found.";
			return NULL;
		}		
	}
	
	public static function fetchFromDbRooms() 
	{				
		$rooms = array();
		$sql = "SELECT * FROM bsi_rooms WHERE is_apartment = 0";
		$query = mysql_query($sql);
		if (!$query)
		{
			global $logger;
			$logger->LogFatal("Error executing query: $sql");
			$logger->LogFatal("Database error: " . mysql_errno() . ". Message: " . mysql_error());
			die("Database error: " . mysql_errno() . ". Message: " . mysql_error());			
		}
		while ($row = mysql_fetch_assoc($query))
		{
			$room = Room::fetchFromParameters($row);
			$rooms[] = $room;			
		}
		return $rooms;		
	}
	
	public static function fetchFromDbApartments()
	{				
		$rooms = array();
		$sql = "SELECT * FROM bsi_rooms WHERE is_apartment = 1";
		$query = mysql_query($sql);
		if (!$query)
		{
			global $logger;
			$logger->LogFatal("Error executing query: $sql");
			$logger->LogFatal("Database error: " . mysql_errno() . ". Message: " . mysql_error());
			die("Database error: " . mysql_errno() . ". Message: " . mysql_error());			
		}
		while ($row = mysql_fetch_assoc($query))
		{
			$room = Room::fetchFromParameters($row);
			$rooms[] = $room;			
		}
		return $rooms;		
	}

	public function isValid()
	{
		$this->errors = array();		
		if (strlen(trim($this->roomNumber)) == 0)
		{
			$this->setError("Room number cannot be empty");
		}
		if (strlen(trim($this->roomName)) == 0)
		{
			$this->setError("Room name cannot be empty.");
		}
        if ((sizeof($this->roomDescriptions)) == 0)
        {
            $this->setError("Room description cannot be empty.");
        }
        else
        {
            foreach($this->roomDescriptions as $roomDescription)
            {
                if (strlen(trim($roomDescription)) == 0)
                {
                    $this->setError("Room description cannot be empty.");
                }
            }
        }
		return sizeof($this->errors) == 0;					
	}
	
	public function save()
	{
		$this->errors = array();
		if (!$this->isValid())
		{
			return false;
		}
		
		else if ($this->id == 0)
		{
			// Run INSERT
			$sql = "INSERT INTO bsi_rooms (room_number, room_name, ";
            foreach ($this->roomDescriptions as $key => $value)
            {
                $sql.= "room_desc_" . $key . ", ";
            }
            $sql.= "extra_bed, capacity, is_apartment) VALUES (";
		  	$sql = $sql . "'" . mysql_escape_string($this->roomNumber) .  "', '" . mysql_escape_string($this->roomName) . "', ";
            foreach ($this->roomDescriptions as $key => $value)
            {
                $sql.= "'" . mysql_escape_string($value) . "', ";
            }
            $sql.= ($this->hasExtraBed ? "1" : "0") . ", " . $this->capacity . ", ";
            $sql.= ($this->isApartment ? "1" : "0") . ")";            
			if (!mysql_query($sql))
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
			$sql = "UPDATE bsi_rooms ";
			$sql = $sql . "SET room_number = '" . mysql_escape_string($this->roomNumber) . "', ";
			$sql = $sql . "room_name = '" . mysql_escape_string($this->roomName) . "', ";
            foreach ($this->roomDescriptions as $key => $value)
            {
                $sql.= "room_desc_" . $key . " = '" . mysql_escape_string($value) . "', ";
            }
			$sql = $sql . "extra_bed = " . ($this->hasExtraBed ? "1" : "0") . ", ";
			$sql = $sql . "capacity = " . $this->capacity . ", ";
			$sql = $sql . "is_apartment = " . ($this->isApartment ? "1" : "0");         
		  	$sql = $sql . " WHERE id = " . $this->id;			 		  	
			if (!mysql_query($sql))
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
			if (!mysql_query($sql))
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