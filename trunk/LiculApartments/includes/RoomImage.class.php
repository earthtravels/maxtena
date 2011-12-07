<?php
//include_once ("SystemConfiguration.class.php");

class RoomImage
{	
    public $roomId = 0;    
	public $galleryImage = null;
	
	public $errors = array();
	public static $staticErrors = array();
	
	public function __construct()
	{	
		$this->galleryImage = new GalleryImage();
    }
	
	public static function fetchFromParameters($postParams, $fileParams=null) 
	{
		global $systemConfiguration;
		global $logger;
        RoomImage::$staticErrors = array();
		$roomImage = new RoomImage();
		$roomImage->galleryImage = GalleryImage::fetchFromParameters($postParams, $fileParams);
		if (isset($postParams['room_id']) && is_numeric($postParams['room_id']))
		{
			$roomImage->roomId = intval($postParams['room_id']);
		}		
		else if (is_null($roomImage->galleryImage))
		{
			$logger->LogError("Gallery image is null.");
		}
		return $roomImage;
	}

	
	public static function fetchFromDbForRoom($roomId) 
	{		
		global $logger;
		$logger->LogDebug("Fetching all room images for room id: $roomId");
		
		$roomImages = array();
		$sql = "SELECT ri.*, gi.* FROM bsi_room_images ri INNER JOIN bsi_gallery_images gi ON ri.image_id = gi.id WHERE ri.room_id = $roomId ORDER BY gi.display_order";
		$query = mysql_query($sql);
		if (!$query)
		{
			$logger->LogFatal("Database error: " . mysql_errno() . ". Message: " . mysql_error());
			$logger->LogError("SQL: " . $sql);
			die("Database error: " . mysql_errno() . ". Message: " . mysql_error());			
		}
		while ($row = mysql_fetch_assoc($query))
		{
			$roomImage = RoomImage::fetchFromParameters($row);
			$roomImages[sizeof($roomImages)] = $roomImage;
		}
		mysql_free_result($query);
		return $roomImages;		
	}

	public static function fetchFromDb($roomId, $imageId) 
	{		
		global $logger;
		$logger->LogDebug("Fetching all room images for room id: $roomId");
				
		$sql = "SELECT ri.*, gi.* FROM bsi_room_images ri INNER JOIN bsi_gallery_images gi ON ri.image_id = gi.id WHERE ri.room_id = $roomId AND image_id = $imageId ORDER BY gi.display_order";
		$query = mysql_query($sql);
		if (!$query)
		{
			$logger->LogFatal("Database error: " . mysql_errno() . ". Message: " . mysql_error());
			$logger->LogError("SQL: " . $sql);
			die("Database error: " . mysql_errno() . ". Message: " . mysql_error());			
		}
		if ($row = mysql_fetch_assoc($query))
		{
			$roomImage = RoomImage::fetchFromParameters($row);
			return $roomImage;
		}
		mysql_free_result($query);
		return null;		
	}

	public function isValid()
	{
		$this->errors = array();		
		if ($this->roomId == 0)
		{
			$this->setError("Room id cannot be 0");
		}
		if ($this->galleryImage == null)
		{			
			$this->setError("Image must be set");
		}
		else if (!$this->galleryImage->isValid())
		{
			foreach ($this->galleryImage->errors as $error) 
			{
				$this->setError($error);
			}			
		}
		else if ($this->galleryImage->id <= 0 && strlen(trim($this->galleryImage->thumbImageFileName)) == 0)
		{
			$this->setError("Thumbnail image file name cannot be empty");
		}
		return sizeof($this->errors) == 0;					
	}
	
	public function save()
	{		
		global $logger;
		if (!$this->isValid())
		{
			return false;
		}
		
		if(!$this->galleryImage->save())
		{
			$this->errors = $this->galleryImage->errors;
			return false;
		}
		
		$sql = "SELECT * from bsi_room_images WHERE room_id = " . $this->roomId . " AND image_id = " . $this->galleryImage->id . " LIMIT 1";		
		$query = mysql_query($sql);
		if (!$query)
		{
			$logger->LogFatal("Database error: " . mysql_errno() . ". Message: " . mysql_error());
			$logger->LogError("SQL: " . $sql);
			die("Database error: " . mysql_errno() . ". Message: " . mysql_error());			
		}
		$rowCount = mysql_numrows($query);
		mysql_free_result($query);
		
		if ($rowCount == 0)
		{
			// Run INSERT
			$sql = "INSERT INTO bsi_room_images (room_id, image_id) VALUES (" . $this->roomId . ", " . $this->galleryImage->id . ")";			
            $query = mysql_query($sql);
			if (!$query)
			{
				$logger->LogFatal("Database error: " . mysql_errno() . ". Message: " . mysql_error());
				$logger->LogError("SQL: " . $sql);
				die("Database error: " . mysql_errno() . ". Message: " . mysql_error());
			}			
			mysql_free_result($query);
		}		
		return true;	
	}
	
	public static function delete($roomId, $imageId)	
	{
		global $logger;
		$this->errors = array();
		if (is_numeric($roomId) && is_numeric($imageId))
		{
			// Run DELETE
			$sql = "DELETE FROM bsi_room_images WHERE room_id = $roomId AND image_id = $imageId";			 		  	
			if (!mysql_query($sql))
			{
				$logger->LogFatal("Database error: " . mysql_errno() . ". Message: " . mysql_error());
				$logger->LogError("SQL: " . $sql);
				die("Database error: " . mysql_errno() . ". Message: " . mysql_error());
			}
		}
	}
	
	private function setError($errorMessage)
	{
		$this->errors[] = $errorMessage;
	}
}