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
		$form       = \Drupal::formBuilder()->getForm('Drupal\quiz\Form\addQuizForm');
		$deleteForm = \Drupal::formBuilder()->getForm('Drupal\quiz\Form\deleteQuizForm');
		return array(
			'#theme'      => 'main',
			'#content'    => [
				'form'       => $form,
				'deleteForm' => $deleteForm,
				'quizes'     => quizMethods::getAllQuizes(),
			],
		);
	}
	public function quiz($id) {
		$form       = \Drupal::formBuilder()->getForm('Drupal\quiz\Form\addQuestionForm');
		$deleteForm = \Drupal::formBuilder()->getForm('Drupal\quiz\Form\deleteQuestionForm');

		return array(
			'#theme'      => 'quiz',
			'#content'    => [
				'quiz'       => quizMethods::getQuiz($id),
				'form'       => $form,
				'deleteForm' => $deleteForm,
				'questions'  => quizMethods::getAllQuestions($id),
			],
		);
	}

	public function question($id) {
		$form       = \Drupal::formBuilder()->getForm('Drupal\quiz\Form\addAnswerForm');
		$deleteForm = \Drupal::formBuilder()->getForm('Drupal\quiz\Form\deleteAnswerForm');
		$editForm   = \Drupal::formBuilder()->getForm('Drupal\quiz\Form\editQuestionForm');
		return array(
			'#theme'      => 'question',
			'#content'    => [
				'question'   => quizMethods::getQuestionById($id),
				'form'       => $form,
				'deleteForm' => $deleteForm,
				'editForm'   => $editForm,
				'answers'    => quizMethods::getAllAnswers($id),
			],
		);
	}

}