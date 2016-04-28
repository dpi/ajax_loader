<?php

/**
 * Renders ajax loader settings form.
 * @param $form
 * @param $form_state
 */
function _ajax_loader_settings_form($form, &$form_state) {

  $settings = variable_get('ajax_loader_settings', array());

  $form['wrapper'] = array(
    '#prefix' => '<div id="throbber-wrapper">',
    '#suffix' => '</div>'
  );

  $form['wrapper']['throbber'] = array(
    '#type' => 'select',
    '#title' => t('Throbber'),
    '#description' => t('Choose your throbber'),
    '#required' => TRUE,
    '#options' => _ajax_loader_get_options(),
    '#default_value' => isset($settings['throbber']) ? $settings['throbber'] : NULL,
    '#ajax' => array(
      'wrapper' => 'throbber-wrapper',
      'callback' => 'ajax_loader_change',
    ),
  );

  if (!empty($form_state['values']['throbber']) || isset($settings['throbber'])) {
    // Show preview of throbber.
    if (!empty($form_state['values']['throbber'])) {
      $class = $form_state['values']['throbber'];
    }
    else {
      $class = $settings['throbber'];
    }

    /** @var ThrobberInterface $throbber */
    $throbber = new $class();
    $form['wrapper']['throbber']['#attached']['css'] = array(
      array(
        'data' => drupal_get_path('module', 'ajax_loader') . '/css/throbber-general.css',
        'type' => 'file',
      ),
      array(
        'data' => $throbber->getCssFile(),
        'type' => 'file',
      ),
      array(
        'data' => drupal_get_path('module', 'ajax_loader') . '/css/throbber-admin.css',
        'type' => 'file',
      ),
    );

    $form['wrapper']['throbber']['#suffix'] = '<span class="throbber-example">' . $throbber->getMarkup() . '</span>';
  }

  $form['custom_css'] = array(
    '#type' => 'textfield',
    '#title' => t('Custom css file'),
    '#description' => t('Add a custom css file to overwrite the default css added by this module. Eg: sites/all/themes/your-theme/throbber.css'),
    '#default_value' => isset($settings['custom_css']) ? $settings['custom_css'] : NULL,
  );

  $form['hide_ajax_message'] = array(
    '#type' => 'checkbox',
    '#title' => t('Never show ajax loading message'),
    '#description' => t('Choose whether you want to hide the loading ajax message even when it is set.'),
    '#default_value' => isset($settings['hide_ajax_message']) ? $settings['hide_ajax_message'] : 0,
  );

  $form['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Save'),
  );

  return $form;
}

/**
 * Ajax callback when throbber is changed.
 * @param $form
 * @param $form_state
 */
function ajax_loader_change($form, &$form_state) {
  return $form['wrapper'];
}

/**
 * Submit handler.
 * @param $form
 * @param $form_state
 */
function _ajax_loader_settings_form_submit($form, &$form_state) {
  $settings = array(
    'throbber' => $form_state['values']['throbber'],
    'custom_css' => $form_state['values']['custom_css'],
    'hide_ajax_message' => $form_state['values']['hide_ajax_message'],
  );

  variable_set('ajax_loader_settings', $settings);
  drupal_set_message(t('Your settings have been saved.'));
}

/**
 * Returns all throbbers as option array.
 * @return array
 */
function _ajax_loader_get_options() {
  $options = array('' => '- Select -');

  // Module definied throbbers.
  $options = array_merge($options, ThrobberTypes::toArray());
  // Custom user definied throbbers.
  if ($throbbers = ThrobberManager::processUserDefinedThrobbers()) {
    foreach ($throbbers as $throbber) {
      /** @var ThrobberInterface $throbber */
      $options = array_merge($options, array(get_class($throbber) => $throbber->getLabel()));
    }
  }

  return $options;
}