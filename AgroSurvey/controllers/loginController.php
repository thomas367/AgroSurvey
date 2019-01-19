<?php
	require("../models/users.php");	
	
	//Check if there are empty fields in the form.
	if(!$_POST['username'] || !$_POST['password']){
		$status = "Empty";
	}
	else{
		
		//Username and password sent from the form
		$username=$_POST['username'];
		$password=$_POST['password'];
		mysql_real_escape_string($username);
		mysql_real_escape_string($password);
		
		$usersObj = new User();
		
		$result=$usersObj->getAccountsFromDAO();
		$checkstate=false;
		
		for($i=0; $i<count($result); $i++){
		
			$singleAccount=$result[$i];
			if(($singleAccount[1]==$username) && ($singleAccount[2]==$password)){
				$checkstate=true;
				
				session_start();
				$_SESSION['userID'] = $singleAccount[0];
				$_SESSION['username'] = $singleAccount[1] ;		
			}
		}
		
		if($checkstate==true){
			$status = "Success";
		}
		else{
			//Wrong username or password
			$status = "Wrong";
		}
	}
	$response['status'] = $status;
	print json_encode($response);
?>