<?php
//include_once ("SystemConfiguration.class.php");

class GalleryImage
{  	
	public $id = 0;
    public $imageFileName = "";
    public $thumbImageFileName = "";
    public $description = null;
    public $link = "";
    public $displayOrder = 0;
    
    private static $descriptionPrefix = "desc_"; 
    
	public $errors = array();
	public static $staticErrors = array();
	
	public function __construct()
	{	
		$this->description = new LocalizedText(GalleryImage::$descriptionPrefix);
    }
	
	public static function fetchFromParameters($postParams, $fileParams=null, $uploadLocation="../images") 
	{		
		global $systemConfiguration;
		global $logger;
        GalleryImage::$staticErrors = array();
		$galleryImage = new GalleryImage();
		if (isset($postParams['id']) && is_numeric($postParams['id']))
		{
			$galleryImage->id = intval($postParams['id']);
		}				
		if (isset($postParams['image_name']))
		{
			$galleryImage->imageFileName = $postParams['image_name'];
		}
		else if ($fileParams != null && isset($fileParams['image_name']) && strlen(trim($fileParams['image_name']['name'])) > 0)
        {
            $imageName = "";
			if (!UploadImage::upload($fileParams, 'image_name', $uploadLocation, $imageName))
            {
               GalleryImage::$staticErrors = UploadImage::$errors;
			   return null;
            }
			$galleryImage->imageFileName = $imageName;
        }
        
		if (isset($postParams['thumb_image_name']))
		{
			$galleryImage->thumbImageFileName = $postParams['thumb_image_name'];
		}
		else if ($fileParams != null && isset($fileParams['thumb_image_name']) && strlen(trim($fileParams['thumb_image_name']['name'])) > 0)
        {
            $imageName = "";
			if (!UploadImage::upload($fileParams, 'thumb_image_name', $uploadLocation, $imageName))
            {
               GalleryImage::$staticErrors = UploadImage::$errors;
			   return null;
            }
			$galleryImage->thumbImageFileName = $imageName;
        }
        $galleryImage->description = LocalizedText::fetchFromParameters($postParams, GalleryImage::$descriptionPrefix);
        if (isset($postParams['link']))
		{
			$galleryImage->link = $postParams['link'];
		}
		if (isset($postParams['display_order']) && is_numeric($postParams['display_order']))
		{
			$galleryImage->displayOrder = intval($postParams['display_order']);
		}		
		return $galleryImage;
	}
	
	private static function fetchFromDbSqlMultiple($sql) 
	{		
		global $logger;
		$logger->LogInfo("Fetching multiple gallery images using SQL: $sql");
		$galleryImages = array();		
		$query = mysql_query($sql);
		if (!$query)
		{
			$logger->LogFatal("Database error: " . mysql_errno() . ". Message: " . mysql_error());
			$logger->LogError("SQL: " . $sql);
			die("Database error: " . mysql_errno() . ". Message: " . mysql_error());			
		}
		while ($row = mysql_fetch_assoc($query))
		{
			$galleryImage = GalleryImage::fetchFromParameters($row);
			$galleryImages[] = $galleryImage;
		}
		mysql_free_result($query);
		return $galleryImages;		
	}
	
	private static function fetchFromDbSqlSingle($sql) 
	{		
		global $logger;
		$logger->LogInfo("Fetching single gallery image using SQL: $sql");				
		$query = mysql_query($sql);
		if (!$query)
		{
			$logger->LogFatal("Database error: " . mysql_errno() . ". Message: " . mysql_error());
			$logger->LogError("SQL: " . $sql);
			die("Database error: " . mysql_errno() . ". Message: " . mysql_error());			
		}
		if ($row = mysql_fetch_assoc($query))
		{
			$galleryImage = GalleryImage::fetchFromParameters($row);
			return $galleryImage;
		}
		mysql_free_result($query);
		return null;		
	}
	
	public static function fetchFromDbWhere($whereSql) 
	{		
		global $logger;
		$logger->LogInfo("Fetching gallery images using WHERE SQL: $whereSql");
		
		$galleryImages = array();
		$sql = "SELECT * FROM bsi_gallery_images " . $whereSql . " ORDER BY display_order";				
		$query = mysql_query($sql);
		if (!$query)
		{
			$logger->LogFatal("Database error: " . mysql_errno() . ". Message: " . mysql_error());
			$logger->LogError("SQL: " . $sql);
			die("Database error: " . mysql_errno() . ". Message: " . mysql_error());			
		}
		while ($row = mysql_fetch_assoc($query))
		{
			$galleryImage = GalleryImage::fetchFromParameters($row);
			$galleryImages[] = $galleryImage;
		}
		mysql_free_result($query);
		return $galleryImages;		
	}

	
	public static function fetchAllFromDb() 
	{		
		global $logger;
		$logger->LogInfo("Fetching all gallery images ...");
		$galleryImages = array();
		$sql = "SELECT * FROM bsi_gallery_images ORDER BY display_order";
		return GalleryImage::fetchFromDbSqlMultiple($sql);				
	}	
	
	public static function fetchFromDb($id) 
	{		
		global $logger;
		$logger->LogInfo("Fetching gallery image for id: $id");
		if (!is_numeric($id))
		{				
			$logger->LogError("Gallery image id: $id is not a valid number");
			return null;
		}
				
		$sql = "SELECT * FROM bsi_gallery_images WHERE id = " . $id;
		return GalleryImage::fetchFromDbSqlSingle($sql);				
	}	

	public function isValid()
	{
		$this->errors = array();		
		if ($this->id <= 0 && strlen(trim($this->imageFileName)) == 0)
		{
			$this->setError("Image file name cannot be empty");
		}		
		return sizeof($this->errors) == 0;					
	}
		
	public function save()
	{
		global $logger;
		$this->errors = array();
		if (!$this->isValid())
		{
			$logger->LogError("Invalid object tried to be saved!");
			$logger->LogError($this->errors);			
			return false;
		}
		
		global $logger;		
		$this->imageFileName = trim($this->imageFileName);
		$this->thumbImageFileName = trim($this->thumbImageFileName);
		$this->link = trim($this->link);
		if (strlen($this->link) == 0)
		{
			$this->link = "#";
		}		
		if ($this->id == 0)
		{
			$logger->LogInfo("Inserting new gallery image ...");
			// Run INSERT
			$sql = "INSERT INTO bsi_gallery_images (image_name, thumb_image_name, ";
			$sql.= $this->description->getMySqlFields(false);
			$sql.= "link, display_order) VALUES (";
			$sql.= "'" . mysql_escape_string($this->imageFileName) . "', ";
			$sql.= "'" . mysql_escape_string($this->thumbImageFileName) . "', ";
			$sql.= $this->description->getMySqlValues(false);
			$sql.= "'" . mysql_escape_string($this->link) . "', ";
			$sql.= $this->displayOrder;
			$sql.= ")";
            $query = mysql_query($sql);
			if (!$query)
			{
				$logger->LogFatal("Database error: " . mysql_errno() . ". Message: " . 19.04);
				$logger->LogError("SQL: " . $sql);
				die("Database error: " . mysql_errno() . ". Message: " . mysql_error());
			} 
			$this->id = mysql_insert_id();			
		}
		else 
		{
			$logger->LogInfo("Updating gallery image ...");
			// Run UPDATE
			$sql = "UPDATE bsi_gallery_images SET ";
			if (isset($this->imageFileName) && strlen(trim($this->imageFileName)) > 0)
            {
                $sql.= "image_name = " . mysql_escape_string($this->imageFileName) . "', ";
            }
			if (isset($this->thumbImageFileName) && strlen(trim($this->thumbImageFileName)) > 0)
            {
                $sql.= "thumb_image_name = " . mysql_escape_string($this->thumbImageFileName) . "', ";
            }			
			$sql.= $this->description->getMySqlValuesForSet(false);
			$sql.= "link = '" . mysql_escape_string($this->link) . "', ";
			$sql.= "display_order = " . $this->displayOrder;			
		  	$sql.= " WHERE id = " . $this->id;			 		  	
			$query = mysql_query($sql);
			if (!$query)
			{
				$logger->LogFatal("Database error: " . mysql_errno() . ". Message: " . mysql_error());
				$logger->LogError("SQL: " . $sql);
				die("Database error: " . mysql_errno() . ". Message: " . mysql_error());
			}									
		}
		$logger->LogInfo("Operation was successfull.");	
		return true;	
	}
	
	public static function delete($id)	
	{
		$this->errors = array();
		if (is_numeric($id))
		{
			// Run DELETE
			$sql = "DELETE FROM bsi_gallery_images WHERE id = " . $id;			 		  	
			if (!mysql_query($sql))
			{
				die('Error: ' . mysql_error());
			}
		}
	}
	
	private function setError($errorMessage)
	{
		$this->errors[] = $errorMessage;
	}
}