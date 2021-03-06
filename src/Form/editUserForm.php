<?php

namespace Drupal\quiz\Form;

/**
 * @file
 * Contains \Drupal\quiz\Form\addAnswerForm.
 */

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use \Drupal\quiz\Classes\quizMethods;

class editUserForm extends FormBase {
	/**
	 * {@inheritdoc}
	 */
	public function getFormId() {
		return 'editUser';
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
			'#placeholder' => t('Email or Unique Name'),
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
			'#options'  => array(1 => $this->t('Active'), 0 => $this->t('Inactive')),
			'#required' => TRUE,
		);
		$form['id'] = array(
			'#type'        => 'textfield',
			'#placeholder' => t('id'),
			'#required'    => TRUE,
		);
		$form['actions']['#type']  = 'actions';
		$form['actions']['submit'] = array(
			'#type'        => 'submit',
			'#value'       => $this->t('save'),
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

		quizMethods::editUser($form_state->getValues());
	}

}