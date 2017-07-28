<?php

namespace Drupal\quiz\Classes;

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
		} catch (\Exception $e) {
			drupal_set_message('Error happen when adding quiz');
		}
	}

}