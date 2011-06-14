<?php 

class UploadImage
{
	public static $errors = array();
	
	// 
	/* This function reads the extension of the file. 
	 *
	*/
	static function getExtension($fileName)
	{
		$i = strrpos ($fileName, ".");
		if (! $i)
		{
			return "";
		}
		$l = strlen ($fileName) - $i;
		$ext = substr ($fileName, $i + 1, $l);
		return strtolower($ext);
	}
	
	static function upload($array, $inputName, $location, &$uploadedImage)
	{
		global $logger;			
		$logger->LogInfo("Attempting to upload file ...");
		$logger->LogInfo("Input name: " . $inputName);
		$logger->LogInfo("Location: " . $location);
		$logger->LogInfo("Array: " . var_export($array, true));
			
		$errors = array();
		$image = $_FILES[$inputName]['name'];
		if ($image)
		{
			$filename = stripslashes($image);
			//get the extension of the file in a lower case format
			$extension = UploadImage::getExtension ($filename);
			$logger->LogInfo("Extension: " . $extension);

			//if it is not a known extension, we will suppose it is an error and will not  upload the file,  
			//otherwise we will do more tests
			if (($extension == "jpg") || ($extension == "jpeg") || ($extension == "png") || ($extension == "gif"))
			{				
				//we will give an unique name, for example the time in unix time format
				$imageName = time() . '_' . rand(1, 1000000) . '.' . $extension;
				//the new name will be containing the full path where will be stored (images folder)
				$newname = $location . "/" . $imageName;
				//we verify if the image has been uploaded, and print error instead
				$logger->LogInfo("Copying " . $_FILES[$inputName]['tmp_name'] . " to " .  $newname);
				$copied = copy ($_FILES[$inputName]['tmp_name'], $newname);
				if ($copied)
				{
					$logger->LogInfo("Copy successful.");
					$uploadedImage = $imageName;
					return true;
				}
				else
				{
					$logger->LogError("Error copying file " . $_FILES[$inputName]['tmp_name'] . " to images directory.");
					$errors[sizeof($errors)] = "Error copying file " . $_FILES[$inputName]['tmp_name'] . " to images directory.";
					return false;
				}	
			}
			else
			{
				$logger->LogError("Invalid extension " . $extension);
				$errors[sizeof($errors)] = "Invalid extension " . $extension;
				return false;
			}		
		}
		else
		{
			$logger->LogError("Array does not have input name " . $inputName);
			$errors[sizeof($errors)] = "Array does not have input name " . $inputName;
			return false;
		} 
	}
}
?>