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
	public function buildForm(array $form, FormStateInterface $form_state) {
		$form['image'] = array(
			'#title' => t('Question Image'),
			'#type'  => 'managed_file',
			//'#description'     => t('The uploaded image will be displayed on screen image.'),
			'#default_value'   => isset($config['photo'])?$config['photo']:'',
			'#upload_location' => 'public://images/',
			'#required'        => FALSE,
			'#theme'           => '',
		);
		$form['body'] = array(
			'#type'        => 'textarea',
			'#placeholder' => t('Question?'),
			'#required'    => TRUE,
			'#resizable'   => TRUE,
		);
		$form['multichoice'] = array(
			'#type'  => 'checkbox',
			'#title' => $this->t('Multichoice'),
			//'#default_value' => 0,
		);
		$form['id'] = array(
			'#type'        => 'textfield',
			'#placeholder' => t('id'),
			'#required'    => TRUE,
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
		if ($image = $form_state->getValue('image')) {
			$file = File::load($image[0]);
			$file->setPermanent();
			$file->save();
			$imgurl = file_create_url($file->getFileUri());
		}
		quizMethods::editQuestion($form_state->getValues(), $imgurl);

	}

}
