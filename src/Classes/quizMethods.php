<?php

namespace Drupal\quiz\Classes;
use Symfony\Component\HttpFoundation\RedirectResponse;

class quizMethods {

	public static function addQuiz($quiz) {
		try {
			\Drupal::database()->insert('quiz')
			                   ->fields([
					'title',
					'body'
				])
			->values(array(
					$quiz['title'],
					$quiz['body'],
				))
			->execute();
			drupal_set_message($quiz['title'].' added successfully');
		} catch (\Exception $e) {
			drupal_set_message('Error happen when adding quiz');
		}
	}

	static public function getAllQuizes() {
		$query = \Drupal::database()->select('quiz', 'q');
		$query->fields('q', ['id', 'title', 'body']);
		$result = $query->execute();
		$quizes = [];
		while ($row = $result->fetchAssoc()) {
			array_push($quizes, [
					'id'    => $row['id'],
					'title' => $row['title'],
					'body'  => $row['body'],
				]);
		}
		return $quizes;
	}

	static public function getQuiz($id) {
		$result = \Drupal::database()->select('quiz', 'q')
		                             ->fields('q', ['id', 'title', 'body'])
		                             ->condition('id', [$id])
		                             ->execute();
		while ($row = $result->fetchAssoc()) {
			$quiz = [
				'id'    => $row['id'],
				'title' => $row['title'],
				'body'  => $row['body'],
			];
		}
		return $quiz;
	}

	static public function addQuestion($question) {

		try {
			\Drupal::database()->insert('question')
			                   ->fields([
					'body',
					'multichoice',
					'quizId'
				])
			->values(array(
					$question['body'],
					$question['multichoice'],
					$question['quizId'],
				))
			->execute();

			drupal_set_message('Question added successfully');

			$response = new RedirectResponse('question/'.self::getQuestionByBody($question['body'])['id']);
			$response->send();

		} catch (\Exception $e) {
			drupal_set_message('Error happen when adding Question');
		}
	}

	static public function getAllQuestions($quizId) {
		$result = \Drupal::database()->select('question', 'q')
		                             ->fields('q', ['id', 'body', 'multichoice', 'quizId'])
		                             ->condition('quizId', [$quizId])
		                             ->execute();
		$questions = [];
		while ($row = $result->fetchAssoc()) {
			array_push($questions, [
					'id'          => $row['id'],
					'body'        => $row['body'],
					'multichoice' => $row['multichoice'],
					'quizId'      => $row['quizId'],
				]);
		}
		return $questions;
	}

	static public function getQuestionById($id) {
		$result = \Drupal::database()->select('question', 'q')
		                             ->fields('q', ['id', 'body', 'multichoice', 'quizId'])
		                             ->condition('id', [$id])
		                             ->execute();
		while ($row = $result->fetchAssoc()) {
			$question = [
				'id'          => $row['id'],
				'body'        => $row['body'],
				'multichoice' => $row['multichoice'],
				'quizId'      => $row['quizId'],
			];
		}
		return $question;
	}

	static public function getQuestionByBody($body) {
		$result = \Drupal::database()->select('question', 'q')
		                             ->fields('q', ['id', 'body', 'multichoice', 'quizId'])
		                             ->condition('body', [$body])
		                             ->execute();
		while ($row = $result->fetchAssoc()) {
			$question = [
				'id'          => $row['id'],
				'body'        => $row['body'],
				'multichoice' => $row['multichoice'],
				'quizId'      => $row['quizId'],
			];
		}
		return $question;
	}

	static public function getNextQuestion($questionId, $quizId) {
		$query = \Drupal::database()->select('question', 'q')
		                            ->fields('q', ['id', 'body', 'multichoice', 'quizId'])
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
			];
		}
		return $question;
	}

	static public function addAnswer($answer) {

		try {
			\Drupal::database()->insert('answer')
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
		$result = \Drupal::database()->select('answer', 'a')
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
		$result = \Drupal::database()->select('answer', 'a')
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
			\Drupal::database()->update('answer')
			                   ->condition('questionId', [$answer['questionId']])
			                   ->fields([
					'status' => 0,

				])
				->execute();
			\Drupal::database()->update('answer')
			                   ->condition('id', [$answer['answerId']])
			                   ->fields([
					'status' => 1,

				])
				->execute();
		}
	}

	static public function getTrueAnswers($questionId) {
		$result = \Drupal::database()->select('answer', 'a')
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
					\Drupal::database()->update('answer')->condition('id', [$answer['answerId']])->fields(['status' => 0, ])->execute();
				} else {
					drupal_set_message('The question must have one true answer at least ');
				}
			} else {
				\Drupal::database()->update('answer')
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
			$query = \Drupal::database()->delete('answer', [])
			                            ->condition('id', [$answer['answerId']])
			                            ->execute();
		}
	}

	static public function deleteQuestion($id) {
		foreach (self::getAllAnswers($id) as $answer) {
			self::deleteAnswer($answer['id']);
		}
		$query = \Drupal::database()->delete('question', [])
		                            ->condition('id', [$id])
		                            ->execute();
	}

	static public function deleteQuiz($id) {
		foreach (self::getAllQuestions($id) as $question) {
			self::deleteQuestion($question['id']);
		}
		$query = \Drupal::database()->delete('quiz', [])
		                            ->condition('id', [$id])
		                            ->execute();
	}

	static public function editQuestion($question) {
		if ($question['multichoice']) {
			\Drupal::database()->update('question')
			                   ->condition('id', [$question['id']])
			                   ->fields([
					'body'        => $question['body'],
					'multichoice' => $question['multichoice'],

				])
				->execute();
			drupal_set_message('Changes saved successfully');
		} else {
			if (count(self::getTrueAnswers($question['id'])) == 1) {
				\Drupal::database()->update('question')
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
		\Drupal::database()->insert('user_quizes')
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
		$query = \Drupal::database()->delete('user_quizes', [])
		                            ->condition('userId', [$userQuiz['userId']])
		                            ->condition('quizId', [$userQuiz['quizId']])
		                            ->execute();
	}

	static public function getUserQuizes($userId) {
		$result = \Drupal::database()->select('user_quizes', 'u')
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
				session_start();
				$_SESSION['login_user'] = $user;
				$response               = new RedirectResponse('/userquiz');
				$response->send();
				drupal_set_message('login successfully');
			} else {
				drupal_set_message('The password is wrong');
			}
		} else {
			drupal_set_message('This email is not reqistered');
		}
	}

	static public function result($tryId, $quizId, $questionId, $answer) {
		$question = self::getQuestionById($questionId);
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
			$result = \Drupal::database()->select('quiz_result', 'q')
			                             ->fields('q', ['id'])
			                             ->orderBy('id', 'ASC')
			                             ->execute();
			while ($row = $result->fetchAssoc()) {
				$resultId = $row['id'];
			}

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
			$result = \Drupal::database()->select('quiz_result', 'q')
			                             ->fields('q', ['id'])
			                             ->orderBy('id', 'ASC')
			                             ->execute();
			while ($row = $result->fetchAssoc()) {
				$resultId = $row['id'];
			}
			self::addCorrectAnswer($questionId, $resultId);
			self::addUserAnswer($answer, $resultId);
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
		\Drupal::database()->insert('quiz_try')
		                   ->fields(['quizTitle', 'userId'])
		                   ->values([$quizTitle, $userId, ])
		                   ->execute();
		$result = \Drupal::database()->select('quiz_try', 'q')
		                             ->fields('q', ['id'])
		                             ->orderBy('id', 'DESC')
		                             ->execute();
		while ($row = $result->fetchAssoc()) {
			return $row['id'];
		}
	}

	static public function getTries($userId) {
		$query = \Drupal::database()->select('quiz_try', 'q')
		                            ->fields('q', ['id', 'quizTitle', 'score', 'userId', 'date']);
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
}