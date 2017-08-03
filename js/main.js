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
		$("#answers").html('your result is :');
		$("#submit").hide();
	}
	else{
		$("#question").html('<h6>' + data.question.body +  '</h6>');
		$("#answers").html(answers(data.answers));
		$("#questionId").val(data.question.id);
		$("#submit").val('Next');		
	}

}

function answers(answers) {
	if (answers.length === 0 ) {		
		return '';
	}
	else{
		var html = '';
		for (var i = 0; i < answers.length; i++) {
			html = html + 	'<input type="radio" name="userAnswer" value="' + answers[i].body +'" required>'+ ' ' + answers[i].body +'<br>' ;	
		}		
		return html ;
	}
	
}














});


