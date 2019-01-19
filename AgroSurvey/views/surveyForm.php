<?php 
	session_start();
	if(!(isset($_SESSION['userID']))){
		header("Location:../index.php");
	}


	require("../models/survey.php");
	require("../models/question.php");
	require("../models/choice.php");
	require("../models/response.php");
	
	$connectedUser = $_SESSION['userID'];
	$surveyId = '';
	$survey = null;
	$survey_title = null;
	$survey_startDate = null;
	$survey_endDate = null;
	$questions = null;
	$error_message = '';
	$response = null;

	if(!empty($_GET['sid'])){ 
		if(is_numeric($_GET['sid'])){
			$responseObj = new Response();
			$response = $responseObj->getResponsesBySurveyId($_GET['sid']);
			// Check if the survey has responses
			if($response){
				$surveyObj = new Survey();
				$survey = $surveyObj->getSurveysByID($_GET['sid']); 
				
				// Check if the survey exists
				if($survey){
					$confirmConnectedUser = $survey->user_id;
					if($connectedUser == $confirmConnectedUser){
						$surveyId = $survey->survey_id;
						if (!isset($_POST['submit_survey_form'])) { 
							$survey_title = $survey->name;
							$survey_startDate = $survey->startDate;
							$survey_endDate = $survey->endDate;
						}
					}
					else{
						header("Location:cPanel.php");
					}
				}
				else{
					header("Location:cPanel.php");
				}
			}
			else{
				$surveyObj = new Survey();
				$survey = $surveyObj->getSurveysByID($_GET['sid']); 
				
				// Check if the survey exists
				if($survey){
					$confirmConnectedUser = $survey->user_id;
					if($connectedUser == $confirmConnectedUser){
						$surveyId = $survey->survey_id;
						if (!isset($_POST['submit_survey_form'])) { 
							$survey_title = $survey->name;
							$survey_startDate = $survey->startDate;
							$survey_endDate = $survey->endDate;
							$questionObj = new Question();
							$questions = $questionObj->getQuestionsBySurveyId($surveyId);       
						}
					}
					else{
						header("Location:cPanel.php");
					}
				}
				else{
					header("Location:cPanel.php");
				}
			}
		}
		else{
			header("Location:cPanel.php");
		}		
	};
	if($response){ // Update survey with responses
		if (isset($_POST['submit_survey_form'])) { // Form has been submitted
			$survey_title = trim($_POST['survey_title']);
			$survey_startDate = date('Y/m/d', strtotime($_POST['start-date']));
			$survey_endDate = date('Y/m/d', strtotime($_POST['end-date']));
			$now = (date('Y/m/d'));
			
			if(!empty($surveyId)){
				if($survey_endDate < $now){
					$error_message = "End date can't be before current date!!!";
				}
				else if($survey_endDate < $survey_startDate){
					$error_message = "You can not set the end date before start date!!!";
				}
				else{
					$surveyObj = new Survey();
					$surveyObj->updateSurveyWithResponses($surveyId, $survey_endDate);
					header("Location:cPanel.php");
				}
			}
		}	
	}
	else{ //Create or update survey without responses
		if (isset($_POST['submit_survey_form'])) { // Form has been submitted
			
			$survey_title = trim($_POST['survey_title']);
			mysql_real_escape_string($survey_title);
			$survey_startDate = date('Y/m/d', strtotime($_POST['start-date']));
			$survey_endDate = date('Y/m/d', strtotime($_POST['end-date']));
			$now = (date('Y/m/d'));
			
			if (!empty($surveyId)) { // Edit Survey	
				if($survey_startDate < $now || $survey_endDate < $now){
					$error_message = "Start date and end date can't be before current date!!!";
				}
				else if($survey_endDate < $survey_startDate || $survey_startDate == $survey_endDate){
					$error_message = "Start date have to be before end date!!!";
				}
				else{
					$surveyObj = new Survey();
					$surveyObj->updateSurvey($surveyId, $survey_title, $survey_startDate, $survey_endDate);
					header("Location:cPanel.php");
				}
			} 
			else { // Create Survey
				if($survey_startDate < $now || $survey_endDate < $now){
					$error_message = "Start date and end date can't be before current date!!!";
				}
				else if($survey_endDate < $survey_startDate || $survey_startDate == $survey_endDate){
					$error_message = "Start date have to be before end date!!!";
				}
				else{
					$surveyObj = new Survey();
					$surveyObj->createSurvey($survey_title, $survey_startDate, $survey_endDate, $connectedUser);
					$surveyId = mysql_insert_id(); 
					/*	Create a folder for the current survey
						based on the surveyId.
						Each survey has a unique folder. 
					*/
					if ( !file_exists("../images/$surveyId") ) {
						mkdir("../images/$surveyId", 0777, true);
					}
				}	
			}
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
		<link rel='stylesheet prefetch' type="text/css" href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'>
		<!-- Css external link -->
		<link rel="stylesheet" type="text/css" href="../css/main.css">
		<!-- jQuery library -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		<!-- Sweet Alert library -->
		<script src="https://cdn.jsdelivr.net/npm/sweetalert2@7.25.0/dist/sweetalert2.all.min.js"></script>
		<!-- Javascript external link -->
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
		<div class="container">
			<form id="survey_creation_form" name="survey_creation_form" action="surveyForm.php<?= ($surveyId ? '?sid='.$surveyId : '') ?>" method="post">
				<span class="info pull-right"><i>All fields marked with an asterisk <span class="glyphicon glyphicon-asterisk"></span> are required.</i></span>
				
				<!-- Survey Title -->
				<div class="form-group">
					<label>Title: <span class="glyphicon glyphicon-asterisk"></span></label>
					<input type="text" name="survey_title" class="form-control" placeholder="Enter the survey title" autofocus required <?php if($response) :?> readonly <?php endif;?> value="<?= htmlentities($survey_title) ?>">
				</div>
				
				<!-- Error message for wrong dates -->	
				<?php if ($error_message) : ?>
					<div class="errorMessage">
						<div class="col-xs-6 col-xs-offset-3"><?= $error_message ?></div>
					</div>
				<?php endif; ?>
				
				
				<!-- Start date & End date --> 
				<div class="form-group row">
					<div class="start-date col-xs-4">
						<label>Start date: <span class="glyphicon glyphicon-asterisk"></span></label>
						<input type="text" id="datePicker" name="start-date" placeholder="ηη/μμ/εεεε" autofocus required <?php if($response) :?> readonly <?php endif;?> value="<?= htmlentities($survey_startDate) ?>" onmouseover="(this.type='date')"/> 
					</div>
					<div class="end-date col-xs-offset-4 col-xs-4">
						<label>End date: <span class="glyphicon glyphicon-asterisk"></span></label>
						<input type="text" id="datePicker" name="end-date" placeholder="ηη/μμ/εεεε" autofocus required value="<?= htmlentities($survey_endDate) ?>" onmouseover="(this.type='date')"/> 
					</div>
				</div>	
				<input type="submit" id="submit_survey_form" name="submit_survey_form" class="hidden">
			</form>

			<div id="questions_container">
				<?php if ($questions) : ?>
					<?php $choiceObj = new Choice(); ?>	
					<?php foreach ($questions as $question) : $choices = $choiceObj->getChoicesByQuestionId($question->question_id);  $imageChoices = $choiceObj->getImageChoicesByQuestionId($question->question_id);  ?>
					<section class="question_template ui-state-default" id="<?= $question->question_id ?>">
						<form class="question_creation_form" name="question_creation_form" action="../controllers/submitQuestionController.php" method="post" enctype="multipart/form-data" onSubmit="return saveQuestion(this);">
							<a href="#" class="delete_question">
								<span class="glyphicon glyphicon-remove "></span>
							</a>

							<div class="row">	
								<!--  Question Title     -->
								<div class="form-group col-xs-7">
									<label>Question Title: <span class="glyphicon glyphicon-asterisk"></span></label>
									<input type="text" name="question_title" id="question_title" class="question_title form-control" placeholder="Enter the question title" value="<?= $question->question_text ?>" required>
								</div>

								<!-- Question Type -->
								<div class="question_type form-group col-xs-3 col-xs-offset-2">
									<label for="select_question_type_<?= $question->question_id ?>">Question Type: <span class="glyphicon glyphicon-asterisk"></span></label>
									<select id="select_question_type_<?= $question->question_id ?>" class="form-control select_question_type" name="select_question_type" required>
										<option value="">Select Question Type</option>
										<option value="1" <?= ($question->question_type == '1') ? 'selected' : ''; ?>>Multiple Choice</option>
										<option value="2" <?= ($question->question_type == '2') ? 'selected' : ''; ?>>Multiple Choice with images</option>
									</select>
								</div>
							</div>
							
							<!--  Choices    -->
							<div class="choices">	
								<!-- Single Choices -->
								<?php if($question->question_type == '1') : ?>
									<?php if ($choices) : $last = end($choices); ?>
										<div class="">
											<?php foreach ($choices as $key => $choice) : ?>	
												<div class="form-group row">
													<div class="col-xs-4">
														<input type="text" name="choices[]" class="form-control" placeholder="Choice <?= ($key + 1) ?>" value="<?= $choice->choice_text ?>" required>
														<input type="hidden" id="choice_id" name="choice_ids[]" value="<?= $choice->choice_id ?>">
													</div>
													<div class="col-xs-1">
														<?php if (count($choices) > 2) : ?><span class="glyphicon glyphicon-minus delete_choice <?php if(count($choices) < 3) : ?>hidden<?php endif; ?>"></span><?php endif; ?>
														<?php if ($choice == $last) : ?><span class="glyphicon glyphicon-plus add_choice"></span><?php endif; ?>
													</div>
												</div>
											<?php endforeach; ?>
										</div>
									<?php endif; ?>
								<?php endif; ?>	
												
								<!-- Image Choices -->  
								<?php if ($question->question_type == '2') : ?>
									<?php if ($imageChoices) : $last = end($imageChoices); ?>
										<div class="">
											<?php foreach ($imageChoices as $key => $imageChoice) : ?>
												<div class="form-group row">
													<div class="col-xs-2">
														<div class="img-wrap">
															<span class="close">&times;</span>
															<img src="<?= $imageChoice->image_path ?>" height="120" width="170">
														</div>
													</div>
													<div class="col-xs-4 hidden">
														<input type="file" name="image_choices[]" class="form-control" accept="image/*" value="<?= $imageChoice->image_name ?>" onchange="previewImage(this)">
														<img src"#" id="preview" alt="">
														<input type="hidden" id="image_choice_id" name="image_choice_ids[]" value="<?= $imageChoice->image_id ?>">
													</div>
													<div class="col-xs-1">
														<?php if (count($imageChoices) > 2) : ?><span class="glyphicon glyphicon-minus delete_choice <?php if(count($imageChoices) < 3) : ?>hidden<?php endif; ?>"></span><?php endif; ?>
														<?php if ($imageChoice == $last) : ?><span class="glyphicon glyphicon-plus add_choice"></span><?php endif; ?>
													</div>
												</div>
											<?php endforeach; ?>
										</div>
									<?php endif; ?>
								<?php endif; ?>		
							</div>
							<input type="hidden" id="question_id" name="question_id" value="<?= $question->question_id ?>">
							<input type="hidden" id="survey_id" name="surveyId" value="<?= $surveyId ?>">
							<input type="submit" id="submit_question_form" name="submit_question_form" value="Save" class="btn btn-default question_save_button">
						</form>
					</section>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>

			<?php if ((!empty($surveyId)) && (empty($response))) : ?>
				<div id="add_question_button" class="btn btn-default">+ Add Question</div>
			<?php endif; ?>

			<div><label for="submit_survey_form" id="save_survey_button" class="btn btn-primary"><?= !empty(($surveyId)) ? 'Done' : 'Create' ?></label></div>

			<!-- Add Question default question template -->
			<div class="hidden">
				<div class="question_template_container">
					<section class="question_template ui-state-default" id="">
						<form class="question_creation_form" name="question_creation_form" action="../controllers/submitQuestionController.php" method="post" enctype="multipart/form-data" onSubmit="return saveQuestion(this);">
							<a href="#" class="delete_question hidden">
								<span class="glyphicon glyphicon-remove"></span>
							</a>

							<div class="row">
								<!-- Question Title -->
								<div class="form-group col-xs-7">
									<label>Question Title: <span class="glyphicon glyphicon-asterisk"></span></label>
									<input type="text" name="question_title" id="question_title" class="question_title form-control" placeholder="Enter the question title" value="" required>
								</div>
								<!-- Question Type -->
								<div class="question_type form-group col-xs-3 col-xs-offset-2">
									<label for="">Question Type: <span class="glyphicon glyphicon-asterisk"></span></label>

									<select id="" class="form-control select_question_type" name="select_question_type" required>
										<option value="">Select Question Type</option>
										<option value="1">Multiple Choice</option>
										<option value="2">Multiple Choice with images</option>
									</select>
								</div>
								
							</div>
							<div class="choices"></div>
							<input type="hidden" id="question_id" name="question_id" value="">
							<input type="hidden" id="survey_id" name="surveyId" value="<?= $surveyId ?>">
							<input type="submit" id="submit_question_form" name="submit_question_form" value="Save" class="btn btn-default question_save_button">
						</form>
					</section>
				</div>
			</div>
		</div>
	</body>
</html>							