<?php

namespace Drupal\quiz\Classes;

class quizMethods {

	public static function addQuiz($quiz) {
		foreach ($quiz as $key => $value) {
			drupal_set_message($key.': '.$value);
		}
		drupal_set_message($quiz['title']);

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
	}

}