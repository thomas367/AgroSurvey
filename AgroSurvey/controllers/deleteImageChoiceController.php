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
		$choice = $choiceObj->getImageChoiceByChoiceId($choiceId);
		if($choice){
			$query = "SELECT image_path from imagechoices WHERE image_id='".$choiceId."'";
			$result = mysql_query($query);
			while($row = mysql_fetch_array($result)){
				$path = $row[0];
			}
			unlink($path);
			$choiceObj->deleteImageChoice($choiceId);
		}
	}
?>