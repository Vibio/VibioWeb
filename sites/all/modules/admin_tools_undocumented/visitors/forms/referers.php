<?php

// $Id: referers.php,v 1.1 2010/09/24 03:29:24 gashev Exp $

/**
 * @file
 * Referers form for visitors module.
 */

/**
 * Referer type constants.
 */
define('REFERER_TYPE_INTERNAL_PAGES', 1);
define('REFERER_TYPE_EXTERNAL_PAGES', 2);
define('REFERER_TYPE_ALL_PAGES',      3);

/**
 * Set to session info default values for visitors referer type.
 */
function visitors_set_session_referer_type() {
  if (!isset($_SESSION['referer_type'])) {
    $_SESSION['referer_type'] = REFERER_TYPE_EXTERNAL_PAGES;
  }
}

/**
 * Referers form data.
 */
function visitors_referers_form($form_state) {
  visitors_set_session_date_range();
  visitors_set_session_referer_type();

  $form = visitors_date_filter_form_block();

  $form['visitors_referer'] = array(
    '#type'          => 'fieldset',
    '#title'         => t('Referers type filter'),
    '#collapsible'   => FALSE,
    '#collapsed'     => FALSE,
    '#description'   => t('Choose referers type')
  );

  $form['visitors_referer']['referer_type'] = array(
    '#type' => 'select',
    '#title' => 'Referer type',
    '#default_value' => $_SESSION['referer_type'],
    '#options' => array(
        REFERER_TYPE_INTERNAL_PAGES => t('Internal pages'),
        REFERER_TYPE_EXTERNAL_PAGES => t('External pages'),
        REFERER_TYPE_ALL_PAGES      => t('All pages')
    ),
  );

  $form['submit'] = array(
    '#type'          => 'submit',
    '#value'         => t('Submit'),
  );

  return $form;
}

/**
 * Visitors referers form values validation.
 */
function visitors_referers_form_validate($form, &$form_state) {
  visitors_date_filter_form_validate($form, &$form_state);
}

/**
 * Submit values from referers form write to session info.
 */
function visitors_referers_form_submit($form, &$form_state) {
  visitors_date_filter_form_submit($form, $form_state);
  $_SESSION['referer_type'] = $form_state['values']['referer_type'];
}

/**
 * Build sql query from referer type value.
 *
 * @return string sql query.
 */
function visitors_referers_condition() {
  visitors_set_session_referer_type();
  switch ($_SESSION['referer_type']) {
    case REFERER_TYPE_INTERNAL_PAGES:
      $query = "AND visitors_referer LIKE '%%%s%%' AND visitors_referer <> ''";
      break;
    case REFERER_TYPE_EXTERNAL_PAGES:
      $query = "AND visitors_referer NOT LIKE '%%%s%%'";
      break;
    default:
      $query = '';
      break;
  }

  return $query;
}
