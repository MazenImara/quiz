<?php

namespace Drupal\quiz\Form;

/**
 * @file
 * Contains \Drupal\quiz\Form\addAnswerForm.
 */

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use \Drupal\quiz\Classes\quizMethods;

class addUserForm extends FormBase {
	/**
	 * {@inheritdoc}
	 */
	public function getFormId() {
		return 'addUser';
	}

	/**
	 * {@inheritdoc}
	 */
	public function buildForm(array $form, FormStateInterface $form_state) {
		$form['name'] = array(
			'#type'        => 'textfield',
			'#placeholder' => t('Name'),
			'#required'    => TRUE,
		);
		$form['uniq'] = [
			'#type'        => 'textfield',
			'#required'    => TRUE,
			'#placeholder' => 'Email or Uniq Name',
		];
		$form['password'] = array(
			'#type'        => 'textfield',
			'#placeholder' => t('Password'),
			'#required'    => TRUE,
		);
		$form['status']['status'] = array(
			'#type'  => 'radios',
			'#title' => $this->t('status'),
			//'#default_value' => 1,
			'#options'  => array(1 => $this->t('Active'), 0 => $this->t('Unactive')),
			'#required' => TRUE,
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

		quizMethods::addUser($form_state->getValues());
	}

}