jQuery(document).ready(function($) {

$("#addTryDetailsForm").submit(function(e) {
	e.preventDefault();
	$.post("/ajaxaddtrydetails",
	    $("#addTryDetailsForm").serialize() ,
	function(data, status){
	});
	$.post("/ajaxquiz",
	    $("#doQuizForm").serialize() ,
	function(data, status){
		if (data.login == '0') {window.location.href = "/userquiz";}
		else {nextQuestion(data);}
	});
});

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
		if (lang == 'sv') {
			$("#question").html('<h6>Du är klar</h6>');
		}else{
			$("#question").html('<h6>You have done</h6>');
		}

		if ($('#showResult').val() == 1) {
			if (lang == 'sv') {
				$("#answers").html('Ditt resultat är : ' + data.tryScore + ' % '+ result(data));
			}else{
				$("#answers").html('your result is : ' + data.tryScore + ' % '+ result(data));
			}

		}else{
			if (lang == 'sv') {
				$("#answers").html('Du kommer att få resultatet via E-post');
			}else{
				$("#answers").html('You will receive the result by mail');
			}

		}
		$("#next").hide();
		$(".countdown").hide();
	}
	else{
		if (data.question.image) {img = '<img src="'+ data.question.image  +'" alt="Smiley face" height="200" width="200"><br>' }else{ img = '';}
		$("#question").html(img +'<p>' + data.question.body +  '</p>');
		$("#answers").html(answers(data));
		$("#questionId").val(data.question.id);
		$("#addTryDetailsForm").hide();
		$("#next").show();
	}

}

function answers(data) {
	if (data.answers.length === 0 ) {
		return '';
	}
	else{		
		var html = '';
		if (data.question.textChoice == 1) {
			for (var i = 0; i < data.answers.length; i++) {
				html = html + 	'<textarea name="userAnswer['+data.answers[i].id+']" rows="1" cols="60" required placeholder="'+data.answers[i].title+'"></textarea>';	
			}
			if(data.question.showAgreement == 1){
				if (lang == 'sv') {
					html = html + '<input type="checkbox" required> Jag godkänner att mina uppgifter sparas <a href="'+agreementLink+'"target="_blank">Läs mer om vår GDPR policy</a>';
				}else{
					html = html + '<input type="checkbox" required> I agree that my data will be saved <a href="'+agreementLink+'"target="_blank">Read more about our GDPR policy</a>';
				}				
			}
		}
		else if (data.question.multichoice == 1) {
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
		if (lang == 'sv') {
			if (data[i].score > -1 ) {
				result = result + '<br><br>' + data[i].question + '<div class="row"><div class="col-sm-6"><br> Dina svar:' + resultAnswers(data[i].userAnswers) + '</div><div class="col-sm-6"></div></div><br> Poäng: ' +  data[i].score;
			}
			else{
				result = result + '<br><br>' + data[i].question + '<div class="row"><div class="col-sm-6"><br>' + resultAnswers(data[i].userAnswers) + '</div><div class="col-sm-6"></div></div><br>';
			}
		}else{			
			if (data[i].score > -1 ) {
				result = result + '<br><br>' + data[i].question + '<div class="row"><div class="col-sm-6"><br> Your Answers:' + resultAnswers(data[i].userAnswers) + '</div><div class="col-sm-6"></div></div><br> Score: ' +  data[i].score;
			}
			else{
				result = result + '<br><br>' + data[i].question + '<div class="row"><div class="col-sm-6"><br>' + resultAnswers(data[i].userAnswers) + '</div><div class="col-sm-6"></div></div><br> ';

			}			
		}
	}
	return result;
}
/* with corrcet answers
function result(data) {
	result= '';
	for (var i = 0; i < Object.keys(data).length - 2 ; i++) {
		result = result + '<br><br>' + data[i].question + '<div class="row"><div class="col-sm-6"><br> Your Answers:' + resultAnswers(data[i].userAnswers) + '</div><div class="col-sm-6"><br> Correct Answers:' + resultAnswers(data[i].correctAnswers) + '</div></div><br> Score: ' +  data[i].score;

	}
	return result;
}
*/
function resultAnswers(Answers) {
	var answers = '';
	for (var i = 0; i < Answers.length; i++) {
		answers = answers + '<br>' + Answers[i].body;
	}
	return answers;
}


$("#start").click(function(){
  timer();
});

function timer(timeout) {

	var timer2 = "0:00";
	var interval = setInterval(function() {


	  var timer = timer2.split(':');
	  //by parsing integer, I avoid all extra string processing
	  var minutes = parseInt(timer[0], 10);
	  var seconds = parseInt(timer[1], 10);
	  ++seconds;
	  minutes = (seconds > 59) ? ++minutes : minutes;
	  if (minutes > 59) clearInterval(interval);
	  seconds = (seconds > 59) ? 0 : seconds;
	  seconds = (seconds < 10) ? '0' + seconds : seconds;
	  //minutes = (minutes < 10) ?  minutes : minutes;
	  $('.countdown').html(minutes + ':' + seconds);
	  timer2 = minutes + ':' + seconds;
	}, 1000);
}








});

//alert(JSON.stringify(data[0]));
