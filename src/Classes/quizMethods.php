<?php

namespace Drupal\quiz\Classes;
use Symfony\Component\HttpFoundation\RedirectResponse;

class quizMethods {

	public static function addQuiz($quiz, $imgurl) {
		try {
			\Drupal::database()->insert('quiz')
			                   ->fields([
					'title',
					'body',
					'image',
					'showResult',
					'seccess',
					'send_email',
				])
			->values(array(
					$quiz['title'],
					$quiz['body'],
					$imgurl,
					$quiz['show_result'],
					$quiz['seccess'],
					$quiz['send_email'],
				))
			->execute();
			$response = new RedirectResponse('quiz/'.self::getLast('quiz'));
			$response->send();
			drupal_set_message($quiz['title'].' added successfully');
		} catch (\Exception $e) {
			drupal_set_message('Error happen when adding quiz');
		}
	}
	static public function editQuiz($quiz, $imageUrl) {
		\Drupal::database()->update('quiz')
		                   ->condition('id', [$quiz['id']])
		                   ->fields([
				'title'      => $quiz['title'],
				'body'       => $quiz['body'],
				'showResult' => $quiz['show_result'],
				'seccess'    => $quiz['seccess'],
				'send_email' => $quiz['send_email'],
			])
			->execute();
		if ($imageUrl) {
			\Drupal::database()->update('quiz')
			                   ->condition('id', [$quiz['id']])
			                   ->fields([
					'image' => $imageUrl,
				])
				->execute();
		}
	}

	static public function getLast($table) {
		$result = \Drupal::database()->select($table, 'q')
		                             ->fields('q', ['id'])
		                             ->orderBy('id', 'DESC')
		                             ->execute();
		while ($row = $result->fetchAssoc()) {
			return $row['id'];
		}
	}
	static public function getAllQuizes() {
		$query = \Drupal::database()->select('quiz', 'q');
		$query->fields('q', ['id', 'title', 'body', 'image', 'showResult', 'seccess', 'send_email']);
		$result = $query->execute();
		$quizes = [];
		while ($row = $result->fetchAssoc()) {
			array_push($quizes, [
					'id'         => $row['id'],
					'title'      => $row['title'],
					'body'       => $row['body'],
					'image'      => $row['image'],
					'showResult' => $row['showResult'],
					'seccess'    => $row['seccess'],
					'send_email' => $row['send_email'],
				]);
		}
		return $quizes;
	}

	static public function getQuiz($id) {
		$quiz   = null;
		$result = \Drupal::database()->select('quiz', 'q')
		                             ->fields('q', ['id', 'title', 'body', 'image', 'showResult', 'seccess', 'send_email'])
		                             ->condition('id', [$id])
		                             ->execute();
		while ($row = $result->fetchAssoc()) {
			$quiz = [
				'id'         => $row['id'],
				'title'      => $row['title'],
				'body'       => $row['body'],
				'image'      => $row['image'],
				'showResult' => $row['showResult'],
				'seccess'    => $row['seccess'],
				'send_email' => $row['send_email'],
			];
		}
		return $quiz;
	}

	static public function addQuestion($question, $imgurl) {

		try {
			\Drupal::database()->insert('quiz_question')
			                   ->fields([
					'body',
					'multichoice',
					'quizId',
					'image',
				])
			->values(array(
					$question['body'],
					$question['multichoice'],
					$question['quizId'],
					$imgurl,
				))
			->execute();

			$response = new RedirectResponse('question/'.self::getLast('quiz_question'));
			$response->send();

		} catch (\Exception $e) {
			drupal_set_message('Error happen when adding Question');
		}
	}

	static public function getAllQuestions($quizId) {
		$result = \Drupal::database()->select('quiz_question', 'q')
		                             ->fields('q', ['id', 'body', 'multichoice', 'quizId', 'image'])
		                             ->condition('quizId', [$quizId])
		                             ->execute();
		$questions = [];
		while ($row = $result->fetchAssoc()) {
			array_push($questions, [
					'id'          => $row['id'],
					'body'        => $row['body'],
					'multichoice' => $row['multichoice'],
					'quizId'      => $row['quizId'],
					'image'       => $row['image'],
				]);
		}
		return $questions;
	}

	static public function getQuestionById($id) {
		$result = \Drupal::database()->select('quiz_question', 'q')
		                             ->fields('q', ['id', 'body', 'multichoice', 'quizId', 'image'])
		                             ->condition('id', [$id])
		                             ->execute();
		while ($row = $result->fetchAssoc()) {
			$question = [
				'id'          => $row['id'],
				'body'        => $row['body'],
				'multichoice' => $row['multichoice'],
				'quizId'      => $row['quizId'],
				'image'       => $row['image'],
			];
		}
		return $question;
	}

	static public function getNextQuestion($questionId, $quizId) {
		$question = null;
		$query    = \Drupal::database()->select('quiz_question', 'q')
		                            ->fields('q', ['id', 'body', 'multichoice', 'quizId', 'image'])
		                            ->condition('quizId', [$quizId])
		                            ->orderBy('id', 'DESC');

		if ($questionId != '0') {
			$query->condition('id', [$questionId], '>');
		}
		$result = $query->execute();
		while ($row = $result->fetchAssoc()) {
			$question = [
				'id'          => $row['id'],
				'body'        => $row['body'],
				'multichoice' => $row['multichoice'],
				'quizId'      => $row['quizId'],
				'image'       => $row['image'],
			];
		}
		return $question;
	}

	static public function addAnswer($answer) {

		try {
			\Drupal::database()->insert('quiz_answer')
			                   ->fields([
					'body',
					'status',
					'questionId',
				])
			->values(array(
					$answer['body'],
					$answer['status'],
					$answer['questionId'],
				))
			->execute();

			drupal_set_message('Answer added successfully');

		} catch (\Exception $e) {
			drupal_set_message('Error happen when adding answer');
		}
	}

	static public function getAllAnswers($questionId) {
		$result = \Drupal::database()->select('quiz_answer', 'a')
		                             ->fields('a', ['id', 'body', 'status', 'questionId'])
		                             ->condition('questionId', [$questionId])
		                             ->execute();
		$answers = [];
		while ($row = $result->fetchAssoc()) {
			array_push($answers, [
					'id'         => $row['id'],
					'body'       => $row['body'],
					'status'     => $row['status'],
					'questionId' => $row['questionId']
				]);
		}
		return $answers;
	}
	static public function getAnswer($id) {
		$result = \Drupal::database()->select('quiz_answer', 'a')
		                             ->fields('a', ['id', 'body', 'status', 'questionId'])
		                             ->condition('id', [$id])
		                             ->execute();

		while ($row = $result->fetchAssoc()) {
			return [
				'id'         => $row['id'],
				'body'       => $row['body'],
				'status'     => $row['status'],
				'questionId' => $row['questionId']
			];
		}
	}
	static public function changeAnswerStatus($answer) {
		if (self::getQuestionById($answer['questionId'])['multichoice']) {
			# code...
		} else {
			\Drupal::database()->update('quiz_answer')
			                   ->condition('questionId', [$answer['questionId']])
			                   ->fields([
					'status' => 0,

				])
				->execute();
			\Drupal::database()->update('quiz_answer')
			                   ->condition('id', [$answer['answerId']])
			                   ->fields([
					'status' => 1,

				])
				->execute();
		}
	}

	static public function getTrueAnswers($questionId) {
		$result = \Drupal::database()->select('quiz_answer', 'a')
		                             ->fields('a', ['status'])
		                             ->condition('questionId', [$questionId])
		                             ->condition('status', [1])
		                             ->execute();
		$trueAnswers = [];
		while ($row = $result->fetchAssoc()) {
			array_push($trueAnswers, [
					'status' => $row['status'],
				]);
		}
		return $trueAnswers;
	}

	static public function changeAnswerStatusMulti($answer) {
		if (self::getQuestionById($answer['questionId'])['multichoice']) {
			if (self::getAnswer($answer['answerId'])['status']) {

				if (count(self::getTrueAnswers($answer['questionId'])) > 1) {
					\Drupal::database()->update('quiz_answer')->condition('id', [$answer['answerId']])->fields(['status' => 0, ])->execute();
				} else {
					drupal_set_message('The question must have one true answer at least ');
				}
			} else {
				\Drupal::database()->update('quiz_answer')
				                   ->condition('id', [$answer['answerId']])
				                   ->fields(['status' => 1, ])
					->execute();
			}
		}
	}

	static public function deleteAnswer($answer) {
		if ($answer['status']) {
			drupal_set_message('You can not delete it until you change it to false answer or make another true answer');
		} else {
			$query = \Drupal::database()->delete('quiz_answer', [])
			                            ->condition('id', [$answer['answerId']])
			                            ->execute();
		}
	}

	static public function deleteQuestion($id) {
		foreach (self::getAllAnswers($id) as $answer) {
			self::deleteAnswer($answer['id']);
		}
		$query = \Drupal::database()->delete('quiz_question', [])
		                            ->condition('id', [$id])
		                            ->execute();
	}

	static public function deleteQuiz($id) {
		foreach (self::getAllQuestions($id) as $question) {
			self::deleteQuestion($question['id']);
		}

		$query = \Drupal::database()->delete('quiz_user_quizzes', [])
		                            ->condition('quizId', [$id])
		                            ->execute();

		$query = \Drupal::database()->delete('quiz', [])
		                            ->condition('id', [$id])
		                            ->execute();
	}

	static public function editQuestion($question, $imageUrl) {
		if ($imageUrl) {
			\Drupal::database()->update('quiz_question')
			                   ->condition('id', [$question['id']])
			                   ->fields([
					'image' => $imageUrl,
				])
				->execute();
		}
		if ($question['multichoice']) {
			\Drupal::database()->update('quiz_question')
			                   ->condition('id', [$question['id']])
			                   ->fields([
					'body'        => $question['body'],
					'multichoice' => $question['multichoice'],

				])
				->execute();
			drupal_set_message('Changes saved successfully');
		} else {
			if (count(self::getTrueAnswers($question['id'])) == 1) {
				\Drupal::database()->update('quiz_question')
				                   ->condition('id', [$question['id']])
				                   ->fields([
						'body'        => $question['body'],
						'multichoice' => $question['multichoice'],

					])
					->execute();
			} else {
				drupal_set_message('To change from multichoice to one choice you have to add only one true answer ');
			}
		}
	}

	static public function addUser($user) {

		try {
			\Drupal::database()->insert('quiz_user')
			                   ->fields([
					'name',
					'email',
					'password',
					'status',
				])
			->values(array(
					$user['name'],
					$user['email'],
					$user['password'],
					$user['status'],
				))
			->execute();

			drupal_set_message('User added successfully');

		} catch (\Exception $e) {
			drupal_set_message('Error happen when adding user');
		}
	}

	static public function deleteUser($id) {

		$result = \Drupal::database()->select('quiz_try', 'q')
		                             ->fields('q', ['id'])
		                             ->condition('userId', [$id])
		                             ->execute();
		$trys = [];
		while ($row = $result->fetchAssoc()) {
			array_push($trys, [
					'id' => $row['id'],
				]);
		}
		foreach ($trys as $try) {
			self::deleteTry($try);
		}
		$query = \Drupal::database()->delete('quiz_user', [])
		                            ->condition('id', [$id])
		                            ->execute();
	}
	static public function getAllUsers() {
		$result = \Drupal::database()->select('quiz_user', 'u')
		                             ->fields('u', ['id', 'name', 'email', 'password', 'status'])
		                             ->execute();
		$users = [];
		while ($row = $result->fetchAssoc()) {
			array_push($users, [
					'id'       => $row['id'],
					'name'     => $row['name'],
					'email'    => $row['email'],
					'password' => $row['password'],
					'status'   => $row['status'],
				]);
		}
		return $users;
	}

	static public function getUser($id) {
		$result = \Drupal::database()->select('quiz_user', 'u')
		                             ->fields('u', ['id', 'name', 'email', 'password', 'status'])
		                             ->condition('id', [$id])
		                             ->execute();

		while ($row = $result->fetchAssoc()) {
			$user = [
				'id'       => $row['id'],
				'name'     => $row['name'],
				'email'    => $row['email'],
				'password' => $row['password'],
				'status'   => $row['status'],
			];
		}
		return $user;
	}

	static public function getUserByEmail($email) {
		$user   = null;
		$result = \Drupal::database()->select('quiz_user', 'u')
		                             ->fields('u', ['id', 'name', 'email', 'password', 'status'])
		                             ->condition('email', [$email])
		                             ->execute();

		while ($row = $result->fetchAssoc()) {
			$user = [
				'id'       => $row['id'],
				'name'     => $row['name'],
				'email'    => $row['email'],
				'password' => $row['password'],
				'status'   => $row['status'],
			];
		}
		return $user;
	}

	static public function assignQuiz($assign) {
		\Drupal::database()->insert('quiz_user_quizzes')
		                   ->fields([
				'userId',
				'quizId',
			])
		->values(array(
				$assign['userId'],
				$assign['quizId'],
			))
		->execute();
	}

	static public function unAssignQuiz($userQuiz) {
		$query = \Drupal::database()->delete('quiz_user_quizzes', [])
		                            ->condition('userId', [$userQuiz['userId']])
		                            ->condition('quizId', [$userQuiz['quizId']])
		                            ->execute();
	}

	static public function getUserQuizes($userId) {
		$result = \Drupal::database()->select('quiz_user_quizzes', 'u')
		                             ->fields('u', ['quizId'])
		                             ->condition('userId', [$userId])
		                             ->execute();
		$quizesIds = [];
		while ($row = $result->fetchAssoc()) {
			array_push($quizesIds, [
					'id' => $row['quizId'],
				]);
		}
		$quizes = [];
		foreach ($quizesIds as $quiz) {
			array_push($quizes, self::getQuiz($quiz['id']));
		}
		return $quizes;
	}

	static public function editUser($user) {

		try {
			\Drupal::database()->update('quiz_user')
			                   ->condition('id', [$user['id']])
			                   ->fields([
					'name'     => $user['name'],
					'email'    => $user['email'],
					'password' => $user['password'],
					'status'   => $user['status'],

				])
				->execute();

			drupal_set_message('Changes saved successfully');

		} catch (\Exception $e) {
			drupal_set_message('Error happen when editing');
		}
	}

	static public function login($login) {
		if ($user = self::getUserByEmail($login['email'])) {
			if ($user['password'] == $login['password']) {
				if ($user['status']) {
					if (session_status() == PHP_SESSION_NONE) {
						session_start();
					}
					$_SESSION['login_user'] = $user;
					$_SESSION['timeout']    = time()+(30*60);
					$response               = new RedirectResponse('/userquiz');
					$response->send();
					drupal_set_message('login successfully');
				} else {
					drupal_set_message('The account is disable contact admin');
				}

			} else {
				drupal_set_message('The password is wrong');
			}
		} else {
			drupal_set_message('This email is not reqistered');
		}
	}

	static public function timeout() {
		if (isset($_SESSION['timeout']) && time() > $_SESSION['timeout']) {
			if (isset($_SESSION['login_user'])) {
				unset($_SESSION['login_user']);
				unset($_SESSION['timeout']);
			}
		}
	}

	static public function result($tryId, $quizId, $questionId, $answer) {
		$question = self::getQuestionById($questionId);
		if (self::getAllAnswers($questionId)) {

			if ($question['multichoice']) {
				$correctAnswers = self::getCorrectAnswers($questionId);
				$score          = 0;
				$correct        = 0;
				$wrong          = 0;
				foreach ($answer as $id) {

					if (self::getAnswer($id)['status']) {
						$correct++;
					} else {
						$wrong++;
					}
				}
				$correct = $correct-$wrong;
				if ($correct > 0) {
					$score = $correct/count($correctAnswers);
				}
				\Drupal::database()->insert('quiz_result')
				                   ->fields([
						'tryId',
						'quizTitle',
						'question',
						'score',

					])
				->values([
						$tryId,
						self::getQuiz($quizId)['title'],
						$question['body'],
						$score,
					])
				->execute();
				$resultId = self::getLast('quiz_result');
				foreach ($answer as $id) {
					self::addUserAnswer(self::getAnswer($id)['body'], $resultId);
				}
				self::addCorrectAnswers($correctAnswers, $resultId);

			} else {
				if ($answer == self::getCorrectAnswer($questionId)['body']) {
					$score         = 1;
				} else { $score = 0;}
				\Drupal::database()->insert('quiz_result')
				                   ->fields([
						'tryId',
						'quizTitle',
						'question',
						'score',

					])
				->values([
						$tryId,
						self::getQuiz($quizId)['title'],
						$question['body'],
						$score,
					])
				->execute();
				$resultId = self::getLast('quiz_result');
				self::addCorrectAnswer($questionId, $resultId);
				self::addUserAnswer($answer, $resultId);
			}
		}
	}

	static public function addUserAnswer($body, $resultId) {
		\Drupal::database()->insert('quiz_user_answer')
		                   ->fields(['body', 'resultId'])
		                   ->values([$body, $resultId])
		                   ->execute();

	}

	static public function addCorrectAnswer($questionId, $resultId) {
		\Drupal::database()->insert('quiz_correct_answer')
		                   ->fields(['body', 'resultId'])
		                   ->values([self::getCorrectAnswer($questionId)['body'], $resultId])
		                   ->execute();
	}

	static public function addCorrectAnswers($answers, $resultId) {
		foreach ($answers as $answer) {
			\Drupal::database()->insert('quiz_correct_answer')
			                   ->fields(['body', 'resultId'])
			                   ->values([$answer['body'], $resultId])
			                   ->execute();
		}

	}

	static function getCorrectAnswer($questionId) {
		foreach (self::getAllAnswers($questionId) as $answer) {
			if ($answer['status']) {
				return $answer;
			}
		}
	}

	static function getCorrectAnswers($questionId) {
		$answers = [];
		foreach (self::getAllAnswers($questionId) as $answer) {
			if ($answer['status']) {
				array_push($answers, $answer);
			}
		}
		return $answers;
	}

	static public function getResult($tryId) {
		$tryScore    = 0;
		$questionNum = 0;
		$result      = \Drupal::database()->select('quiz_result', 'u')
		                             ->fields('u', ['id', 'tryId', 'quizTitle', 'question', 'score'])
		                             ->condition('tryId', [$tryId])
		                             ->execute();
		$quiz_result = ['more' => '0', 'tryScore' => 0, ];
		while ($row = $result->fetchAssoc()) {
			$tryScore = $tryScore+(double) $row['score'];
			$questionNum++;
			array_push($quiz_result, [
					'id'    => $row['id'],
					'tryId' => $row['tryId'],

					'quizTitle'      => $row['quizTitle'],
					'question'       => $row['question'],
					'score'          => $row['score'],
					'correctAnswers' => self::getResultCorrectAnswers($row['id']),
					'userAnswers'    => self::getResultUserAnswers($row['id']),
				]);
		}
		if ($questionNum > 0) {
			$persetScore = ($tryScore*100)/$questionNum;
		} else {
			$persetScore = 0;
		}
		$quiz_result['tryScore'] = $persetScore;
		self::setTryScore($persetScore, $tryId);
		return $quiz_result;
	}

	static public function setTryScore($tryScore, $tryId) {
		\Drupal::database()->update('quiz_try')
		                   ->condition('id', [$tryId])
		                   ->fields(['score' => $tryScore, ])
			->execute();
	}

	static public function getResultCorrectAnswers($resultId) {
		$result = \Drupal::database()->select('quiz_correct_answer', 'u')
		                             ->fields('u', ['id', 'body', 'resultId'])
		                             ->condition('resultId', [$resultId])
		                             ->execute();
		$answers = [];
		while ($row = $result->fetchAssoc()) {
			array_push($answers, [
					'id'       => $row['id'],
					'body'     => $row['body'],
					'resultId' => $row['resultId'],
				]);
		}
		return $answers;
	}
	static public function getResultUserAnswers($resultId) {
		$result = \Drupal::database()->select('quiz_user_answer', 'u')
		                             ->fields('u', ['id', 'body', 'resultId'])
		                             ->condition('resultId', [$resultId])
		                             ->execute();
		$answers = [];
		while ($row = $result->fetchAssoc()) {
			array_push($answers, [
					'id'       => $row['id'],
					'body'     => $row['body'],
					'resultId' => $row['resultId'],
				]);
		}
		return $answers;
	}
	static public function addTry($userId, $quizTitle) {
		$date = date("Y-m-d H:i:s");
		\Drupal::database()->insert('quiz_try')
		                   ->fields(['quizTitle', 'userId', 'date'])
		                   ->values([
				$quizTitle,
				$userId,
				date("Y-m-d H:i:s"),
			])
		->execute();
		return self::getLast('quiz_try');
	}

	static public function getTries($userId = null) {
		$query = \Drupal::database()->select('quiz_try', 'q')
		                            ->fields('q', ['id', 'quizTitle', 'score', 'userId', 'date'])
		                            ->orderBy('id', 'DESC');
		if ($userId != null) {
			$query->condition('userId', [$userId]);
		}

		$result = $query->execute();

		$tries = [];
		while ($row = $result->fetchAssoc()) {
			array_push($tries, [
					'id'        => $row['id'],
					'quizTitle' => $row['quizTitle'],
					'score'     => $row['score'],
					'user'      => self::getUser($row['userId']),
					'date'      => $row['date'],
				]);
		}

		return $tries;
	}

	static public function getTry($id) {
		$result = \Drupal::database()->select('quiz_try', 'q')
		                             ->fields('q', ['id', 'quizTitle', 'score', 'userId', 'date'])
		                             ->condition('id', [$id])
		                             ->execute();

		while ($row = $result->fetchAssoc()) {
			return [
				'id'        => $row['id'],
				'quizTitle' => $row['quizTitle'],
				'score'     => $row['score'],
				'user'      => self::getUser($row['userId']),
				'date'      => $row['date'],
			];
		}

	}

	static public function deleteTry($try) {

		$result = \Drupal::database()->select('quiz_result', 'u')
		                             ->fields('u', ['id'])
		                             ->condition('tryId', [$try['id']])
		                             ->execute();
		$resultIds = [];
		while ($row = $result->fetchAssoc()) {
			array_push($resultIds, [
					'id' => $row['id'],
				]);
		}

		foreach ($resultIds as $result) {
			$query = \Drupal::database()->delete('quiz_user_answer', [])
			                            ->condition('resultId', [$result['id']])
			                            ->execute();

			$query = \Drupal::database()->delete('quiz_correct_answer', [])
			                            ->condition('resultId', [$result['id']])
			                            ->execute();
		}

		$query = \Drupal::database()->delete('quiz_result', [])
		                            ->condition('tryId', [$try['id']])
		                            ->execute();

		$query = \Drupal::database()->delete('quiz_try', [])
		                            ->condition('id', [$try['id']])
		                            ->execute();
		drupal_set_message('Successfully deleted');
	}

	static public function sendResult($result, $quizId, $user) {
		$quiz = self::getQuiz($quizId);
		if ($quiz['send_email']) {

			$body = 'The score is : '.$result['tryScore'].'\n your answers are: \n';
			foreach ($result as $row) {
				$body = $body+$row['question']+' \n answer : \n';
				foreach ($row['userAnswers'] as $answer) {
					$body = $body+$answer['body']+'\n';
				}
				$body = $body+'score: '+$row['score']+'\n';
			}

			$mailManager       = \Drupal::service('plugin.manager.mail');
			$module            = 'quiz';
			$key               = 'result';
			$to                = $user['email'];
			$params['message'] = $body;
			$params['title']   = 'Your result for quiz: '.$quiz['title'].' : '.$result['tryScore'];
			$langcode          = \Drupal::currentUser()->getPreferredLangcode();
			$send              = true;

			$result = $mailManager->mail($module, $key, $to, $langcode, $params, NULL, $send);
			if ($result['result'] !== true) {
				$message = t('There was a problem sending your email notification to @email.', array('@email' => $to));
				drupal_set_message($message, 'error');
				\Drupal::logger('mail-log')->error($message);
				return;
			}

			$message = t('An email notification has been sent to @email ', array('@email' => $to));
			drupal_set_message($message);
			\Drupal::logger('mail-log')->notice($message);

		}
	}
}