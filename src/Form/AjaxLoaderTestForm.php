<?php
/**
 * Created by PhpStorm.
 * User: robin-entityone
 * Date: 02/05/2016
 * Time: 07:39
 */

namespace Drupal\ajax_loader\Form;


use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Class AjaxLoaderTestForm
 * @package Drupal\ajax_loader\Form
 */
class AjaxLoaderTestForm extends FormBase {

  /**
   * @inheritdoc.
   */
  public function getFormId() {
    return 'ajax_loader_test';
  }

  /**
   * @inheritdoc.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['test_link'] = array(
      '#type' => 'link',
      '#title' => 'Test',
      '#url' =>  new Url('ajax_loader.test'),
      '#ajax' => array(
        'wrapper' => 'my-wrapper',
        'callback' => array($this, 'ajaxCallback')
      ),
    );

    return $form;
  }

  /**
   * @inheritdoc.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

  }

}