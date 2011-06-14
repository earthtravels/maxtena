<?php

class SliderImage
{	      
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
        SliderImage::$staticErrors = array();
		$sliderImage = new SliderImage();
		$sliderImage->galleryImage = GalleryImage::fetchFromParameters($postParams, $fileParams);				
		return $sliderImage;
	}	

	public static function fetchFromDb($imageId) 
	{		
		global $logger;
		$logger->LogInfo("Fetching slider image for image id: $imageId");
				
		$sql = "SELECT gi.* FROM bsi_slider_images ri INNER JOIN bsi_gallery_images gi ON ri.image_id = gi.id WHERE ri.image_id = $imageId ORDER BY gi.display_order";
		$query = mysql_query($sql);
		if (!$query)
		{
			$logger->LogFatal("Database error: " . mysql_errno() . ". Message: " . mysql_error());
			$logger->LogError("SQL: " . $sql);
			die("Database error: " . mysql_errno() . ". Message: " . mysql_error());			
		}
		if ($row = mysql_fetch_assoc($query))
		{
			$sliderImage = SliderImage::fetchFromParameters($row);
			return $sliderImage;
		}
		mysql_free_result($query);
		return null;		
	}
	
	public static function fetchAllDb() 
	{		
		global $logger;
		$logger->LogDebug("Fetching all slider images ...");

		$sliderImages = array();
		$sql = "SELECT gi.* FROM bsi_slider_images ri INNER JOIN bsi_gallery_images gi ON ri.image_id = gi.id ORDER BY gi.display_order";
		$query = mysql_query($sql);
		if (!$query)
		{
			$logger->LogFatal("Database error: " . mysql_errno() . ". Message: " . mysql_error());
			$logger->LogError("SQL: " . $sql);
			die("Database error: " . mysql_errno() . ". Message: " . mysql_error());			
		}
		while ($row = mysql_fetch_assoc($query))
		{
			$sliderImage = SliderImage::fetchFromParameters($row);
			$sliderImages[] = $sliderImage;
		}
		mysql_free_result($query);
		return $sliderImages;		
	}

	public function isValid()
	{
		$this->errors = array();		
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
		return sizeof($this->errors) == 0;					
	}
	
	public function save()
	{		
		global $logger;
		$logger->LogInfo(__METHOD__ .  " Saving ...");
		if (!$this->isValid())
		{
			return false;
		}
		
		if(!$this->galleryImage->save())
		{
			$logger->LogError(__METHOD__ .  " Error saving ...");
			$logger->LogError(__METHOD__ .  $this->galleryImage->errors);
			$this->errors = $this->galleryImage->errors;
			return false;
		}

		$sql = "SELECT * from bsi_slider_images WHERE image_id = " . $this->galleryImage->id . " LIMIT 1";		
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
			$sql = "INSERT INTO bsi_slider_images (image_id) VALUES (" . $this->galleryImage->id . ")";			
            $query = mysql_query($sql);
			if (!$query)
			{
				$logger->LogFatal("Database error: " . mysql_errno() . ". Message: " . mysql_error());
				$logger->LogError("SQL: " . $sql);
				die("Database error: " . mysql_errno() . ". Message: " . mysql_error());
			}			
		}		
		return true;	
	}
	
	public static function delete($imageId)	
	{
		global $logger;
		$this->errors = array();
		if (is_numeric($imageId))
		{
			// Run DELETE
			$sql = "DELETE FROM bsi_slider_images WHERE image_id = $imageId";			 		  	
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