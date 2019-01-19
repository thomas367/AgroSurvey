<?php 
	session_start();
	if(!(isset($_SESSION['userID'])))
		header("Location:../index.php");
?>
<?php 
	// To set up in a server we just change http://localhost/ with website domain.
	defined('SITE_URL') ? null : define('SITE_URL', 'http://localhost/');
?>

<?php 
		require("../models/survey.php");
		
		$connectedUser = $_SESSION['userID'];
		$surveyObj = new Survey();
		//Get all the surveys of the connected user
		$surveys = $surveyObj->getSurveys($connectedUser);
?>

<!DOCTYPE html>
<html lang="en" >
	<head>
		<meta charset="UTF-8">
		<title>AgroSurvey</title>
		<!-- Bootstrap libraries -->
		<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css"> 
		<link rel='stylesheet prefetch' type="text/css" href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'>
		<!-- Css external link -->
		<link rel="stylesheet" type="text/css" href="../css/main.css">
		<!-- jQuery library -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		<!-- Sweet Alert library -->
		<script src="https://cdn.jsdelivr.net/npm/sweetalert2@7.25.0/dist/sweetalert2.all.min.js"></script>
		<!-- Clipboard library -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/1.6.0/clipboard.min.js"></script>
		<!-- Toastr libraries -->
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/css/toastr.min.css" />
		<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/js/toastr.min.js"></script>
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
			<div class="title_panel clearfix">
				<h2>Control Panel</h2>
				<span class="pull-right">Welcome<b><i> <?php echo $_SESSION['username']; ?> </i></b><a href="../models/logout.php" class="btn btn-primary">Logout</a>
			</div>
			<?php if($surveys) : ?>
			<table class="table table-bordered table-hover surveys-table">
				<thead>
					<tr>
						<th>Title</th>
						<th>Publish</th>
						<th>Edit</th>
						<th>Report</th>
						<th>Delete</th>
					</tr>	
				</thead>		
				<tbody>
					<?php foreach($surveys as $survey): ?>
					<tr class="text-center">
						<td id="surveyTitle"><?php echo $survey->name ?></td>
						<td><a href="#" class="publishLink" data-clipboard-text="<?= SITE_URL . "ptyxiaki/views/survey.php?id=" . $survey->hash ?>" data-survey-name="<?= $survey->name ?>"><span class="glyphicon glyphicon-share"></span></a></td>
						<td><a href="surveyForm.php?sid=<?= $survey->survey_id ?>"><span class="glyphicon glyphicon-edit"></span></a></td>
						<td><a href="reportForm.php?sid=<?= $survey->survey_id ?>"><span class="glyphicon glyphicon-stats"></span></a></td>
						<td><a href="#" class="delete_survey" data-survey-id="<?= $survey->survey_id ?>" data-survey-name="<?= $survey->name ?>"> <span class="glyphicon glyphicon-trash"></span></a></td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
			<?php else : ?>
			<p>You have not created any surveys yet.</p>
			<?php endif; ?>
			<a href="surveyForm.php" class="btn btn-primary">Create</a>
		</div>
	</body>
</html>