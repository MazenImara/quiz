<?php

namespace Drupal\quiz\Form;

/**
 * @file
 * Contains \Drupal\quiz\Form\addQuestionForm.
 */

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\file\Entity\File;
use \Drupal\quiz\Classes\quizMethods;

class addTextFieldForm extends FormBase {
	/**
	 * {@inheritdoc}
	 */
	public function getFormId() {
		return 'addTextFieldForm';
	}

	/**
	 * {@inheritdoc}
	 */
	public function buildForm(array $form, FormStateInterface $form_state, $questionId = null) {
		$form['questionId'] = array(
			'#type'        => 'hidden',
			'#placeholder' => t('questionId'),
			'#value'    => $questionId,
		);
		$form['title'] = array(
			'#type'        => 'textfield',
			'#placeholder' => t('Title'),
			'#required'    => True,
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

		quizMethods::addTextField($form_state->getValues());

	}

}
