<!DOCTYPE html>
<html lang="en" >
	<head>
		<meta charset="UTF-8">
		<title>AgroSurvey</title>
		<!-- Bootstrap libraries -->
		<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css"> 
		<link rel='stylesheet prefetch' type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<!-- Css external link -->
		<link rel="stylesheet" type="text/css" href="../css/style.css">
		<!-- jQuery library -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		<!-- Sweet Alert library -->
		<script src="https://cdn.jsdelivr.net/npm/sweetalert2@7.25.0/dist/sweetalert2.all.min.js"></script>
		<!-- Javascript external link -->
		<script type="text/javascript" src="../js/myScript.js"> </script>
	</head>
	<body>
		<div class="container main_register">
			<div class="row">
				<div class="col-md-6 col-md-offset-3 text-center title">
					<h1>AgroSurvey</h1>
					<div class="bar"></div> 
				</div>
			</div>
			<div class="row"> 
				<div class="col-md-6 col-md-offset-3 form">
					<h2>Signup</h2>
					<form action="../controllers/registerController.php" method="post" onSubmit="return registerAction(this);" >
						<input type="text" name="username" placeholder="username"/><br/>
						<input type="password" name="password" placeholder="password"/><br/>
						<input type="password" name="repassword" placeholder="repeat password"/><br/>
						<input type="text" name="name" placeholder="name"/><br/>
						<input type="text" name="surname" placeholder="surname"/>
						<input id="signup" type="submit" value="Signup">
					</form>
					<h4>Do you have an account? Just login!<span><input id="login" type="button" onClick="location.href='../index.php'" value="Login"></span></h4>
				</div>
			</div>
		</div>
	</body>
</html>
