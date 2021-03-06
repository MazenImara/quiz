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

class changeAnswerStatusForm extends FormBase {
	/**
	 * {@inheritdoc}
	 */
	public function getFormId() {
		return 'changeAnswerStatus';
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
		$form['answerId'] = array(
			'#type' => 'radios',
			//'#title' => $this->t('status'),
			//'#default_value' => 1,
			'#options'  => NULL,
			'#required' => TRUE,
		);
		$form['actions']['#type']  = 'actions';
		$form['actions']['submit'] = array(
			'#type'        => 'submit',
			'#value'       => $this->t('Save'),
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

		quizMethods::changeAnswerStatus($form_state->getValues());
		$response = new RedirectResponse('/quiz/question/'.$form_state->getValues()['questionId']);
		$response->send();

	}

}