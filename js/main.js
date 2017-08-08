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
		$("#next").hide();
		$(".countdown").hide();
	}
	else{
		if (data.question.image) {img = '<img src="'+ data.question.image  +'" alt="Smiley face" height="200" width="200"><br>' }else{ img = '';}
		$("#question").html(img +'<h6>' + data.question.body +  '</h6>');
		$("#answers").html(answers(data));
		$("#questionId").val(data.question.id);
		$("#start").hide();
		$("#next").show();		
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

$("#start").click(function(){	
  timer();
});

function timer(timeout) {
	
	var timer2 = "5:01";
	var interval = setInterval(function() {


	  var timer = timer2.split(':');
	  //by parsing integer, I avoid all extra string processing
	  var minutes = parseInt(timer[0], 10);
	  var seconds = parseInt(timer[1], 10);
	  --seconds;
	  minutes = (seconds < 0) ? --minutes : minutes;
	  if (minutes < 0) clearInterval(interval);
	  seconds = (seconds < 0) ? 59 : seconds;
	  seconds = (seconds < 10) ? '0' + seconds : seconds;
	  //minutes = (minutes < 10) ?  minutes : minutes;
	  $('.countdown').html(minutes + ':' + seconds);
	  timer2 = minutes + ':' + seconds;
	}, 1000);
}








});

//alert(JSON.stringify(data[0]));
