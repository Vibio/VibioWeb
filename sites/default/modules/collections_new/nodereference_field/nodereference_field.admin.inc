<?php
// $Id: nodereference_field.admin.inc,v 1.1 2009/06/12 18:31:17 boombatower Exp $

/**
 * @file
 * Provide administration specific functionality.
 */

/**
 * Theme the referenceable fields into a draggable table.
 *
 * @param $fieldset
 *   Fieldset containing referenceable fields and related information.
 * @return
 *   HTML output of draggable table.
 */
function theme_nodereference_field_content_field_edit_form(array $fieldset) {
  $keys = element_children($fieldset);
  if (!$keys) {
    return '<div>' . t('No fields associated with referenced node type.') . '</div>';
  }

  drupal_add_css(drupal_get_path('module', 'block') .'/block.css', 'module', 'all', FALSE);
  $header = array(t('Label'), t('Name'), t('Type'), t('Status'), t('Weight'));
  $rows = array();

  foreach ($fieldset[current($keys)]['region']['#options'] as $category => $label) {
    drupal_add_tabledrag('nodereference_field', 'match', 'sibling', 'nodereference_field-region-select', 'nodereference_field-region-' . $category, NULL, FALSE);
    drupal_add_tabledrag('nodereference_field', 'order', 'sibling', 'nodereference_field-weight', 'nodereference_field-weight-' . $category);
    $row = array(
      array(
        'data' => $label,
        'colspan' => 5,
        'class' => 'region nodereference_field-' . $category,
      ),
    );
    $rows[] = array(
      'data' => $row,
      'class' => 'region',
    );

    $rows = array_merge($rows, theme_nodereference_field_content_field_edit_form_fields($fieldset, $category));
  }

  drupal_add_js(drupal_get_path('module', 'nodereference_field') . '/nodereference_field.js', 'module', 'footer');
  return theme('table', $header, $rows, array('id' => 'nodereference_field'));
}

/**
 * Theme individual section of referenceable fields.
 *
 * @param $fieldset
 *   Fieldset containing referenceable fields and related information.
 * @param $category
 *   Category of referenceable fields, either 'enabled' or 'disabled'.
 * @return
 *   Table rows for category.
 */
function theme_nodereference_field_content_field_edit_form_fields(array $fieldset, $category) {
  $rows = array();
  foreach (element_children($fieldset) as $key) {
    $field = $fieldset[$key];

    if ($field['region']['#value'] != $category) {
      continue;
    }

    $row = array();
    $row[] = $field['label']['#value'];
    $row[] = $field['name']['#value'];
    $row[] = $field['type']['#value'];

    $field['region']['#attributes']['class'] = 'nodereference_field-region-select nodereference_field-region-enabled';
    $row[] = drupal_render($field['region']);

    $field['weight']['#attributes']['class'] = 'nodereference_field-weight nodereference_field-weight-enabled';
    $row[] = drupal_render($field['weight']);

    $rows[] = array(
      'data' => $row,
      'class' => 'draggable'
    );
  }
  return $rows;
}
