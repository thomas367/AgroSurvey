<?php 
	session_start();
	if(!(isset($_SESSION['userID'])))
		header("Location:../index.php");
?>

<?php 
	require("../models/survey.php");
	$surveyObj = new Survey();
	$survey = null;
	$surveyId = $_POST['id'];
	$path = "../images/$surveyId";
	
	if ($surveyId) {
		$survey = $surveyObj->getSurveysByID($surveyId);
		if ($survey) {
			$surveyObj->deleteSurvey($surveyId);
		}
		$folders = glob($path . '/*', GLOB_ONLYDIR);
		foreach($folders as $folder){
			$files = glob($folder . '/*');
			foreach($files as $file){
				if(is_file($file)){
					unlink($file);
				}
			}
			rmdir($folder);
		}
		rmdir($path);
	}
?>














