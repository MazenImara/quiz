<?php

namespace Drupal\quiz\Form;

/**
 * @file
 * Contains \Drupal\quiz\Form\addAnswerForm.
 */

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use \Drupal\quiz\Classes\quizMethods;

class addAnswerForm extends FormBase {
	/**
	 * {@inheritdoc}
	 */
	public function getFormId() {
		return 'addAnswer';
	}

	/**
	 * {@inheritdoc}
	 */
	public function buildForm(array $form, FormStateInterface $form_state) {
		$form['body'] = array(
			'#type'        => 'textarea',
			'#placeholder' => t('Answer.'),
			'#required'    => TRUE,
			'#resizable'   => TRUE,
		);
		$form['status']['status'] = array(
			'#type'  => 'radios',
			'#title' => $this->t('status'),
			//'#default_value' => 1,
			'#options'  => array(0 => $this->t('False'), 1 => $this->t('True')),
			'#required' => TRUE,
		);
		$form['questionId'] = array(
			'#type'        => 'textfield',
			'#placeholder' => t('questionId'),
			'#required'    => TRUE,
		);
		$form['actions']['#type']  = 'actions';
		$form['actions']['submit'] = array(
			'#type'        => 'submit',
			'#value'       => $this->t('Create'),
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

		quizMethods::addAnswer($form_state->getValues());
	}

}