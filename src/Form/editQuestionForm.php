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

class editQuestionForm extends FormBase {
	/**
	 * {@inheritdoc}
	 */
	public function getFormId() {
		return 'editQuestion';
	}

	/**
	 * {@inheritdoc}
	 */
	public function buildForm(array $form, FormStateInterface $form_state, $id = null) {
		$question = quizMethods::getQuestionById($id);
		$form['image'] = array(
			'#title' => t('Question Image'),
			'#type'  => 'managed_file',
			//'#description'     => t('The uploaded image will be displayed on screen image.'),
			'#default_value'   => isset($config['photo'])?$config['photo']:'',
			'#upload_location' => 'public://images/',
			'#required'        => FALSE,
			//'#theme'           => '',
		);
		$form['body'] = array(
			'#type'        => 'textarea',
			'#placeholder' => t('Question?'),
			'#required'    => TRUE,
			'#resizable'   => TRUE,
			'#default_value' => $question['body'],
		);
		$form['multichoice'] = array(
			'#type'  => (!$question['textChoice']) ? 'checkbox' : 'hidden',
			'#title' => $this->t('Multichoice'),
			'#default_value' => $question['multichoice'],
		);
		$form['showAgreement'] = array(
			'#type'  => ($question['textChoice']) ? 'checkbox' : 'hidden',
			'#title' => $this->t('Show agreement'),
			'#default_value' => $question['showAgreement'],
		);

		$form['textChoice'] = array(
			'#type'  => 'hidden',
			'#title' => $this->t('Show agreement'),
			'#value' => $question['textChoice'],
		);
		$form['id'] = array(
			'#type'        => 'hidden',
			'#placeholder' => t('id'),
			'#required'    => TRUE,
			'#value' => $question['id'],
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
		$imgurl = null;
		if ($image = $form_state->getValue('image')) {
			$file = File::load($image[0]);
			$file->setPermanent();
			$file->save();
			$imgurl = file_create_url($file->getFileUri());
		}
		quizMethods::editQuestion($form_state->getValues(), $imgurl);

	}

}
