<?php

namespace Drupal\quiz\Form;

/**
 * @file
 * Contains \Drupal\quiz\Form\addQuizForm.
 */

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\file\Entity\File;
use \Drupal\quiz\Classes\quizMethods;

class addQuizForm extends FormBase {
	/**
	 * {@inheritdoc}
	 */
	public function getFormId() {
		return 'addQuiz';
	}

	/**
	 * {@inheritdoc}
	 */
	public function buildForm(array $form, FormStateInterface $form_state) {
		$form['image'] = array(
			'#title' => t('Quiz Image'),
			'#type'  => 'managed_file',
			//'#description'     => t('The uploaded image will be displayed on screen image.'),
			'#default_value'   => isset($config['photo'])?$config['photo']:'',
			'#upload_location' => 'public://images/',
			'#required'        => FALSE,
			//'#theme'           => '',
		);
		$form['title'] = array(
			'#type'        => 'textfield',
			'#placeholder' => t('Title'),
			'#required'    => TRUE,
		);
		$form['body'] = array(
			'#type'        => 'textarea',
			'#placeholder' => t('Quiz Description'),
			'#required'    => TRUE,
			'#resizable'   => TRUE,
		);
		$form['seccess'] = array(
			'#type'          => 'textfield',
			'#placeholder'   => t('Seccess %'),
			'#required'      => TRUE,
			'#default_value' => '86',
		);
		$form['send_email'] = array(
			'#type'  => 'checkbox',
			'#title' => $this->t('Send result by email'),
			//'#default_value' => 0,
		);
		$form['show_result'] = array(
			'#type'  => 'checkbox',
			'#title' => $this->t('Show result after quiz'),
			//'#default_value' => 0,
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
		$imgurl = null;
		if ($image = $form_state->getValue('image')) {
			$file = File::load($image[0]);
			$file->setPermanent();
			$file->save();
			$imgurl = file_create_url($file->getFileUri());
		}
		quizMethods::addQuiz($form_state->getValues(), $imgurl);
	}

}
