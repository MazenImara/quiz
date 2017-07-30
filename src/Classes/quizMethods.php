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
			drupal_set_message($quiz['title'] . ' added successfully');
		} catch (\Exception $e) {
			drupal_set_message('Error happen when adding quiz');
		}
	}

	static public function getAllQuizes()
	{
		$query = \Drupal::database()->select('quiz', 'q');
		$query->fields('q', ['id', 'title', 'body']);
		$result = $query->execute();
		$quizes = [];
		while ($row = $result->fetchAssoc()) {
			array_push($quizes, [
					'id' => $row['id'],
					'title' => $row['title'],
					'body' => $row['body'],
				]);
		}
		return $quizes;
	}
	static public function getQuiz($id)
	{
		$result = \Drupal::database()->select('quiz', 'q')
			->fields('q', ['id', 'title', 'body'])
			->condition('id', [$id] )
			->execute();	
		while ($row = $result->fetchAssoc()) {
			$quiz = [
					'id' => $row['id'],
					'title' => $row['title'],
					'body' => $row['body'],
			] ;
		}
		return $quiz;
	}
	static public function deleteQuiz($id)
	{
		$id = (int) $id;
		$query = \Drupal::database()->delete('quiz', 'q')
			->condition('id', [$id] )
			->execute();
			drupal_set_message($id . 'test');
	}

	static public function addQuestion($question)
	{

		try {
			\Drupal::database()->insert('question')
			                   ->fields([
					'body',
					'quizId'
				])
			->values(array(
					$question['body'],
					$question['quizId'],
				))
			->execute();
			
			drupal_set_message('Question added successfully');

			$response = new RedirectResponse('question/' . self::getQuestionByBody($question['body'])['id']);
			$response->send();

		} catch (\Exception $e) {
			drupal_set_message('Error happen when adding Question');
		}		
	}

	static public function getAllQuestions($quizId)
	{
		$result = \Drupal::database()->select('question', 'q')
			->fields('q', ['id', 'body', 'quizId'])
			->condition('quizId', [$quizId] )
			->execute();
		$questions = [];
		while ($row = $result->fetchAssoc()) {
			array_push($questions, [
					'id' => $row['id'],
					'body' => $row['body'],
					'quizId' => $row['quizId'],
				]);
		}
		return $questions;
	}

	static public function getQuestionById($id)
	{
		$result = \Drupal::database()->select('question', 'q')
			->fields('q', ['id', 'body', 'quizId'])
			->condition('id', [$id] )
			->execute();	
		while ($row = $result->fetchAssoc()) {
			$question = [
					'id' => $row['id'],
					'body' => $row['body'],
					'quizId' => $row['quizId'],
			] ;
		}
		return $question;
	}

	static public function getQuestionByBody($body)
	{
		$result = \Drupal::database()->select('question', 'q')
			->fields('q', ['id', 'body', 'quizId'])
			->condition('body', [$body] )
			->execute();	
		while ($row = $result->fetchAssoc()) {
			$question = [
					'id' => $row['id'],
					'body' => $row['body'],
					'quizId' => $row['quizId'],
			] ;
		}
		return $question;
	}	

}