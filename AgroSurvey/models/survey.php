<?php
	require("../dbConfig/databaseConnection.php");
	
	
	class Survey {
	
		//Data members
		private $survey_id;
		private $name;
		private $startDate;
		private $endDate;
		private $hash;
		private $user_id;
		
		//Constructor
		function __construct(){
			$this->survey_id="";
			$this->name="";
			$this->startDate="";
			$this->endDate="";
			$this->hash="";
			$this->user_id="";
		}
		
		//Set data members
		function setDataMembers($surveyId, $surveyName, $stDate, $ndDate, $hashLink, $userId){
			$this->survey_id = $surveyId;
			$this->name = $surveyName;
			$this->startDate = $stDate;
			$this->endDate = $ndDate;
			$this->hash = $hashLink;
			$this->user_id = $userId;
		}	
		
		//Gets all the data from the survey tables and
		//returns them into an array of objects
		public function getSurveys($connectedUser=0){
			$query = "SELECT * FROM surveys WHERE user_id='".$connectedUser."'";
			$result = mysql_query($query);
			$array_result = array();
			while($row = mysql_fetch_object($result)){
				$array_result[] = $row; 
			}
			return
				$array_result;
		}
		
		//Get a survey from the table based on the survey_id
		public function getSurveysByID($id=0){
			$query = "SELECT * FROM surveys WHERE survey_id='".$id."'";
			$result = mysql_query($query);
			$array_result = array();
			while($row = mysql_fetch_object($result)){
				$array_result[] = $row;
			}
			return	
				array_shift($array_result);
		}
		
		//Get a survey from the table based on the hash
		public function getSurveysByHash($hash){
			$query = "SELECT * FROM surveys WHERE hash='".$hash."'";
			$result = mysql_query($query);
			$array_result = array();
			while($row = mysql_fetch_object($result)){
				$array_result[] = $row;
			}
			return	
				array_shift($array_result);
		}
		
		//Update a survey in the survey table
		public function updateSurvey($surveyId, $surveyName, $stDate, $ndDate){	
			$query = "UPDATE surveys SET name='".$surveyName."', startDate='".$stDate."', endDate='".$ndDate."' WHERE surveys.survey_id='".$surveyId."'"; 
			mysql_query($query);
		}
		
		//Update a survey with responses in the survey table
		public function updateSurveyWithResponses($surveyId, $ndDate){
			$query = "UPDATE surveys SET endDate='".$ndDate."' WHERE surveys.survey_id='".$surveyId."'"; 
			mysql_query($query);
		}
		
		//Create survey function
		public function createSurvey($surveyName, $stDate, $ndDate,$userId){
			$hash = $this->checkIntegrity();
			$query = "INSERT INTO surveys(survey_id, name, startDate, endDate, hash, user_id)
						VALUES(null, '".$surveyName."', '".$stDate."', '".$ndDate."', '".$hash."', '".$userId."');"; 
			mysql_query($query);
		}
		
		//Delete survey function
		public function deleteSurvey($surveyId){
			$query = "DELETE FROM surveys WHERE survey_id='".$surveyId."'";
			mysql_query($query);
		}

		//Create a hash for the survey
		public function createHash(){
			$characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$characters_length = strlen($characters);
			$randomHash = "";
			for ($i = 0; $i < 15; $i++) {
				$randomHash .= $characters[rand(0, $characters_length - 1)];
			}
			return $randomHash;	
		}
		
		//Check the integrity of the hash
		private function checkIntegrity(){
			$randomHash = $this->createHash();
			$query = "SELECT * FROM surveys WHERE hash='".$randomHash."'";
			$result = mysql_query($query);
			while(mysql_num_rows($result) > 0){
				$this->checkIntegrity();
			}
			return $randomHash;
		}
	}
?>