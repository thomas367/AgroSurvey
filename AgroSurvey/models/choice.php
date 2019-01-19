<?php
	require("../dbConfig/databaseConnection.php");

	class Choice {

		//Data members
		private $choice_id;
		private $question_id;
		private $choise_text;
		private $image_choice;
		
		//Constructor
		function __construct(){
			$this->choice_id="";
			$this->question_id="";		
			$this->choise_text="";
		}
		
		//Set data members
		function setDataMembers($choiceId, $questionId, $choiceText){
			$this->choice_id = $choiceId;
			$this->question_id = $questionId;
			$this->choice_text = $choiceText;
		}
		
		/*************************************** Text Choice Functions *********************************************************************************/
		
		//Get the choices of each question
		public function getChoicesByQuestionId($questionId){
			$query = "SELECT * FROM choices WHERE question_id='".$questionId."'";
			$result = mysql_query($query);
			$array_result = array();
			while($row = mysql_fetch_object($result)){
				$array_result[] = $row;
			}
			return	
				$array_result;
		}		
		
		//Get choices by choiceId
		public function getChoiceByChoiceId($choiceId=0){
			$query = "SELECT * FROM choices WHERE choice_id='".$choiceId."'";
			$result = mysql_query($query);
			$array_result = array();
			while($row = mysql_fetch_object($result)){
				$array_result[] = $row;
			}
			return	
				array_shift($array_result);
		}
			
		//Update a choice in the choice table
		public function updateChoice($choiceId, $choiceText){	
			$query = "UPDATE choices SET choice_text='".$choiceText."' WHERE choices.choice_id='".$choiceId."'"; 
			mysql_query($query);			
		}
	
		//Create choice function
		public function createChoice($questionId, $choiceText){
			$query = "INSERT INTO choices(choice_id, question_id, choice_text)
						VALUES(null, '".$questionId."', '".$choiceText."');"; 
			mysql_query($query);
		}
			
		//Delete choice function
		public function deleteChoice($choiceId){
			$query = "DELETE FROM choices WHERE choice_id='".$choiceId."'";
			mysql_query($query);
		}
	
	/*************************************** Image Choice Functions *********************************************************************************/
	
		//Get the image choice of each question
		public function getImageChoicesByQuestionId($questionId){
			$query = "SELECT * FROM imagechoices WHERE question_id='".$questionId."'";
			$result = mysql_query($query);
			$array_result = array();
			while($row = mysql_fetch_object($result)){
				$array_result[] = $row;
			}
			return	
				$array_result;
		}
		
		//Get image choices by choiceId
		public function getImageChoiceByChoiceId($choiceId=0){
			$query = "SELECT * FROM imagechoices WHERE image_id='".$choiceId."'";
			$result = mysql_query($query);
			$array_result = array();
			while($row = mysql_fetch_object($result)){
				$array_result[] = $row;
			}
			return	
				array_shift($array_result);
		}
		
		//Delete image choice function
		public function deleteImageChoice($choiceId){
			$query = "DELETE FROM imagechoices WHERE image_id='".$choiceId."'";
			mysql_query($query);
		}
		
		// Create image choice function	
		public function createImageChoice($questionId, $name, $path){
			$query = "INSERT INTO imagechoices(image_id, question_id, image_name, image_path)
						VALUES(null, '".$questionId."', '".$name."', '".$path."');"; 
			mysql_query($query);
		}
		
		//Update an image in the image choice table
		public function updateImageChoice($choiceId, $name, $path){	
			$query = "UPDATE imagechoices SET image_name='".$name."', image_path='".$path."' WHERE imagechoices.image_id='".$choiceId."'"; 
			mysql_query($query);		
		}
	}
?>