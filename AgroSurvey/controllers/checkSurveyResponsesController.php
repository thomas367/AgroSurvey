<?php 
	session_start();
	if(!(isset($_SESSION['userID'])))
		header("Location:../index.php");
?>

<?php 
	require("../models/response.php");
	$responseObj = new Response();
	$response = null;
	$result = null;
	$surveyId = $_POST['id'];
	
	if ($surveyId) {
		$response = $responseObj->getResponsesBySurveyId($surveyId);
		if ($response) {
			$result = true;
		}
		else{
			$result = false;
		}
	}
	echo json_encode($result);
?>