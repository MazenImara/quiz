<?php

namespace Drupal\quiz\Form;

/**
 * @file
 * Contains \Drupal\quiz\Form\addAnswerForm.
 */

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use \Drupal\quiz\Classes\quizMethods;

class assignQuizForm extends FormBase {
	/**
	 * {@inheritdoc}
	 */
	public function getFormId() {
		return 'assignQuiz';
	}

	/**
	 * {@inheritdoc}
	 */
	public function buildForm(array $form, FormStateInterface $form_state) {
		$form['userId'] = array(
			'#type'        => 'textfield',
			'#placeholder' => t('userId'),
			'#required'    => TRUE,
		);
		$form['quizId'] = array(
			'#type'        => 'textfield',
			'#placeholder' => t('quizId'),
			'#required'    => TRUE,
		);
		$form['actions']['#type']  = 'actions';
		$form['actions']['submit'] = array(
			'#type'        => 'submit',
			'#value'       => $this->t('Assign'),
			'#button_type' => 'primary',
		);
		return $form;
	}

	/**
	 * {@inheritdoc}
	 */
	public function validateForm(array&$form, FormStateInterface $form_state) {

	}

	/**
	 * {@inheritdoc}
	 */
	public function submitForm(array&$form, FormStateInterface $form_state) {

		quizMethods::assignQuiz($form_state->getValues());
	}

}