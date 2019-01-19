<?php
	class imageValidation{
		
		//Method that works out the extension of the file to be uploaded.
		public function fileExtensionCheck($imageName){
			//valid file extensions.
			$allowedExtensions = array("png","jpg","jpeg","gif","bmp","JPG","JPEG","GIF","BMP","PNG");
			
			//Works out the file extension
			$imageExt = explode('.', $imageName);
			$imageExt = strtolower(end($imageExt));
			
			for($i=0; $i<sizeof($allowedExtensions); $i++){
				if($imageExt == $allowedExtensions[$i])
					return null;
			}
			return "Extention";
		}
		
		//Method that checks the size of the file to be uploaded
		public function fileSizeCheck($imageSize){
			// allowed size 600KB
			if($imageSize <= 614400)
				return null;
			else
				return "Size";
		}
	}
?>