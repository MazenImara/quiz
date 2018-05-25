<?php

namespace Drupal\quiz\Form;

/**
 * @file
 * Contains \Drupal\quiz\Form\deleteAnswerForm.
 */

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use \Drupal\quiz\Classes\quizMethods;

class deleteQuestionForm extends FormBase {
	/**
	 * {@inheritdoc}
	 */
	public function getFormId() {
		return 'deleteQuestion';
	}

	/**
	 * {@inheritdoc}
	 */
	public function buildForm(array $form, FormStateInterface $form_state) {
		$form['questionId'] = array(
			'#type'        => 'textfield',
			'#placeholder' => t('questionId'),
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
			'#value'       => $this->t('Delete'),
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

		quizMethods::deleteQuestion($form_state->getValues()['questionId']);
		$response = new RedirectResponse('/quiz/'.$form_state->getValues()['quizId']);
		$response->send();
		drupal_set_message('Question has been deleted successfuly');
	}

}