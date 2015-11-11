<?php
	require_once("functions.php");
	//data.php
	// siia pääseb ligi sisseloginud kasutaja
	//kui kasutaja ei ole sisseloginud,
	//siis suuunan data.php lehele
	if(!isset($_SESSION["logged_in_user_id"])){
		header("Location: login.php");
		
		//see katkestab faili edasise lugemise
		exit();
	}
	
	//kasutaja tahab välja logida
	if(isset($_GET["logout"])){
		//aadressireal on olemas muutuja logout
		
		//kustutame kõik session muutujad ja peatame sessiooni
		session_destroy();
		
		header("Location: login.php");
	}
	
	
	//******FAILI ÜLESLAADIMINE w3schoolsist***********
	
	$target_dir = "profile_pics/";
	$target_file = $target_dir.$_SESSION["logged_in_user_id"].".jpg";
	
	if(isset($_POST["submit"])) {
		//profile_pics/1.jpg
	$uploadOk = 1;
	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
	// Check if image file is a actual image or fake image
	
		
		$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]); //kontrollib,kas tegu on pildiga
		
		if($check !== false) {
			echo "File is an image - " . $check["mime"] . ".";
			$uploadOk = 1;
		} else {
			echo "File is not an image.";
			$uploadOk = 0;
		}
	
	// Check if file already exists
	if (file_exists($target_file)) {
		echo "Sorry, file already exists.";
		$uploadOk = 0;
	}
	// Check file size
	if ($_FILES["fileToUpload"]["size"] > 1024000) {
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
			header("Location: data.php");
			echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
		} else {
			echo "Sorry, there was an error uploading your file.";
		}
	}
	}
	
	//Kustuta pilt
	if(isset($_GET["delete"])){
		unlink($target_file);
		header("Location: data.php");
	}
?>
	
<?php if(isset($_SESSION["login_success_message"])): ?>

	<p style="color:green;" >
		<?=$_SESSION["login_success_message"];?>
	</p>
<?php

	//kustutan sõnumi pärast esimest näitamist
	unset($_SESSION["login_success_message"]);
	
	endif; ?>
	
<p>
	Tere, <?=$_SESSION["logged_in_user_email"];?>
	<a href="?logout=1"> Logi välja <a> 
</p>

<h2>Profiilipilt</h2>

<?php if(file_exists($target_file)): ?>

<div style="width:200px; height:200px; background-image:url(<?=$target_file;?>);
background-position: center center; background-size: cover;"></div>

<a href="?delete=1">Delete profile pic</a>

<?php else: ?>

<form action="data.php" method="post" enctype="multipart/form-data">
    Lae üles pilt(1MB, png, gif)
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Upload Image" name="submit">
</form>

<?php endif; ?>

<?php

	$file_array = scandir($target_dir);
	var_dump($file_array);
	
	//iga faili nime kohta
	for($i = 0; $i < count($file_array); $i++){
		
		echo "<a href='".$target_dir.$file_array[$i]."'>".$file_array[$i]."</a>";
		
	}
?>
