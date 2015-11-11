<?php
	require_once("functions.php");
	require_once("InterestsManager.class.php");
	//data.php
	// siia pääseb ligi sisseloginud kasutaja
	//kui kasutaja ei ole sisseloginud,
	//siis suuunan data.php lehele
	
	 // muutujad väärtuste jaoks
	$id = "";
	$interest = "";
	$name = "";
	$add_name = "";
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
	
	//uus instants klassist
	$InterestsManager = new InterestsManager($mysqli, $_SESSION["logged_in_user_id"]);
	
	//aadressirealt muutuja
	if(isset($_GET["new_interest"])){
	
		$add_new_response = $InterestsManager->addInterest($_GET["new_interest"]);
		
	}
	
	//rippmenüü valiku kõrval vajutati nuppu
	if(isset($_GET["new_dd_selection"])){
	
		$add_new_userinterest_response = $InterestsManager->addUserInterest($_GET["new_dd_selection"]);
		
	}
	
?>


<p>
	Tere, <?=$_SESSION["logged_in_user_email"];?>
	<a href="?logout=1"> Logi välja <a> 
</p>

<h2>Lisa huviala</h2>
 <?php if(isset($add_new_response->error)): ?>
  
	<p style="color:red;">
		<?=$add_new_response->error->message;?>
	</p>
  
  <?php elseif(isset($add_new_response->success)): ?>
	
	<p style="color:green;" >
		<?=$add_new_response->success->message;?>
	</p>
	
	<?php endif; ?>
<form>
	<input name="new_interest" placeholder="Huviala"><input type="submit" name="login" value="Lisa">
</form>

<h2>Minu huvialad</h2>
<form>
	<!--Siia järele tuleb rippmenüü -->
	<?=$InterestsManager->createDropdown();?>
	<input type="submit">
	
	<?php if(isset($add_new_userinterest_response->error)): ?>
  
	<p style="color:red;">
		<?=$add_new_userinterest_response->error->message;?>
	</p>
  
  <?php elseif(isset($add_new_userinterest_response->success)): ?>
	
	<p style="color:green;" >
		<?=$add_new_userinterest_response->success->message;?>
	</p>
	
	<?php endif; ?>
</form>