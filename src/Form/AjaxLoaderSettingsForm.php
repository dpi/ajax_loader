<?php

namespace Drupal\ajax_loader\Form;

use Drupal\ajax_loader\ThrobberManagerInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class AjaxLoaderSettingsForm
 * @package Drupal\ajax_throbber\Form
 */
class AjaxLoaderSettingsForm extends ConfigFormBase {

  protected $throbberManager;

  public function __construct(ConfigFactoryInterface $config_factory, ThrobberManagerInterface $throbber_manager) {
    parent::__construct($config_factory);

    $this->throbberManager = $throbber_manager;
  }

  /**
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   * @return static
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('ajax_loader.throbber_manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'ajax_throbber_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['ajax_throbber.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $settings = $this->config('ajax_throbber.settings');

    $form['wrapper'] = array(
      '#prefix' => '<div id="throbber-wrapper">',
      '#suffix' => '</div>'
    );

    $form['wrapper']['throbber'] = array(
      '#type' => 'select',
      '#title' => t('Throbber'),
      '#description' => t('Choose your throbber'),
      '#options' => $this->throbberManager->getThrobberOptionList(),
      '#default_value' => ($settings->get('throbber')) ? $settings->get('throbber') : NULL,
      '#ajax' => array(
        'wrapper' => 'throbber-wrapper',
        'callback' => array($this, 'ajaxThrobberChange'),
      ),
    );

    if (!empty($form_state->getValue('throbber')) || !empty($settings->get('throbber'))) {
      // Show preview of throbber.
      if (!empty($form_state->getValue('throbber'))) {
        $throbber = $this->throbberManager->loadThrobberInstance($form_state->getValue('throbber'));
      }
      else {
        $throbber = $this->throbberManager->loadThrobberInstance($settings->get('throbber'));
      }

      $form['wrapper']['throbber']['#attached']['library'] = array(
        'ajax_loader/ajax_loader.admin',
      );

      $form['wrapper']['throbber']['#suffix'] = '<span class="throbber-example">' . $throbber->getMarkup() . '</span>';
    }

    $form['hide_ajax_message'] = array(
      '#type' => 'checkbox',
      '#title' => t('Never show ajax loading message'),
      '#description' => t('Choose whether you want to hide the loading ajax message even when it is set.'),
      '#default_value' => ($settings->get('hide_ajax_message')) ? $settings->get('hide_ajax_message') : 0,
    );

    $form['always_fullscreen'] = array(
      '#type' => 'checkbox',
      '#title' => t('Always show loader as overlay (fullscreen)'),
      '#description' => t('Choose whether you want to show the loader as an overlay, no matter what the settings of the loader are.'),
      '#default_value' => ($settings->get('always_fullscreen')) ? $settings->get('always_fullscreen') : 0,
    );

    $form['show_admin_paths'] = array(
      '#type' => 'checkbox',
      '#title' => t('Use ajax loader on admin pages'),
      '#description' => t('Choose whether you also want to show the loader on admin pages or still like to use the default core loader.'),
      '#default_value' => ($settings->get('show_admin_paths')) ? $settings->get('show_admin_paths') : 0,
    );

    return parent::buildForm($form, $form_state);
  }

  /**
   * Ajax callback when throbber is changed.
   * @param $form
   * @param $form_state
   */
  public function ajaxThrobberChange($form, &$form_state) {
    return $form['wrapper'];
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('ajax_throbber.settings')
      ->set('throbber', $form_state->getValue('throbber'))
      ->set('hide_ajax_message', $form_state->getValue('hide_ajax_message'))
      ->set('always_fullscreen', $form_state->getValue('always_fullscreen'))
      ->set('show_admin_paths', $form_state->getValue('show_admin_paths'))
      ->save();

    // Clear cache, so that library is picked up.
    drupal_flush_all_caches();

    parent::submitForm($form, $form_state);
  }

}

