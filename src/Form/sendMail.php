<?php

namespace Drupal\quiz\Form;

/**
 * @file
 * Contains \Drupal\quiz\Form\addQuizForm.
 */

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class sendMail extends FormBase {
	/**
	 * {@inheritdoc}
	 */
	public function getFormId() {
		return 'sendMail';
	}

	/**
	 * {@inheritdoc}
	 */
	public function buildForm(array $form, FormStateInterface $form_state) {
		$form['title'] = array(
			'#type'        => 'textfield',
			'#placeholder' => t('Title'),
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

		$mailManager       = \Drupal::service('plugin.manager.mail');
		$module            = 'quiz';
		$key               = 'my_key';// Replace with Your key
		$to                = \Drupal::currentUser()->getEmail();
		$params['message'] = $message;
		$params['title']   = $label;
		$langcode          = \Drupal::currentUser()->getPreferredLangcode();
		$send              = true;

		$result = $mailManager->mail($module, $key, $to, $langcode, $params, NULL, $send);
		if ($result['result'] !== true) {
			$message = t('There was a problem sending your email notification to @email.', array('@email' => $to));
			drupal_set_message($message, 'error');
			\Drupal::logger('mail-log')->error($message);
			return;
		}

		$message = t('An email notification has been sent to @email ', array('@email' => $to));
		drupal_set_message($message);
		\Drupal::logger('mail-log')->notice($message);

	}

}
