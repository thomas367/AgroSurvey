<?php
	require("../models/users.php");	
	
	//Check if there are empty fields in the form.
	if(!$_POST['username'] || !$_POST['password'] || !$_POST['repassword'] || !$_POST['name'] || !$_POST['surname']){
		$status = "Empty";
	}
	else{
		
		//Data sent from the form
		$username=$_POST['username'];
		$password=$_POST['password'];
		$repassword=$_POST['repassword'];
		$name=$_POST['name'];
		$surname=$_POST['surname'];
		
		mysql_real_escape_string($username);
		mysql_real_escape_string($password);
		mysql_real_escape_string($repassword);
		mysql_real_escape_string($name);
		mysql_real_escape_string($surname);
			
		if($password == $repassword){
			$userObj = new User();
			$result = $userObj->getAccountsFromDAO();
			$checkstate = false;
					
			for($i=0; $i<count($result); $i++){
				$singleAccount=$result[$i];
				if($singleAccount[1]==$username){
					$checkstate = true;			
				}
			}
			if($checkstate==true){
				// Username already exists
				$status = "Error";
			}	
			else{	  
				$query = "INSERT INTO users(user_id, username, password, name, surname)
					VALUES (null, '$username', '$password', '$name', '$surname');";
				mysql_query($query);
				$userId = mysql_insert_id();
				
				session_start();
				$_SESSION['userID'] = $userId;
				$_SESSION['username'] = $username;

				$status = "Success";		
			}
		}
		else{
			// Password and repassword mismatch
			$status = "Wrong";
		}
	}
	$response['status'] = $status;
	print json_encode($response);
?>