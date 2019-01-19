<?php 
	session_start();
	if(!(isset($_SESSION['userID'])))
		header("Location:../index.php");
?>

<?php 

	require("../models/question.php");
	require("../models/choice.php");
	require("imageValidationController.php");
	
	$surveyId = $_POST['surveyId'];
	$questionId = $_POST['question_id'];
	$questionTitle = $_POST['question_title'];
	mysql_real_escape_string($questionTitle);
	$questionType = $_POST['select_question_type'];
	//single choice
	$choiceIds = array();
	$choices = array();
	// image choice
	$imageChoiceIds = array();
	$images = array();
	$result = null;
			
	if (!empty($questionId)){ // Edit Question
		$questionObj = new Question();
		$questionObj->updateQuestion($questionId, $questionType ,$questionTitle);
	}
	else{ // Create Question
		$questionObj = new Question();
		$questionObj->createQuestion($surveyId, $questionType ,$questionTitle);
		$questionId = (string)mysql_insert_id();
		
		if($questionType == 2){
			/*	Create a folder for the current question
				based on the questionId.
				Each question has a unique folder. 
			*/
			if ( !file_exists("../images/$surveyId/$questionId") ) {
				mkdir("../images/$surveyId/$questionId", 0777, true);
			}
		}
	}
	
	if($questionType == 1){
		if(isset($_POST['choices'])){	
			$choiceIds = $_POST['choice_ids'];
			$choices = $_POST['choices'];
			
			foreach ($choices as $key => $value) {
				$choice_text = $value;
				$choiceId = $choiceIds[$key];
				mysql_real_escape_string($choice_text);	
				if (!empty($choiceId)) { // Edit Choice
					$choiceObj = new Choice();
					$choiceObj->updateChoice($choiceId, $choice_text);	
				} 
				else { // Create Choice
					$choiceObj = new Choice();
					$choiceObj->createChoice($questionId, $choice_text);
					$choiceIds[$key] = (string)mysql_insert_id();
				}
			}	
		}
	}
	else if($questionType == 2){
		if(isset($_FILES['image_choices'])){	
			$imageChoiceIds = $_POST['image_choice_ids'];
			$names = $_FILES['image_choices']['name'];
			$images = $_FILES['image_choices']['tmp_name'];
			$sizes = $_FILES['image_choices']['size'];
			
			foreach ($names as $key => $value) {
				$image = $images[$key];
				$imageChoiceId = $imageChoiceIds[$key];
				$name = $names[$key];
				$size = $sizes[$key];
				
				// Update image choice
				if (!empty($imageChoiceId)) { 
					if(!empty($image)){
						$query = "SELECT image_path from imagechoices WHERE image_id='".$imageChoiceId."'";
						$result = mysql_query($query);
						while($row = mysql_fetch_array($result)){
							$oldImagePath = $row[0];
						}
						$validationObj = new imageValidation();
						// check if the file is image
						$result = $validationObj->fileExtensionCheck($name);
						if($result == null){
							// check the size of the image
							$result = $validationObj->fileSizeCheck($size);
							if($result == null){
								//$oldImagePath = mb_convert_encoding($oldImagePath, 'GREEK', 'UTF-8');
								// Delete old image in the directory
								unlink($oldImagePath); 
								// convert chars to save to directory
								//$nameDir = mb_convert_encoding($name, 'GREEK', 'UTF-8');	
								$pathDir = "../images/$surveyId/$questionId/".$name;
								// Upload image in the directory
								move_uploaded_file($image, $pathDir);
								// convert chars for database
								$nameDatabase = mb_convert_encoding($name, 'HTML-ENTITIES', 'UTF-8');
								$nameDatabase = html_entity_decode($nameDatabase);
								$pathDatabase = "../images/$surveyId/$questionId/".$nameDatabase;
								$choiceObj = new Choice();
								$choiceObj->updateImageChoice($imageChoiceId, $nameDatabase, $pathDatabase);	
							}
							else{
								$result = "Size";
							}
						}
						else{
							$result = "Extention";
						}
					}
				} 
				else { // Upload image choice
					$validationObj = new imageValidation();
					// check if the file is image
					$result = $validationObj->fileExtensionCheck($name);
					if($result == null){
						// check the size of the image 
						$result = $validationObj->fileSizeCheck($size);
						if($result == null){
							// convert chars to save to directory
							//$nameDir = mb_convert_encoding($name, 'GREEK', 'UTF-8');	
							$pathDir = "../images/$surveyId/$questionId/".$name;
							// Upload image in the directory
							move_uploaded_file($image, $pathDir);
							// convert chars for database
							$nameDatabase = mb_convert_encoding($name, 'HTML-ENTITIES', 'UTF-8');
							$nameDatabase = html_entity_decode($nameDatabase);
							$pathDatabase = "../images/$surveyId/$questionId/".$nameDatabase;
							$choiceObj = new Choice();
							$choiceObj->createImageChoice($questionId, $nameDatabase, $pathDatabase);
							$imageChoiceIds[$key] = (string)mysql_insert_id();
						}
						else{
							$result = "Size";
						}
					}
					else{
						$result = "Extention";
					}
				}
			}	
		}
	}
	if($result == null){
		$response['question_id'] = $questionId;
		$response['image_choices'] = $imageChoiceIds;
		$response['choices'] = $choiceIds;
	}
	else{
		$response['result'] = $result;
		$response['question_id'] = $questionId;
		$response['image_choices'] = $imageChoiceIds;
	}
	print json_encode($response);
?>