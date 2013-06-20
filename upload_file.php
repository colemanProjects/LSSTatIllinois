<!DOCTYPE html PUBLIC "upload page"> 
<html xmlns="coleman" xml:lang="en" lang="en">
<head>
<meta http-equiv="content-type" content"text/html; charset=utf-8" />

	<title> Upload Page</title>
	<body>
		<div id ="content">
		<?php include('includes/homeButton.php'); ?>
		</div>

<?php

echo "uploading..." . "<br>";

//extensions allowed in the uploaded file
$allowedExts = array("tiff","jpeg","jpg","gif","png", "fits");
$extension = end(explode(".", $_FILES["file"]["name"]));
echo "extension = " . $extension . "<br>"; 
$file_type = $_FILES["file"]["type"]; 

//check whether the extension is valid
if(!in_array($extension, $allowedExts)){
	$error_message = 'Only jpg, jpeg, png, fits, and gif image files are supported'; 
	$error = 'yes';
	echo "File(" . $_FILES["file"]["name"] . ") failed to upload.<br>";
  	echo "It may be due to an invalid file type.<br>";
  	echo "$error_message";
}
else
{
  //check whether there's an error with the file 
  if ($_FILES["file"]["error"] > 0)
  {
	echo "Return Code: " . $_FILES["file"]["error"] . "<br>";
  }
  else
  {
	echo "Upload: " . $_FILES["file"]["name"] . "<br>";
	echo "Type: " . $_FILES["file"]["type"] . "<br>";
	echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
	echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br>";

	//check whether the file exists or not
	if (file_exists("uploads/" . $_FILES["file"]["name"]))
	{
	  echo $_FILES["file"]["name"] . " already exists. ";
	}
	else
	{
		//universal name for uploaded files
		$upload_filename = "centralFrameImage." . $extension; 
		$newFilename = basename($_FILES["file"]["tmp_name"]) . ".tiff";

		//actually copy the file to specified location(uploads directory)
		$remove = exec("rm -rf uploads/*"); 
		if($remove == "") echo "successfully removed old files<br>"; 
		$test = move_uploaded_file($_FILES["file"]["tmp_name"],
		"uploads/" . $upload_filename);
		if(!$test) echo "upload failed--probably due to file permissions"; 
		echo "Stored in: " . "uploads/" . $upload_filename . "<br>";
		$myImage = "uploads/" . $upload_filename;
		$size = getImageSize($myImage);
		echo "Image dimensions are: " . $size[3] . "<br>"; 
		
		echo "Converting to tiff style image..." . "<br>";
		if(strtolower(extension) == "fits")
		{
			$convertCommand = "convert uploads/" . $upload_filename . " -define quantum:format=floating-point -define tiff:tile-geometry=128x128 -compress LZW 'ptif:uploads/$newFilename' "; 
		}
		else if(strtolower(extension) == "ptif" || strtolower($extension) == "tif")
		{
			$convertCommand = "cp uploads/" . $upload_filename . " uploads/$newFilename"; 
		}
		else
		{
			$convertCommand = "convert uploads/" . $upload_filename . " -define tiff:tile-geometry=128x128 -compress LZW 'ptif:uploads/$newFilename' "; 
		}
		echo $convertCommand;
		$conversion = exec($convertCommand); 
		//("convert uploads/centralFrameImage.jpg -define tiff:tile-geometry=256x256 -compress jpeg 'ptif:uploads/$newFilenamege' "); 
		if($conversion == "")echo "File successfully converted to tiff";
		else echo "Something went wrong when converting the image to a tiff"; 
		//$writeFile = fopen("sourceFile.js");
		//echo $writeFile == false;
 		//fwrite($writeFile, "8");
		//fclose($writeFile);
		//exec("chmod uo+r iipmooviewer-1.1/sourceFile.js");
		//is writeable: try that
	}
  }
}
?> 
