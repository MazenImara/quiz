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

	static public function deleteAnswer($id) {
		$query = \Drupal::database()->delete('answer', [])
		                            ->condition('id', [$id])
		                            ->execute();
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

}