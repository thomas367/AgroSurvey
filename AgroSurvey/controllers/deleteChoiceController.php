<?php 
	session_start();
	if(!(isset($_SESSION['userID'])))
		header("Location:../index.php");
?>

<?php 
	require("../models/choice.php");
	
	$choiceObj = new Choice();
	$choiceId = $_POST['id'];
	$choice = null;

	if($choiceId){
		$choice = $choiceObj->getChoiceByChoiceId($choiceId);
		if($choice){
			$choiceObj->deleteChoice($choiceId);
		}
	}
?>