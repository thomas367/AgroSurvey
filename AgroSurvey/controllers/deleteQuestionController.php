<?php 
	session_start();
	if(!(isset($_SESSION['userID'])))
		header("Location:../index.php");
?>


<?php
	require("../models/question.php");
	$questionObj = new Question();
	$questionId = $_POST['id'];
	$question = null;

	if($questionId){
		$question = $questionObj->getQuestionByQuestionId($questionId);
		if($question){
			$query = "SELECT question_type, survey_id FROM questions WHERE question_id='".$questionId."'";
			$result = mysql_query($query);
			while($row = mysql_fetch_array($result)){
				$questionType = $row[0];
				$surveyId = $row[1];
			}
			if($questionType == 1){
				$questionObj->deleteQuestion($questionId);
			}
			else if($questionType == 2){
				$questionObj->deleteQuestion($questionId);
			}
		}
		$path = "../images/$surveyId/$questionId";
		$files = glob($path . '/*');
		foreach($files as $file){
			if(is_file($file)){
				unlink($file);
				echo $file;
			}
		}
		rmdir($path);
	}
?>