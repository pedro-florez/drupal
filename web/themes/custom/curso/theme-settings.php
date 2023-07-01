<?php

/**
 * Implements hook_form_system_theme_settings_alter()
 */

function curso_form_system_theme_settings_alter(
    &$form, 
    \Drupal\Core\Form\FormStateInterface $form_state
    /* $form_id */
) {

    # Validar si existe form_id para no modificar el formulario
    /* if ( isset($form_id) ) {
        return;
    } */

    // Add a checkbox to toggle the breadcrumb trail.
    $form['curso_nombre'] = [
      '#type' => 'textfield',
      '#title' => t('Nombre Custom'),
      '#default_value' => theme_get_setting('curso_nombre')
    ];

    $form['curso_descripcion'] = [
        '#type' => 'textfield',
        '#title' => t('Descripcion Custom'),
        '#default_value' => theme_get_setting('curso_descripcion')
    ];
}