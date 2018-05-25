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

class deleteAnswerForm extends FormBase {
	/**
	 * {@inheritdoc}
	 */
	public function getFormId() {
		return 'deleteAnswer';
	}

	/**
	 * {@inheritdoc}
	 */
	public function buildForm(array $form, FormStateInterface $form_state) {
		$form['answerId'] = array(
			'#type'        => 'textfield',
			'#placeholder' => t('answerId'),
			'#required'    => TRUE,
		);
		$form['questionId'] = array(
			'#type'        => 'textfield',
			'#placeholder' => t('questionId'),
			'#required'    => TRUE,
		);
		$form['status']['status'] = array(
			'#type'  => 'radios',
			'#title' => $this->t('status'),
			//'#default_value' => 1,
			'#options'  => array(0 => $this->t('False'), 1 => $this->t('True')),
			'#required' => TRUE,
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

		quizMethods::deleteAnswer($form_state->getValues());
		$response = new RedirectResponse('/quiz/question/'.$form_state->getValues()['questionId']);
		$response->send();

	}

}