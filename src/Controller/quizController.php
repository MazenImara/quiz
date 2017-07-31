<?php

namespace Drupal\quiz\Controller;

use Drupal\Core\Controller\ControllerBase;
use \Drupal\quiz\Classes\quizMethods;

class quizController extends ControllerBase {

	/**
	 * Display the markup.
	 *
	 * @return array
	 */
	public function main() {
		$form = \Drupal::formBuilder()->getForm('Drupal\quiz\Form\addQuizForm');
		return array(
			'#theme'   => 'main',
			'#content' => [
				'form'    => $form,
				'quizes'  => quizMethods::getAllQuizes(),
			],
		);
	}
	public function quiz($id) {
		$form = \Drupal::formBuilder()->getForm('Drupal\quiz\Form\addQuestionForm');
		return array(
			'#theme'     => 'quiz',
			'#content'   => [
				'quiz'      => quizMethods::getQuiz($id),
				'form'      => $form,
				'questions' => quizMethods::getAllQuestions($id),
			],
		);
	}

	public function question($id) {
		$form = \Drupal::formBuilder()->getForm('Drupal\quiz\Form\addQuestionForm');
		return array(
			'#theme'    => 'question',
			'#content'  => [
				'question' => quizMethods::getQuestionById($id),
				//'form' => $form,
				//'questions' => quizMethods::getAllQuestions($id),
			],
		);
	}
}