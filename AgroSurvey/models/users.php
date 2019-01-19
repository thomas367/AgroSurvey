<?php

	require("../dbConfig/databaseConnection.php");
	
	
	class User{
	
		//Data members
		private $username;
		private $password;
		private $name;
		private $surname;	
		
		//Constructor
		function __construct(){
			$this->username="";
			$this->password="";
			$this->name="";
			$this->surname="";
		}
		
		//Set data members
		function SetDataMember($usernm, $passwd, $name, $surname){
			$this->username=$usernm;
			$this->password=$passwd;
			$this->name=$name;
			$this->surname=$surname;
		}
		
		//Get all the accounts from the database and returns
		// an array of arrays back to the caller
		public function getAccountsFromDAO(){
			$query="SELECT * FROM users";
			$result=mysql_query($query);
			$n=0;
			$accountArray=array();
			
			while($account=mysql_fetch_array($result)){
				$i=0;
				$singleAccountArray[$i]=$account['user_id'];
				$singleAccountArray[$i+1]=$account['username'];
				$singleAccountArray[$i+2]=$account['password'];
				$singleAccountArray[$i+3]=$account['name'];
				$singleAccountArray[$i+4]=$account['surname'];
				$accountArray[$n]=$singleAccountArray;
				$n++;
			}
			return $accountArray;
		}
	}
?>