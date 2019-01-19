<?php 
	session_start();
	if(!(isset($_SESSION['userID'])))
		header("Location:../index.php");
?>

<?php
	require("../models/survey.php");
	require("../models/question.php");
	require("../models/response.php");
	require("../models/choice.php");

	$connectedUser = $_SESSION['userID'];
	$surveyId = '';
	$survey = null;
	$questionsIds = array();
	$questions = null;
	$responses = null;

	if (!empty($_GET['sid'])) {
		if (is_numeric($_GET['sid'])) { 
			$surveyObj = new Survey();
			$survey = $surveyObj->getSurveysByID($_GET['sid']); 

			if ($survey) {
				$confirmConnectedUser = $survey->user_id;
				if($connectedUser == $confirmConnectedUser){
					$surveyId = $survey->survey_id;
					$questionObj = new Question();
					$questions = $questionObj->getQuestionsBySurveyId($surveyId); 
					foreach ($questions as $question) {
						$questionsIds[] = $question->question_id;
					}
					$responseObj = new Response();
					$responses = $responseObj->getResponsesBySurveyId($surveyId);
				}
				else{
					header("Location:cPanel.php");
				}
			} 
			else {
				header("Location:cPanel.php");
			}
		} 
		else {
			header("Location:cPanel.php");
		}
	};
?>

<html>
	<head>
		<title>AgroSurvey</title>
		<meta charset="UTF-8">
		<!-- Bootstrap libraries -->
		<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css"> 
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" media="all"/>
		<!-- Css external link -->
		<link rel="stylesheet" type="text/css" href="../css/main.css">
		<!-- Google chart API -->
		<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
		<!-- jQuery library -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		<!-- Javascript external link -->
		<script type="text/javascript" src="../js/myScript.js"> </script>
	</head>
	<body onload='report(<?= json_encode($questionsIds) ?>)'>
		<div id="header">
			<div class="row">
				<div class="col-md-6 col-md-offset-3 text-center title">
					<h1 id="logo-text">AgroSurvey</h1>
				</div>
			</div>
		</div>
		<!-- Survey name -->
		<div class="container">
			<div class="title_panel clearfix">
				<h2>Statistics</h2>
				<span class="pull-right"><i><?php echo $survey->name; ?></i> &#8226; <a href="cPanel.php">Control Panel</a></span>
			</div>
			<!--Print a report -->
			<div class="row print-button">
				<div class="col-xs-12 text-left">
					<div class="btn btn-default" onclick="window.print();"><span class="glyphicon glyphicon-print"></span>Print</div>
				</div>
			</div>		
			<!-- Questions with charts -->
			<div class="charts">
				<?php if ($responses) : ?>
					<div class="form-group center">
						
						<?php foreach ($questions as $question) : ?>
							<!-- Single choice chart -->
							<?php if($question->question_type == '1') : ?>
								<span class="col-xs-6 col-xs-offset-3 text-center"><?= $question->question_text ?></span>
								<div class="chart">
									<div id="pieChart-<?= $question->question_id ?>" class="col-xs-6 col-xs-offset-3 chart pull-center"></div>
								</div>
							<?php endif; ?>
							
							<!-- Image choice chart -->
							<?php if($question->question_type == '2') : ?>
								<div class="">
									<span class="col-xs-6 col-xs-offset-3 text-center"><?= $question->question_text ?></span>
									<div class="chart">
										<div id="pieChart-<?= $question->question_id ?>" class="col-xs-6 col-xs-offset-3 chart pull-center"></div>
									</div>
								</div>
							<?php endif; ?>
							
						<?php endforeach; ?>
					</div>
				<?php else : ?>
				<p>No responses for this survey yet.</p>	
				<?php endif; ?>
			</div>
		</div>			
	</body>
</html>			