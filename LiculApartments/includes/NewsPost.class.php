<?php
class NewsPost
{  
	public $id = 0;
	public $categoryId = 0;
	public $title = null;
	public $contents = null;
	public $imageSmall = "";
	public $imageMedium = "";
	public $imageLarge = "";
	public $postedDate = null;
	public $posterName = "";
	
	private static $titlePrefix = "title_";	
	private static $contentsPrefix = "contents_";
	
	public $errors = array();
	public static $staticErrors = array();

	public function __construct()
	{
		$this->title = new LocalizedText(NewsPost::$titlePrefix);
		$this->contents = new LocalizedText(NewsPost::$contentsPrefix);
		$this->postedDate = new Date();
	}
	
	public function getCategory()
	{
		return NewsCategory::fetchFromDb($this->categoryId);
	}
	
	public static function fetchFromParameters($params, $isDatabaseRow = false) 
	{
		NewsPost::$staticErrors = array();
		
		$newsPost = new NewsPost();
		if (isset($params['id']) && is_numeric($params['id']))
		{
			$newsPost->id = intval($params['id']);
		}
		if (isset($params['category_id']) && is_numeric($params['category_id']))
		{
			$newsPost->categoryId = intval($params['category_id']);
		}
		$newsPost->title = LocalizedText::fetchFromParameters($params, NewsPost::$titlePrefix);
		$newsPost->contents = LocalizedText::fetchFromParameters($params, NewsPost::$contentsPrefix);
		
		if ($isDatabaseRow && isset($params['image_small']))
		{
			$newsPost->imageSmall = $params['image_small'];
		}
		else if (!$isDatabaseRow && isset($_FILES['image_small']['name']) && trim($_FILES['image_small']['name']) != "")
		{
			$imageName = NULL;
			if (!UploadImage::upload($_FILES, "image_small", "../images", $imageName))
			{
				NewsPost::$staticErrors = UploadImage::$errors;
				return null;
			}
			$newsPost->imageSmall = $imageName;
		}
		
		if ($isDatabaseRow && isset($params['image_medium']))
		{
			$newsPost->imageMedium = $params['image_medium'];
		}
		else if (!$isDatabaseRow && isset($_FILES['image_medium']['name']) && trim($_FILES['image_medium']['name']) != "")
		{
			$imageName = NULL;
			if (!UploadImage::upload($_FILES, "image_medium", "../images", $imageName))
			{
				NewsPost::$staticErrors = UploadImage::$errors;
				return null;
			}
			$newsPost->imageMedium = $imageName;
		}
		
		if ($isDatabaseRow && isset($params['image_large']))
		{
			$newsPost->imageLarge = $params['image_large'];
		}
		else if (!$isDatabaseRow && isset($_FILES['image_large']['name']) && trim($_FILES['image_large']['name']) != "")
		{
			$imageName = NULL;
			if (!UploadImage::upload($_FILES, "image_large", "../images", $imageName))
			{
				NewsPost::$staticErrors = UploadImage::$errors;
				return null;
			}
			$newsPost->imageLarge = $imageName;
		}
		
		if (isset($params['poster_name']))
		{
			$newsPost->posterName = trim($params['poster_name']);
		}
		
		if (isset($params['date_posted']))
		{
			$newsPost->postedDate = Date::parse(trim($params['date_posted']));
		}		
		return $newsPost;
	}
	
	public static function fetchFromDb($id) 
	{
		global $logger;
		NewsPost::$staticErrors = array();
		if (!is_numeric($id))
		{
			$logger->LogError("Id " . $id . " is not numeric.");
			NewsPost::$staticErrors[] = "Id " . $id . " is not numeric.";
			return null;
		}
		
		$id = intval($id);
		$sql = "SELECT * FROM bsi_news_posts WHERE id = " . $id;
		$query = mysql_query($sql);
		if (!$query)
		{
			$logger->LogError("Error: " . mysql_errno() . ". Error message: " . mysql_error());
			$logger->LogError("SQL: $sql");
			die ("Error: " . mysql_errno() . ". Error message: " . mysql_error());
		}
		if ($row = mysql_fetch_assoc($query))
		{
			$newsPost = NewsPost::fetchFromParameters($row, true);
			return $newsPost;			
		}
		else
		{
			$logger->LogError("No news post with id " . $id . " could be found.");			
			return null;
		}		
	}
	
	public static function fetchFromDbNewest() 
	{
		global $logger;		
		$sql = "SELECT * FROM bsi_news_posts ORDER BY date_posted DESC LIMIT 1";
		$query = mysql_query($sql);
		if (!$query)
		{
			$logger->LogError("Error: " . mysql_errno() . ". Error message: " . mysql_error());
			$logger->LogError("SQL: $sql");
			die ("Error: " . mysql_errno() . ". Error message: " . mysql_error());
		}
		if ($row = mysql_fetch_assoc($query))
		{
			$newsPost = NewsPost::fetchFromParameters($row, true);
			return $newsPost;			
		}
		else
		{
			$logger->LogError("There are no news posts.");			
			return null;
		}		
	}
	
	public static function fetchFromDbNewestX($limit = 1) 
	{
		global $logger;	
		$newsPosts = array();	
		$limit = max(intval($limit), 1);
		$sql = "SELECT * FROM bsi_news_posts ORDER BY date_posted DESC LIMIT $limit";
		$query = mysql_query($sql);
		if (!$query)
		{
			$logger->LogError("Error: " . mysql_errno() . ". Error message: " . mysql_error());
			$logger->LogError("SQL: $sql");
			die ("Error: " . mysql_errno() . ". Error message: " . mysql_error());
		}
		while ($row = mysql_fetch_assoc($query))
		{
			$newsPost = NewsPost::fetchFromParameters($row, true);
			$newsPosts[] = $newsPost;						
		}
		return $newsPosts;		
	}
	
	public static function fetchAllFromDb() 
	{
		global $logger;
		NewsPost::$staticErrors = array();
		
		$newsPosts = array();
		$sql = "SELECT * FROM bsi_news_posts ORDER BY date_posted DESC";
		$query = mysql_query($sql);
		if (!$query)
		{
			$logger->LogError("Error: " . mysql_errno() . ". Error message: " . mysql_error());
			$logger->LogError("SQL: $sql");
			die ("Error: " . mysql_errno() . ". Error message: " . mysql_error());
		}
		while ($row = mysql_fetch_assoc($query))
		{
			$newsPost = NewsPost::fetchFromParameters($row, true);
			$newsPosts[] = $newsPost;			
		}				
		return $newsPosts;
	}
	
	public static function fetchFromDbPage($page, $isPageNumberValidated = false) 
	{
		global $logger;
		global $systemConfiguration;
		NewsPost::$staticErrors = array();
		
		$postsPerPage = $systemConfiguration->getAdminItemsPerPage();
		if (!$isPageNumberValidated)
		{
			if ($page < 1)
			{
				$page = 1;
			}
			else
			{
				$count = NewsPost::count();				
				$lastPage = ceil($count/$postsPerPage);
				if ($page < $lastPage)
				{
					$page = $lastPage;
				}
			}
		}
		
		$newsPosts = array();
		$sql = "SELECT * FROM bsi_news_posts ORDER BY date_posted DESC LIMIT " . ($page - 1) * $postsPerPage . "," .$postsPerPage;
		$query = mysql_query($sql);
		if (!$query)
		{
			$logger->LogError("Error: " . mysql_errno() . ". Error message: " . mysql_error());
			$logger->LogError("SQL: $sql");
			die ("Error: " . mysql_errno() . ". Error message: " . mysql_error());
		}
		while ($row = mysql_fetch_assoc($query))
		{
			$newsPost = NewsPost::fetchFromParameters($row, true);
			$newsPosts[] = $newsPost;			
		}				
		return $newsPosts;
	}
	
	public static function count($where = null) 
	{
		global $logger;			
		$sql = "SELECT COUNT(*) as ct FROM bsi_news_posts ";
		if ($where != null)
		{
			$sql.= $where;
		}
		$query = mysql_query($sql);
		if (!$query)
		{
			$logger->LogError("Error: " . mysql_errno() . ". Error message: " . mysql_error());
			$logger->LogError("SQL: $sql");
			die ("Error: " . mysql_errno() . ". Error message: " . mysql_error());
		}
		if ($row = mysql_fetch_assoc($query))
		{
			return intval($row['ct']);						
		}				
		return 0;
	}

	public function isValid()
	{
		$this->errors = array();
		if ($this->categoryId == 0)
		{
			$this->errors[] = "Category must be set";
		}		
		if ($this->title->areAnyValuesEmpty())
		{
			$this->errors[] = "Title cannot be empty";
		}
		if ($this->contents->areAnyValuesEmpty())
		{
			$this->errors[] = "Contents cannot be empty";
		}
		if (strlen(trim($this->posterName)) == 0)
		{
			$this->errors[] = "Poster name cannot be empty";
		}		
		if ($this->id == 0 && strlen(trim($this->imageSmall)) == 0)
		{
			$this->errors[] = "Small image name cannot be empty";
		}		
		if ($this->id == 0 && strlen(trim($this->imageMedium)) == 0)
		{
			$this->errors[] = "Medium image name cannot be empty";
		}		
		if ($this->id == 0 && strlen(trim($this->imageLarge)) == 0)
		{
			$this->errors[] = "Large image name cannot be empty";
		}		
		if (strlen(trim($this->posterName)) == 0)
		{
			$this->errors[] = "Poster name cannot be empty";
		}
		if (sizeof($this->errors) == 0)
		{
			$newsCategory = NewsCategory::fetchFromDb($this->categoryId);
			if ($newsCategory == null)
			{
				$this->errors[] = "Invalid category";
			} 
		}		
		return sizeof($this->errors) == 0;
	}
	
	public function save($isValidated = false)
	{
		global $logger;
		if (!$isValidated && !$this->isValid())
		{
			return false;
		}
		
		if ($this->id == 0)
		{
			// Run INSERT
			$sql = "INSERT INTO bsi_news_posts (category_id, ";
			$sql.= $this->title->getMySqlFields(false);
			$sql.= $this->contents->getMySqlFields(false);
			$sql.= "image_small, image_medium, image_large,  poster_name, date_posted) VALUES (";
			$sql.= $this->categoryId . ", ";
			$sql.= $this->title->getMySqlValues(false);
			$sql.= $this->contents->getMySqlValues(false);						
			$sql.= "'" . mysql_escape_string($this->imageSmall) . "', ";
			$sql.= "'" . mysql_escape_string($this->imageMedium) . "', ";
			$sql.= "'" . mysql_escape_string($this->imageLarge) . "', ";
			$sql.= "'" . mysql_escape_string($this->posterName) . "', ";
			$sql.= "'" . $this->postedDate->formatMySql() . "'";
			$sql.= ")";		  			  	
			if (!mysql_query($sql))
			{				
				$logger->LogError("Error: " . mysql_errno() . ". Error message: " . mysql_error());
				$logger->LogError("SQL: $sql");
				die('Error: ' . mysql_error());
			} 
			$this->id = mysql_insert_id();
		}
		else 
		{
			// Run UPDATE
			$sql = "UPDATE bsi_news_posts SET ";
			$sql.= "category_id = " . $this->categoryId . ", ";
			$sql.= $this->title->getMySqlValuesForSet(false);
			$sql.= $this->contents->getMySqlValuesForSet(false);
			if (strlen(trim($this->imageSmall)) > 0)
			{
				$sql = $sql . "image_small = '" . mysql_escape_string($this->imageSmall) . "', ";
			}
			if (strlen(trim($this->imageMedium)) > 0)
			{
				$sql = $sql . "image_medium = '" . mysql_escape_string($this->imageMedium) . "', ";
			}
			if (strlen(trim($this->imageLarge)) > 0)
			{
				$sql = $sql . "image_large= '" . mysql_escape_string($this->imageLarge) . "', ";
			}
			$sql.= "poster_name = '" . mysql_escape_string($this->posterName) . "', ";
			$sql.= "date_posted = '" . $this->postedDate->formatMySql() . "'";						
		  	$sql.= " WHERE id = " . $this->id;			 		  	
			if (!mysql_query($sql))
			{
				$logger->LogError("Error: " . mysql_errno() . ". Error message: " . mysql_error());
				$logger->LogError("SQL: $sql");
				die('Error: ' . mysql_error());
			}							
		}	
		return true;	
	}
	
	public static function delete($id)	
	{
		global $logger;
		NewsPost::$staticErrors = array();
		if (is_numeric($id))
		{
			// Run DELETE
			$sql = "DELETE FROM bsi_news_posts WHERE id = " . $id;			 		  	
			if (!mysql_query($sql))
			{
				$logger->LogError("Error: " . mysql_errno() . ". Error message: " . mysql_error());
				$logger->LogError("SQL: $sql");
				die('Error: ' . mysql_error());
			}
			return true;
		}
		else
		{
			NewsPost::$staticErrors[] = "Id is not numeric";
			return false;
		}
	}
}