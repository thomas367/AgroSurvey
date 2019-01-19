<?php 
	require("../dbConfig/databaseConnection.php");

	class Response {

		//Data members
		private $response_id;
		private $survey_id;
		private $ip_address;

		//Constructor
		function __construct(){
			$this->response_id = "";
			$this->survey_id = "";
			$this->ip_address = "";
		}
	
		//Set data members
		function setDataMembers($responseId, $surveyId, $ipAddress){
			$this->response_id = $responseId;
			$this->survey_id = $surveyId;
			$this->ip_address = $ipAddress;
			
		}
		
		// Creates a response of a user for the survey
		public function createResponse($surveyId, $ipAddress){
			$query = "INSERT INTO responses(response_id, survey_id, ip_address)
						VALUES (null, '".$surveyId."', '".$ipAddress."');";
			mysql_query($query);
		}
		
		// Get all the responses of a survey
		public function getResponsesBySurveyId($surveyId){
			$query = "SELECT * FROM responses WHERE survey_id='".$surveyId."'";
			$result = mysql_query($query);
			$array_result = array();
			while($row = mysql_fetch_object($result)){
				$array_result[] = $row; 
			}
			return
				$array_result;
		}
	}
?>