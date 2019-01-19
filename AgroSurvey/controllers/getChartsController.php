<?php 
	session_start();
	if(!(isset($_SESSION['userID'])))
		header("Location:../index.php");
?>

<?php
	require("../dbConfig/databaseConnection.php");
	require("../models/question.php");
	
	$questionIds = $_POST['question_ids'];
	$chartTable = array();
	$chartData = "";
	$response = array();

	if (!empty($questionIds)) {
		foreach ($questionIds as $questionId) {
			$questionObj = new Question();
			$question = $questionObj->getQuestionByQuestionId($questionId);
			$question_type = $question->question_type;
			if($question_type == 1){
				$query  = "SELECT choices.choice_text, COUNT(useranswers.value) FROM useranswers 
							JOIN choices ON useranswers.value = choices.choice_id WHERE useranswers.question_id='".$questionId."' GROUP BY useranswers.value";
				$result = mysql_query($query);
				
				$chartTable['cols'] = array(
					//Labels for the chart, these represent the column titles
					array('id' => '', 'label' => 'Choices Labels', 'type' => 'string'),
					array('id' => '', 'label' => 'Count', 'type' => 'number')
				);

				$rows = array();
				while($row = mysql_fetch_assoc($result)){
					$temp = array();
					$temp[] = array('v' => (string) $row['choice_text']);
					$temp[] = array('v' => (float) $row['COUNT(useranswers.value)']);
					$rows[] = array('c' => $temp);
				}
				mysql_free_result($result);
				$chartTable['rows'] = $rows;
			}
			else if($question_type == 2){
				$query  = "SELECT imagechoices.image_name, COUNT(useranswers.value) FROM useranswers 
							JOIN imagechoices ON useranswers.value = imagechoices.image_id WHERE useranswers.question_id='".$questionId."' GROUP BY useranswers.value";
				$result = mysql_query($query);
				
				$chartTable['cols'] = array(
					//Labels for the chart, these represent the column titles
					array('id' => '', 'label' => 'Choices Labels', 'type' => 'string'),
					array('id' => '', 'label' => 'Count', 'type' => 'number')
				);

				$rows = array();
				while($row = mysql_fetch_assoc($result)){
					$temp = array();
					$temp[] = array('v' => (string) $row['image_name']);
					$temp[] = array('v' => (float) $row['COUNT(useranswers.value)']);
					$rows[] = array('c' => $temp);
				}
				mysql_free_result($result);
				$chartTable['rows'] = $rows;
			}
			
			$chartData = json_encode($chartTable);
			$response[$questionId]['chartData'] = $chartData;
		}
	}
	print json_encode($response);
?>