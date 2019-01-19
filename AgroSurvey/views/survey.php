<?php 
	require("../models/survey.php");
	require("../models/question.php");
	require("../models/choice.php");
	require("../models/response.php");
	require("../models/userAnswer.php");

	$surveyId = "";
	$surveyTitle = null;
	$survey = null;
	$expiredDate = "";
	$userIP = $_SERVER['REMOTE_ADDR'];
	$error_message = "";
	$questions = null;
	
	if(!empty($_GET['id'])){
		$surveyObj = new Survey();
		$survey = $surveyObj->getSurveysByHash($_GET['id']);
			
		if($survey){
			$surveyId = $survey->survey_id;
			$expiredDate = date('Y-m-d',strtotime($survey->endDate));
			$now = (date('Y-m-d'));
			$surveyTitle = $survey->name;
			$questionObj = new Question();
			$questions = $questionObj->getQuestionsBySurveyId($surveyId);
					
			if($expiredDate < $now){
				// Redirection if the end date of the survey has passed
				header("Location:messages/expiredSurvey.php");
			}
					
			$query = "SELECT response_id FROM responses WHERE ip_address='".$userIP."' AND survey_id='".$surveyId."'";
			$result = mysql_query($query);

			if(mysql_num_rows($result)>0){ 
				// Redirection if the user has already answered this survey
				header("Location:messages/sorrySurvey.php");
			}
		}	
		else{	
			// Redirection if something wrong happened
			header("Location:messages/lostSurvey.php");
		}	
	};
	
	if (isset($_POST['submitUserResponse'])) { // Form has been submitted
		$answers = array();
		$answers = $_POST['answers'];
		if (!empty($answers) && sizeof($answers) == sizeof($questions)) {
			if (empty($error_message)) {
				$responseObj = new Response();
				$responseObj->createResponse($surveyId, $userIP);	
				$responseId = mysql_insert_id();
				
				if ($responseId) {
					foreach ($_POST['answers'] as $question_id =>$value) {
						$responseId;
						$question_id;
						$value;
		
						$userAnswerObj = new UserAnswer();
						$userAnswerObj->createAnswer($responseId, $question_id, $value);
					}
					// Redirection when the user answers the survey
					header("Location:messages/thankYouSurvey.php");
				}
			}
		} 
		else { 
			//There are answers that user didn't answered
			$error_message = "All questions are required.";
		}
	}
?>

<!DOCTYPE html>
<html lang="en" >
	<head>
		<title>AgroSurvey</title>
		<meta charset="UTF-8">
		<!-- Bootstrap libraries -->
		<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css"> 
		<link rel='stylesheet prefetch' type="text/css" href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css'>
		<!-- Css file external link -->
		<link rel="stylesheet" type="text/css" href="../css/main.css">
		<!-- jQuery library -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		<!-- Javascript file external link -->
		<script type="text/javascript" src="../js/myScript.js"> </script>	
	</head>
	<body>
		<div id="header">
			<div class="row">
				<div class="col-md-6 col-md-offset-3 text-center title">
					<h1 id="logo-text">AgroSurvey</h1>
				</div>
			</div>
		</div>
		<div class="publishedSurvey">
			<div class="container">
			
				<div class="row title">
					<div class="col-md-6 col-md-offset-3 text-center"> <?= $surveyTitle ?> </div>
				</div>
				
				<div class="row error">
					<?php if ($error_message) : ?>
						<div class="col-xs-6 col-xs-offset-3"> <?= $error_message ?> </div>
					<?php endif; ?>
				</div>
				
				<form name="published_Survey"  action="survey.php<?= '?id=' .$survey->hash ?>" method="post">
					<?php if ($questions) : ?>
						<?php $choiceObj = new Choice(); ?>
						<?php foreach ($questions as $question) : $choices = $choiceObj->getChoicesByQuestionId($question->question_id); $imageChoices = $choiceObj->getImageChoicesByQuestionId($question->question_id);?>
							<section class="question">
								<div class="question-title"> <?= $question->question_text ?> </div>
								<!-- Single choice -->
								<?php if($question->question_type == '1') : ?>
									<?php if ($choices) : ?>
										<?php foreach ($choices as $choice) : ?>
										<div class="radio">
											<label><input type="radio" name="answers[<?= $question->question_id ?>]" value="<?= $choice->choice_id ?>" <?php if (isset($_POST['answers'][$question->question_id])) { if ($_POST['answers'][$question->question_id] == $choice->question_id) { echo 'checked'; } } ?>> <?= $choice->choice_text ?> </label>
										</div>
										<?php endforeach; ?>
									<?php endif; ?>
								<?php endif; ?>
								
								<!-- Image choice -->
								<?php if($question->question_type == '2') : ?>
									<?php if ($imageChoices) : ?>
										<?php foreach ($imageChoices as $imageChoice) : ?>
										<div class="radio">
											<label><input type="radio" name="answers[<?= $question->question_id ?>]" value="<?= $imageChoice->image_id ?>" <?php if (isset($_POST['answers'][$question->question_id])) { if ($_POST['answers'][$question->question_id] == $imageChoice->question_id) { echo 'checked'; } } ?>> <img src="<?= $imageChoice->image_path?>" height="300" width="450"> </label>
										</div>
										<?php endforeach; ?>
									<?php endif; ?>
								<?php endif; ?>
							</section>
						<?php endforeach; ?>
					<?php endif; ?>
					<input type="hidden" name="survey_id" value="<?= $surveyId ?>">
					<input type="submit" name="submitUserResponse" value="Done" class="btn btn-primary">
				</form>	
			</div>
		</div>
	</body>
</html>