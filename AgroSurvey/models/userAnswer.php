<?php 
	require("../dbConfig/databaseConnection.php");

	class UserAnswer {

		//Data members
		private $userAnswer_id;
		private $response_id;
		private $question_id;
		private $value;
		
		//Constructor
		function __construct(){
			$this->userAnswer_id = "";
			$this->response_id = "";
			$this->question_id = "";
			$this->value = "";
		}
	
		//Set data members
		function setDataMembers($userAnswerId, $responseId, $questionId, $val){
			$this->userAnswer_id = $userAnswerId;
			$this->response_id = $responseId;
			$this->question_id = $questionId;
			$this->value = $val;
		}
		
		// Inserts the answers of a survey
		public function createAnswer($responseId, $questionId, $val){
			$query = "INSERT INTO useranswers(useranswers_id, response_id, question_id, value)
						VALUES(null, '".$responseId."', '".$questionId."', '".$val."');";
			mysql_query($query);
			
		}
	}
?>