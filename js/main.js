jQuery(document).ready(function($) {

function doQuiz() {
	alert()
	
}

$("#doQuizForm").submit(function(e) {
    e.preventDefault();
	$.post("/ajaxquiz",
	    $("#doQuizForm").serialize() ,
	function(data, status){			
		if (data.login == '0') {window.location.href = "/userquiz";}
		else {nextQuestion(data);}	    
	});	   
});

function nextQuestion(data) {
	if (data.more == 0 ) {
		$("#question").html('<h6>You have done</h6>');
		$("#answers").html('your result is :' + result(data));				
		$("#submit").hide();
	}
	else{
		$("#question").html('<h6>' + data.question.body +  '</h6>');
		$("#answers").html(answers(data));
		$("#questionId").val(data.question.id);
		$("#submit").val('Next');		
	}

}

function answers(data) {
	if (data.answers.length === 0 ) {		
		return '';
	}
	else{
		var html = '';
		if (data.question.multichoice == 1 ) {
			for (var i = 0; i < data.answers.length; i++) {
				html = html + 	'<input type="checkbox" name="userAnswer[]" value="' + data.answers[i].id +'" >'+ ' ' + data.answers[i].body +'<br>' ;	
			}
		}
		else {		
			for (var i = 0; i < data.answers.length; i++) {
				html = html + 	'<input type="radio" name="userAnswer" value="' + data.answers[i].body +'" required>'+ ' ' + data.answers[i].body +'<br>' ;	
			}	
		}
		return html ;
	}
	
}

function result(data) {
	result= '';
	for (var i = 0; i < Object.keys(data).length - 1 ; i++) {
		result = result + '<br>' + i + data[i].question;
	}
	return result;
}












});

//alert(JSON.stringify(data[0]));
