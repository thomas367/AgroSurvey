<?php 
	require("../dbConfig/databaseConnection.php");
	
	class Question {
		
		//Data members
		private $question_id;
		private $survey_id;
		private $question_type;
		private $question_text;

		
		//Constructor
		function __construct(){
			$this->question_id="";
			$this->survey_id="";
			$this->question_type="";
			$this->question_text="";
		}
		
		//Set data members
		function setDataMembers($questionId, $surveyId, $questionType ,$questionText){
			$this->question_id = $questionId;
			$this->survey_id = $surveyId;
			$this->question_type = $questionType;
			$this->question_text = $questionText;
		}
		
		//Get questions of the selected survey
		public function getQuestionsBySurveyId($surveyId=0){
			$query = "SELECT * FROM questions WHERE survey_id='".$surveyId."'";
			$result = mysql_query($query);
			$array_result = array();
			while($row = mysql_fetch_object($result)){
				$array_result[] = $row;
			}
			return	
				$array_result;
		}
		
		//Get questions by questionId
		public function getQuestionByQuestionId($questionId=0){
			$query = "SELECT * FROM questions WHERE question_id='".$questionId."'";
			$result = mysql_query($query);
			$array_result = array();
			while($row = mysql_fetch_object($result)){
				$array_result[] = $row;
			}
			return	
				array_shift($array_result);
		}
		
		//Update a question in the question table
		public function updateQuestion($questionId, $questionType ,$questionText){	
			$query = "UPDATE questions SET question_type='".$questionType."', question_text='".$questionText."' WHERE questions.question_id='".$questionId."'"; 
			mysql_query($query);		
			
		}
		
		//Create question function
		public function createQuestion($surveyId, $questionType ,$questionText){
			$query = "INSERT INTO questions(question_id, survey_id, question_type ,question_text)
						VALUES(null, '".$surveyId."', '".$questionType."' ,'".$questionText."');"; 
			mysql_query($query);
			
		}
		
		//Delete question function
		public function deleteQuestion($questionId){
			$query = "DELETE FROM questions WHERE question_id='".$questionId."'";
			mysql_query($query);			
		}
	}
?>