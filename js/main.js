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

function nextQuestion(question) {
	if (question.more == 0 ) {
		$("#quiz-content").html('<h6>You have done</h6>');
	}
	else{
		$("#quiz-content").html('<h6>' + question.body + ': '+ question.id + '</h6>');
		$("#questionId").val(question.id);
		$("#submit").val('Next');		
	}

}

















});


