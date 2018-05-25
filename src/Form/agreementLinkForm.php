<?php
namespace Drupal\quiz\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure example settings for this site.
 */
class agreementLinkForm extends ConfigFormBase {
  /** 
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'agreementLinkForm';
  }

  /** 
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'quiz.settings',
    ];
  }

  /** 
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('quiz.settings');

    $form['link'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Agreement link'),
      '#default_value' => $config->get('agreement_link'),
      '#required' => true,
      '#description' => t('For external link add "http://" befor url'),
    );  



    return parent::buildForm($form, $form_state);
  }

  /** 
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {


       $link = $form_state->getValue('link');
       if(substr($link, 0,4)!="http"){
       	if(substr($link, 0,1)!='/'){
       		$link = '/' . $link;
       	}
       }
       //$link = str_replace("http://","",$link);
       //$link = str_replace("https://","",$link);
      // Retrieve the configuration
       $this->configFactory->getEditable('quiz.settings')
      // Set the submitted configuration setting
      ->set('agreement_link', $link)
      ->save();

    parent::submitForm($form, $form_state);
  }
}