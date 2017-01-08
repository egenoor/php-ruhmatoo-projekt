<?php

require("../functions.php");



//kui ei ole kasutaja id'd
if (!isset($_SESSION["userId"])) {

	//suunan sisselogimise lehele
	header("Location: login.php");
	exit();

}

//kui on ?logout aadressi real siis login välja
if(isset ($_GET["logout"])) {

	session_destroy();
	header("Location:login.php");
	exit();
}

$msg = " ";
if(isset($_SESSION["message"])) {
	$msg = $_SESSION["message"];

	//kui ühe näitame siis kusutua ära, et pärast refreshi ei näita
	unset($_SESSION["message"]);

}



?>


<?php
if(isset($_FILES["fileToUpload"]) && !empty($_FILES["fileToUpload"]["name"])){
    $target_dir = "../profilepics/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
    // Check if image file is a actual image or fake image
    if(isset($_POST["submit"])) {
        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        if($check !== false) {
            echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }
    }
    // Check if file already exists
    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }
    // Check file size
    if ($_FILES["fileToUpload"]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }
    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            echo "Your profile picture has been uploaded.";
            
            // save file name to DB here
            addPicURL(basename( $_FILES["fileToUpload"]["name"]));
			
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}else{
    
}
?>


<!DOCTYPE html>
<h1>TV Show Calendar</h1>
<html>
<body>
<p>
	<h2>Welcome <?=$_SESSION["userName"];?>!</h2>
	<br>
	<img style="height: 200px; width: auto; " src="../profilepics/<?php getProfileURL(); ?>">

	<h3>For starters, let's add one series to your calender!</h3>
	
	<form action="calendar.php" method="post" enctype="multipart/form-data">
		<select name="user_tv_db">
			<?php getSeriesData() ?>
		</select>
		<br><br>
		<h3>Also, let's add a profile image:</h3>
		<input type="file" name="fileToUpload">
		<br><br>
		<input type="submit" value="Ready!" onclick="location='calendar.php'" />
	</form>

	<br><br>

	<br><br>
	<a href="?logout=1"> Log out</a>
</p>
</body>
</html>