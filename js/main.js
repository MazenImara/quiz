jQuery(document).ready(function($) {

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
		$("#answers").html('your result is : ' + data.tryScore + ' % '+ result(data));				
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
	for (var i = 0; i < Object.keys(data).length - 2 ; i++) {
		result = result + '<br><br>' + data[i].question + '<div class="row"><div class="col-sm-6"><br> Your Answers:' + resultCorrectAnswers(data[i].userAnswers) + '</div><div class="col-sm-6"><br> Correct Answers:' + resultUserAnswers(data[i].correctAnswers) + '</div></div><br> Score: ' +  data[i].score;
		
	}
	return result;
}

function resultCorrectAnswers(correctAnswers) {
	var answers = '';
	for (var i = 0; i < correctAnswers.length; i++) {
		answers = answers + '<br>' + correctAnswers[i].body;
	}
	return answers;	
}

function resultUserAnswers(userAnswers) {
	var answers = '';
	for (var i = 0; i < userAnswers.length; i++) {
		answers = answers + '<br>' + userAnswers[i].body;
	}
	return answers;	
}











});

//alert(JSON.stringify(data[0]));
