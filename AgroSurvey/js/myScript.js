$(document).ready(function () {
	/* 
		Checks if the survey has responses and calls the proper method.
		
		1. If the survey doesn't have responses calls the comfirmSurveyDeletion 
		to delete the current Survey and the proper Survey_id and survey_name.
		2. If the survey has responses calls the Infomation function and tell us 
		that we cant delete this survey.
	*/
    $(".delete_survey").click(function (e) {
        e.preventDefault();
        var parent = $(this).closest('tr'),
            survey_id = $(this).data("survey-id");
        var survey_title = $(this).data("survey-name");
		 $.ajax({
			type 	 : "POST",
			data 	 : { id : survey_id},
			url  	 : "../controllers/checkSurveyResponsesController.php",
			dataType : "JSON"
		}).done(function (result){
			if(result == true ){
				Infomation();
			}
			else{
				confirmSurveyDeletion(parent, survey_id, survey_title);
			}
		});
    });
	
	/*
		Calls the copyLink function to copy the link
		of the survey.
	*/
	$(".publishLink").click(function (e) {
		e.preventDefault();
		var survey_title = $(this).data("survey-name");
		copyLink(survey_title);
	});
	
	/*
		When "Add Question" button is clicked
		appends the question template in the questions container.
		
		The default append is: One question textbox with 2 choise the textboxes
	*/
	$("#add_question_button").click(function () {
        if ($(this).hasClass("disabled")) {
            return false;
        } else {    
			$("#questions_container").append($(".question_template_container").html());	
        }
    }); // end of "add_question_button" function
	
	$("#questions_container")
		/*
			On pick Question Type preview the right choices.
			On change Question Type deletes the previous choices
		*/
		.on('change', '.select_question_type', function() {
			var thisChoices = $(this).closest(".question_template").find(".choices"),
                question_type = $(this).val(),
                thisChoiceIds = $(this).closest(".question_template").find(".choices #choice_id").map(function(){ return $(this).val(); }).get(),
				thisImageChoiceIds = $(this).closest(".question_template").find(".choices #image_choice_id").map(function(){ return $(this).val(); }).get();
				
				
            if (thisChoiceIds.length > 0) {
				if(question_type == 2){
					$.each(thisChoiceIds, function (i, choice_id) {
						if (choice_id) {
							deleteChoice(choice_id);
						}
					});
				}
            }
			
			else if (thisImageChoiceIds.length > 0) {
				if(question_type == 1){
					$.each(thisImageChoiceIds, function (i, image_choice_id) {
						if (image_choice_id) {
							deleteImageChoice(image_choice_id);
						}
					});
				}
            }
			
            var defaultChoices = '';
            if (question_type) {
                // Single Choice
                if (question_type == 1) {
                    defaultChoices ='<div class="">' +
										'<div class="form-group row">' +
											'<div class="col-xs-4">' +
												'<input type="text" name="choices[]" class="form-control" placeholder="Choice 1" value="" required>' +
												'<input type="hidden" id="choice_id" name="choice_ids[]" value="">' +
											'</div>' +
											'<div class="col-xs-1">' +
												'<span class="glyphicon glyphicon-minus delete_choice hidden"></span>' +
											'</div>' +
											
										'</div>' +
									
										'<div class="form-group row">' +
											'<div class="col-xs-4">' +
												'<input type="text" name="choices[]" class="form-control" placeholder="Choice 2" value="" required>' +
												'<input type="hidden" id="choice_id" name="choice_ids[]" value="">' +
											'</div>' +
											'<div class="col-xs-1">' +
												'<span class="glyphicon glyphicon-minus delete_choice hidden"></span>' +
												'<span class="glyphicon glyphicon-plus add_choice"></span>' +
											'</div>' +
										'</div>' +
									'</div>';               
                } // Image Choice
				else if (question_type == 2) {  
                    defaultChoices ='<div class="">' + 
                                        '<div class="form-group row">' +
                                            '<div class="col-xs-4">' +
                                                '<input type="file" name="image_choices[]" class="form-control" accept="image/*" value="" required onchange="previewImage(this)">' +
                                                '<img src"#" id="preview" alt="">' +
												'<input type="hidden" id="image_choice_id" name="image_choice_ids[]" value="">' +	
                                            '</div>' +
											'<div class="col-xs-1">' +
												'<span class="glyphicon glyphicon-minus delete_choice hidden"></span>' +
											'</div>' +
                                        '</div>' +

                                        '<div class="form-group row">' +
                                            '<div class="col-xs-4">' +
                                                '<input type="file" name="image_choices[]" class="form-control" accept="image/*" value="" required onchange="previewImage(this)">' +
                                                '<img src"#" id="preview" alt="">' +
												'<input type="hidden" id="image_choice_id" name="image_choice_ids[]" value="">' +
                                            '</div>' +
                                            '<div class="col-xs-1">' +
												'<span class="glyphicon glyphicon-minus delete_choice hidden"></span>' +
                                                '<span class="glyphicon glyphicon-plus add_choice"></span>' +
                                            '</div>' +
                                        '</div>' +
                                    '</div>';      
                } 
            } 
			else {
                defaultChoices = '';
            }

            thisChoices.html(defaultChoices);
        })

		// Adds a choice in the current question form.
		.on('click', '.add_choice', function () {
			var thisChoicesContainer = $(this).closest(".choices"),
				choicesCount = thisChoicesContainer.find(".form-group").length, 
				removeHidden = thisChoicesContainer.find(".delete_choice"),
				question_type = $(this).closest(".question_template").find(".select_question_type").val();
				
			if(question_type == 1){	
				var newChoice = '<div class="form-group row">' +
									'<div class="col-xs-4">' +
										'<input type="text" name="choices[]" class="form-control" placeholder="Choice ' + (choicesCount + 1) + '" value="" required>' +
										'<input type="hidden" id="choice_id" name="choice_ids[]" value="">' +
									'</div>' +
									'<div class="col-xs-1">' +
										'<span class="glyphicon glyphicon-minus delete_choice "></span>' +
										'<span class="glyphicon glyphicon-plus add_choice"></span>' +
									'</div>' +
								'</div>';
			}
			else if (question_type == 2){
				var newChoice = '<div class="form-group row">' +
									'<div class="col-xs-4">' +
										'<input type="file" name="image_choices[]" class="form-control" accept="image/*" value="" required onchange="previewImage(this)">' +
                                        '<img src"#" id="preview" alt="">' +
										'<input type="hidden" id="image_choice_id" name="image_choice_ids[]" value="">' +
									'</div>' +
									'<div class="col-xs-1">' +
										'<span class="glyphicon glyphicon-minus delete_choice"></span>' +
										'<span class="glyphicon glyphicon-plus add_choice"></span>' +
									'</div>' +
								'</div>';		
			}
			removeHidden.removeClass("hidden");
			thisChoicesContainer.children("div").append(newChoice);
			$(this).remove();
		})
		
		 /*
			Removes a choice in the current question form
			if the choice was already saved call the deleteChoice method
			to delete it from the database. Appends the addChoice button in the last 
			choice. Renumber choices in the placeholder.
			If the choices are below 3 the delete choice button disappers. 
		 */
		.on('click', '.delete_choice', function () {
			var thisChoicesContainer = $(this).closest(".choices"),
				thisChoices = thisChoicesContainer.find(".form-group"),
				choicesCount = thisChoicesContainer.find(".form-group").length,
				addHidden = thisChoicesContainer.find(".delete_choice"),
				parent = $(this).closest(".form-group"),
				choice_id = parent.find('#choice_id').val(),
				imageChoice_id = parent.find('#image_choice_id').val(),
				question_type = $(this).closest(".question_template").find(".select_question_type").val();
				
			if(question_type == 1){	
				if (choice_id) {
					deleteChoice(choice_id);
				}
			}
			else if(question_type == 2){
				if(imageChoice_id){
					deleteImageChoice(imageChoice_id);
				}
			}
			if (parent.is(thisChoices.last())) {
				parent.prev().find(".col-xs-1").append('<span class="glyphicon glyphicon-plus add_choice"></span>');
			}
			parent.remove();
			
			
			if(choicesCount < 4){
				addHidden.addClass("hidden");
			}
			thisChoicesContainer.find(".form-group").each(function (index) {
				$(this).find("input").attr("placeholder", "Choice " + (index + 1));        
			});
		})	
		
		/*
			Calls the comfirmQuestionDeletion to delete the current
			Question and pass the proper Question_id.
		*/
		.on('click', '.delete_question', function (e) {
			e.preventDefault();
			var parent = $(this).closest('.question_template');
			var question_id = parent.attr("id");
			confirmQuestionDeletion(parent, question_id);
		}); // End of question_container actions.
		
		/*
			Hides the loaded image and appends the input file to
			upload a new image.
		*/
		$(".close").click(function () {
			var thisImageContainer = $(this).closest(".col-xs-2"),
				thisInputContainer = thisImageContainer.next('.col-xs-4');
			thisImageContainer.addClass("hidden");
			thisInputContainer.removeClass("hidden");
		});
});

/*
	Image preview function.
*/
function previewImage(input) {
	var preview = input.nextElementSibling;
	var image = input.files[0];
	var reader = new FileReader();

	reader.onloadend = function() {
		preview.src = reader.result;
	}

	if (image) {
		reader.readAsDataURL(image);
	} 
	else {
		preview.src = "";
	}
}

/*
	This function sends the data forms to the 
	submitQuestionController and gets backs the Ids
	to edit the form without reload the page.

	Stays on page so we can continue with the 
	creation/edit of the survey.
*/
var saveQuestion = function (form) {
	$.ajax({
		url    	     : $(form).attr('action'),
		type    	 : "POST",
		data         : new FormData($(form)[0]),
		processData  : false,
		contentType  : false,
		dataType     : "json",
		success      : function (rsp) {
			if(rsp.result == 'Extention'){
				ExtentionError();
				$(form).parent().attr('id', rsp.question_id);
				$(form).find("#question_id").val(rsp.question_id);
				$(form).find(".delete_question").removeClass('hidden');
				if (rsp.image_choices.length > 0){
					var thisFormChoices = $(form).find(".choices #image_choice_id");
					$.each(rsp.image_choices, function(i, image_choice_id){
						thisFormChoices.eq(i).val(image_choice_id);
					});
				}
			}
			else if(rsp.result == 'Size'){
				SizeError();
				$(form).parent().attr('id', rsp.question_id);
				$(form).find("#question_id").val(rsp.question_id);
				$(form).find(".delete_question").removeClass('hidden'); 
				if (rsp.image_choices.length > 0){
					var thisFormChoices = $(form).find(".choices #image_choice_id");
					$.each(rsp.image_choices, function(i, image_choice_id){
						thisFormChoices.eq(i).val(image_choice_id);
					});
				}
			}
			else{
				$(form).parent().attr('id', rsp.question_id);
				$(form).find("#question_id").val(rsp.question_id);
				$(form).find(".delete_question").removeClass('hidden'); 
				if (rsp.choices.length > 0 && rsp.image_choices.length == 0) {
					var thisFormChoices = $(form).find(".choices #choice_id");
					$.each(rsp.choices, function(i, choice_id){
						thisFormChoices.eq(i).val(choice_id);
					});
				}
				else if (rsp.image_choices.length > 0 && rsp.choices.length == 0){
					var thisFormChoices = $(form).find(".choices #image_choice_id");
					$.each(rsp.image_choices, function(i, image_choice_id){
						thisFormChoices.eq(i).val(image_choice_id);
					});
				}
			}
		}
	});   
	return false;
}
/*
	Login action.
*/
var loginAction = function (form) {
	$.ajax({
		url    	     : $(form).attr('action'),
		type    	 : "POST",
		data         : $(form).serialize(),
		dataType     : "json",
		success      : function (rsp) {
			if(rsp.status == 'Empty'){
				EmptyFields();
			}
			else if(rsp.status == 'Wrong'){
				WrongData();
			}
			else if(rsp.status == 'Success'){
				window.location.replace("views/cPanel.php");		
			}
		}
	});
	return false;
}

/*
	Register action.
*/
var registerAction = function (form) {
	$.ajax({
		url    	     : $(form).attr('action'),
		type    	 : "POST",
		data         : $(form).serialize(),
		dataType     : "json",
		success      : function (rsp) {
			if(rsp.status == 'Empty'){
				EmptyFields();
			}
			else if(rsp.status == 'Error'){
				UserExists();
			}
			else if(rsp.status == 'Wrong'){
				MismatchFields();
			}
			else if(rsp.status == 'Success'){
				window.location.replace("../views/cPanel.php");
			}
		}
	});
	return false;
}

/*
	This  function appears a sweet alert pop up message
	if you press "Delete" sends the "Question_id" to the
	deleteQuestionController php file to delete the Question
	then removes the current Question container.
*/
function confirmQuestionDeletion(parent, question_id) {
    swal({
        title: "Are you sure you want to delete this question?",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Delete",
        closeOnConfirm: true
    }).then(function (result) { 
		if(result.value){
			$.post("../controllers/deleteQuestionController.php", { id: question_id },
				function () { 
					parent.fadeOut(500, function() {
						parent.remove();
					});
				}
			);
        }
    });
}

/*
	This  function appears a sweet alert pop up message
	if you press "Delete" sends the "Survey_id" to the
	deleteSurveyController php file to delete the Survey
	then removes the current Survey container.
*/
function confirmSurveyDeletion(parent, survey_id, survey_title) {
    swal({
        title: "Are you sure?",
        text: "You are going to delete \"" + survey_title + "\"!",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Delete",
        closeOnConfirm: true
    }).then(function (result) { 
		if(result.value){
			$.post("../controllers/deleteSurveyController.php", { id: survey_id },
				function () { 
					parent.fadeOut(500, function() {
						parent.remove();
					});
				}
			);	
        }
    });
}

/*
	Message for surveys with responses.
*/
function Infomation() {
    swal({
        title: "Whoa there!",
        text: "You can not delete a survey with responses!",
        type: "info",
        showCancelButton: false,
        confirmButtonClass: "btn-primary",
        confirmButtonText: "OK",
        closeOnConfirm: true
    })
}

/*
	Message for a proper image extention.
*/
function ExtentionError() {
    swal({
        title: "Whoa there!",
        text: "Upload an image with proper extention!",
        type: "warning",
        showCancelButton: false,
        confirmButtonClass: "btn-primary",
        confirmButtonText: "OK",
        closeOnConfirm: true
    })
}

/*
	Message for the image size.
*/
function SizeError() {
    swal({
        title: "Whoa there!",
        text: "Images can not be larger than 600KB!",
        type: "warning",
        showCancelButton: false,
        confirmButtonClass: "btn-primary",
        confirmButtonText: "OK",
        closeOnConfirm: true
    })
}

/*
	Message for empty fields.
*/
function EmptyFields() {
    swal({
        title: "Whoa there!",
        text: "There are empty fields! Please fullfil all the fields.",
        type: "warning",
        showCancelButton: false,
        confirmButtonClass: "btn-primary",
        confirmButtonText: "OK",
        closeOnConfirm: true
    })
}

/*
	Message for wrong username or password.
*/
function WrongData() {
    swal({
        title: "Whoa there!",
        text: "Username or password is wrong!",
        type: "warning",
        showCancelButton: false,
        confirmButtonClass: "btn-primary",
        confirmButtonText: "OK",
        closeOnConfirm: true
    })
}

/*
	Message for username already exists.
*/
function UserExists() {
    swal({
        title: "Whoa there!",
        text: "Username already exists! Please pick a new one.",
        type: "warning",
        showCancelButton: false,
        confirmButtonClass: "btn-primary",
        confirmButtonText: "OK",
        closeOnConfirm: true
    })
}

/*
	Message for password and repassword mismatch.
*/
function MismatchFields() {
    swal({
        title: "Whoa there!",
        text: "Password and repassword mismatch!",
        type: "warning",
        showCancelButton: false,
        confirmButtonClass: "btn-primary",
        confirmButtonText: "OK",
        closeOnConfirm: true
    })
}


/*
	In this  function if you press "removeChoice" button and the 
	current choice is saved in the database sends the "Choice_id" 
	to the deleteChoiceController php file to delete the Choice.
*/
function deleteChoice(choice_id) {
    $.post("../controllers/deleteChoiceController.php", { id: choice_id },
    );
}

/*
	In this  function if you press "removeChoice" button and the 
	current choice is saved in the database sends the "imageChoice_id" 
	to the deleteImageChoiceController php file to delete the Choice.
*/
function deleteImageChoice(imageChoice_id) {
    $.post("../controllers/deleteImageChoiceController.php", { id: imageChoice_id },
    );
}

/*
	Creates a link of the survey inside the toastr message.
	Click on the toastr message copies the link and opens  
	in a new window the survey.
*/
function copyLink (survey_title) {
    var clipboard = new Clipboard(".publishLink");
    clipboard.on('success', function (e) {
        toastr.options = {
            "positionClass":    "toast-bottom-center",
            "onclick":          function () { window.open(e.text); },
            "timeOut":          "9000",
            "preventDuplicates": true,
            "extendedTimeOut":  "1000",
            "showMethod":       "fadeIn",
            "showEasing":       "swing",
            "showDuration":     "300",
            "hideMethod":       "fadeOut",
            "hideEasing":       "linear",
            "hideDuration":     "1000"
        };
        $("#toast-container").remove();
        toastr.success('A link to "' + survey_title + '" has been copied to your clipboard.<br>(click to view)');
        e.clearSelection();
    });
}

/*
	On load gets all the Question ids from the 
	current survey and calls the getQuestionCharts 
	function get the data and draws the chart.
*/
function report (question_ids) {
    getQuestionCharts(question_ids);
}

/* 
	This function gets all the questionIds of the current survey
	and post them in "getChartsController.php" file and gets back 
	the answer data of each question in JSON form.
*/
function getQuestionCharts (question_ids) {
    var  jsonData = $.ajax({
            url     : "../controllers/getChartsController.php",
            type    : "POST",
            data    : { question_ids : question_ids },
            dataType: "json",
            async   : false
        }).responseJSON;
    // Load the Visualization API and the package that includes PieChart.
    google.charts.load('current', {'packages':['corechart']});
    // Set a callback to run when the Google Visualization API is loaded.
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
		for (var i = 0; i < question_ids.length; i++) {
			// Create our data table out of JSON data loaded from server.
			var data = new google.visualization.DataTable(jsonData[question_ids[i]]['chartData']);  
			var piechart = new google.visualization.PieChart(document.getElementById('pieChart-' + [question_ids[i]]));
				piechart.draw(data, {width: 455, height: 250});
		}
    }
}