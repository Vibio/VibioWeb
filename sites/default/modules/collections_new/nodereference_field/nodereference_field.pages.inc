<?php
// $Id: nodereference_field.pages.inc,v 1.2 2009/06/12 19:58:18 boombatower Exp $

/**
 * @file
 * Provide functionality that can be lazy loaded.
 */

/**
 * Theme fieldset containing nodereference_autocreate and referenced fields.
 *
 * @param $fieldset
 *   Fieldset containing nodereference_autocreate field and referenced fields.
 * @return
 *   HTML output of table.
 */
function theme_nodereference_field_node_edit(array $fieldset) {
  drupal_add_css(drupal_get_path('module', 'nodereference_field') . '/nodereference_field.css');

  // Table format arrays, $row_description collections field description
  // information to be displayed bellow the table.
  $header = array();
  $rows = array();
  $row_description = array();

  // Extract nodereference_autocreate field from fieldset.
  $field = $fieldset[$fieldset['#field_name']];

  if (isset($field[$fieldset['#field_name'] . '_add_more'])) {
    // Store the add more button for use later since it needs to be rendered
    // outside the table instead of with the nodereference_autocreate field.
    $add_more = $field[$field['#field_name'] . '_add_more'];
    unset($field[$field['#field_name'] . '_add_more']);

    // Set up field name specific CSS id and class for use with tabledrag.
    $table_id = 'nodereference_field-' . $field['#field_name'];
    $weight_class = 'nodereference_field-' . $field['#field_name'] . '-weight';

    // Get a list of the referenced fields by removing the
    // nodereference_autocreate field.
    $referenced_fields = element_children($fieldset);
    $referenced_fields = array_diff($referenced_fields, array($field['#field_name']));

    // Extract the title and description information for display on top and
    // bottom of the table and remove it from the field definition so that it
    // does not render with the field inside the table.
    list($header[$field['#weight']], $row_description[$field['#weight']], $field) = nodereference_field_field_info_get($field);
    foreach ($referenced_fields as $key) {
      $referenced_field = &$fieldset[$key];
      list($header[$referenced_field['#weight']], $row_description[$referenced_field['#weight']], $referenced_field) = nodereference_field_field_info_get($referenced_field);
    }
    $header[100] = t('Weight');
    $row_description[100] = '&nbsp;';

    // Cycle through all rows contained by the nodereference_autocreate field
    // and render associated referenced fields in the same row.
    foreach (element_children($field) as $row_key) {
      $row = array();

      // Sore the weight so it can be rendered at the end of the row.
      $weight = $field[$row_key]['_weight'];
      unset($field[$row_key]['_weight']);

      // Render the nodereference_autocreate first.
      $row[$field['#weight']] = drupal_render($field[$row_key]);

      // Render the referenced fields in weight order. Be sure to use the
      // referenced field associated with the current row key.
      foreach ($referenced_fields as $key) {
        $referenced_field = $fieldset[$key];
        $row[$referenced_field['#weight']] = drupal_render($referenced_field[$row_key]);
      }

      // Render the weight field last and add the tabledrag clas.
      $weight['#attributes']['class'] = $weight_class;
      $row[100] = drupal_render($weight);

      // Sort row based on weights assigned to referenced fields in
      // administration interface.
      ksort($row);

      // Add the row with the tabledrag class.
      $rows[] = array(
        'data' => $row,
        'class' => 'draggable',
      );
    }

    // Append the field description row last.
    $rows[] = array(
      'data' => $row_description,
      'class' => 'description',
    );

    // Sort header based on weights assigned to referenced fields in
    // administration interface.
    ksort($header);

    // Render the table and add the tabldrag javascript.
    drupal_add_tabledrag($table_id, 'order', 'sibling', $weight_class);
    return theme('table', $header, $rows, array('id' => $table_id, 'class' => 'nodereference_field-edit')) . drupal_render($add_more);
  }
  else {
    // Single value so no tabledrag javascript required.
    foreach (element_children($fieldset) as $key) {
      $field = $fieldset[$key];

      // Store title and description for display at top and bottom of table and
      // remove the values from the field.
      list($header[], $row_description[], $field) = nodereference_field_field_info_get($field);

      $row[] = drupal_render($field);
    }

    return theme('table', $header, array($row), array('id' => 'nodereference_field-edit', 'class' => 'nodereference_field-edit'));
  }
}

/**
 * Extract the field's title and description attributes recursively and then
 * remove them from the field.
 *
 * @param $field
 *   Field to extract info from.
 * @param $title
 *   The current title, used internally.
 * @param $description
 *   The current description, used internally.
 * @return
 *   An array containing the field info.
 */
function nodereference_field_field_info_get(array $field, $title = NULL, $description = NULL) {
  // Extract the title or description if set and then set the value to blank.
  if (isset($field['#title']) && $field['#title']) {
    $title = $field['#title'];
    $field['#title'] = '';
  }
  if (isset($field['#description']) && $field['#description']) {
    $description = $field['#description'];
    $field['#description'] = '';
  }

  // Recursively scan the field's children for info.
  if ($children = element_children($field)) {
    foreach ($children as $key) {
      list($title, $description, $field[$key]) = nodereference_field_field_info_get($field[$key], $title, $description);
    }
  }

  return array($title, $description, $field);
}

/**
 * Modified copy of content_add_more_js().
 *
 * Allow nodereference_field to make the necessary changes to the multiple value
 * field during an AHAH add more event.
 *
 * @see content_add_more_js()
 */
function nodereference_field_js_add_more($type_name_url, $field_name) {
  $type = content_types($type_name_url);
  $field = content_fields($field_name, $type['type']);

  if (($field['multiple'] != 1) || empty($_POST['form_build_id'])) {
    // Invalid request.
    drupal_json(array('data' => ''));
    exit;
  }

  // Retrieve the cached form.
  $form_state = array('submitted' => FALSE);
  $form_build_id = $_POST['form_build_id'];
  $form = form_get_cache($form_build_id, $form_state);
  if (!$form) {
    // Invalid form_build_id.
    drupal_json(array('data' => ''));
    exit;
  }

  // NODEREFERENCE_FIELD: Since not executed by cck the include must be explicitly
  // included.
  require_once drupal_get_path('module', 'content') . '/includes/content.node_form.inc';

  // We don't simply return a new empty widget to append to existing ones, because
  // - ahah.js won't simply let us add a new row to a table
  // - attaching the 'draggable' behavior won't be easy
  // So we resort to rebuilding the whole table of widgets including the existing ones,
  // which makes us jump through a few hoops.

  // The form that we get from the cache is unbuilt. We need to build it so that
  // _value callbacks can be executed and $form_state['values'] populated.
  // We only want to affect $form_state['values'], not the $form itself
  // (built forms aren't supposed to enter the cache) nor the rest of $form_data,
  // so we use copies of $form and $form_data.
  $form_copy = $form;
  $form_state_copy = $form_state;
  $form_copy['#post'] = array();
  form_builder($_POST['form_id'], $form_copy, $form_state_copy);
  // Just grab the data we need.
  $form_state['values'] = $form_state_copy['values'];
  // Reset cached ids, so that they don't affect the actual form we output.
  form_clean_id(NULL, TRUE);

  // Sort the $form_state['values'] we just built *and* the incoming $_POST data
  // according to d-n-d reordering.
  unset($form_state['values'][$field_name][$field['field_name'] .'_add_more']);
  foreach ($_POST[$field_name] as $delta => $item) {
    $form_state['values'][$field_name][$delta]['_weight'] = $item['_weight'];
  }
  $form_state['values'][$field_name] = _content_sort_items($field, $form_state['values'][$field_name]);
  $_POST[$field_name] = _content_sort_items($field, $_POST[$field_name]);

  // Build our new form element for the whole field, asking for one more element.
  $form_state['item_count'] = array($field_name => count($_POST[$field_name]) + 1);
  $form_element = content_field_form($form, $form_state, $field);
  // Let other modules alter it.
  drupal_alter('form', $form_element, array(), 'content_add_more_js');

  // Add the new element at the right place in the (original, unbuilt) form.
  if (module_exists('fieldgroup') && ($group_name = _fieldgroup_field_get_group($type['type'], $field_name))) {
    $form[$group_name][$field_name] = $form_element[$field_name];
  }
  else {
    $form[$field_name] = $form_element[$field_name];
  }

  // FIELD_REFERENCE: Preform necessary additions for referenced fields.
  $referenceable_fields = variable_get('nodereference_field_' . $field_name . '_fields', array());
  nodereference_field_element_alter($form, $field, $referenceable_fields);

  // Save the new definition of the form.
  $form_state['values'] = array();
  form_set_cache($form_build_id, $form, $form_state);

  // Build the new form against the incoming $_POST values so that we can
  // render the new element.
  $delta = max(array_keys($_POST[$field_name])) + 1;
  $_POST[$field_name][$delta]['_weight'] = $delta;
  $form_state = array('submitted' => FALSE);
  $form += array(
    '#post' => $_POST,
    '#programmed' => FALSE,
  );
  $form = form_builder($_POST['form_id'], $form, $form_state);

  // Render the new output.
//  $field_form = (!empty($group_name)) ? $form[$group_name][$field_name] : $form[$field_name];
  $field_form = (!empty($group_name)) ? $form[$group_name][$field_name . '_container'] : $form[$field_name . '_container'];
  // We add a div around the new content to receive the ahah effect.
//  $field_form[$delta]['#prefix'] = '<div class="ahah-new-content">'. (isset($field_form[$delta]['#prefix']) ? $field_form[$delta]['#prefix'] : '');
//  $field_form[$delta]['#suffix'] = (isset($field_form[$delta]['#suffix']) ? $field_form[$delta]['#suffix'] : '') .'</div>';
  // Prevent duplicate wrapper.
  unset($field_form['#prefix'], $field_form['#suffix']);

  // If a newly inserted widget contains AHAH behaviors, they normally won't
  // work because AHAH doesn't know about those - it just attaches to the exact
  // form elements that were initially specified in the Drupal.settings object.
  // The new ones didn't exist then, so we need to update Drupal.settings
  // by ourselves in order to let AHAH know about those new form elements.
  $javascript = drupal_add_js(NULL, NULL);
  $output_js = isset($javascript['setting']) ? '<script type="text/javascript">jQuery.extend(Drupal.settings, '. drupal_to_js(call_user_func_array('array_merge_recursive', $javascript['setting'])) .');</script>' : '';

  $output = theme('status_messages') . drupal_render($field_form) . $output_js;

  // Using drupal_json() breaks filefield's file upload, because the jQuery
  // Form plugin handles file uploads in a way that is not compatible with
  // 'text/javascript' response type.
  $_GLOBALS['devel_shutdown'] =  FALSE;
  print drupal_to_js(array('status' => TRUE, 'data' => $output));
  exit;
}

/**
 * Render the specified field and referenced fields as a table.
 *
 * @param $field
 *   Field defintion from $node->content.
 * @return
 *   HTML output of fieldset and table.
 */
function theme_nodereference_field_node_view(array $field) {
  // Load the nested field definition, local node, and referenceable fields.
  $field = $field['field'];
  $node = $field['#node'];
  $referenceable_fields = variable_get('nodereference_field_' . $field['#field_name'] . '_fields', array());
  $referenceable_field_names = array_keys($referenceable_fields);

  $field_weight = $referenceable_fields['title']['weight'];

  // Build a table with the field and referenced fields.
  $header = array($field_weight => $field['#title']);
  $rows = array();
  $remove_keys = array();
  foreach ($field['items'] as $row_key => $item) {
    $row = array();
    if (!($row[$field_weight] = drupal_render($item))) {
      continue;
    }

    // Cycle through the children in $node->content and render each
    // referenceable field. In this way we ensure they are rendered in proper
    // weight order.
    foreach (element_children($node->content) as $key) {
      // Remove the prefix 'nodereference_field_' and check to see that it is in
      // the referenceable field names list.
      if (in_array(substr($key, 20), $referenceable_field_names)) {
        $weight = $node->content[$key]['#weight'];
        $element = $node->content[$key]['field'];

        // Only add the field title to the header once.
        if (!in_array($element['#title'], $header)) {
          $header[$weight] = $element['#title'];
        }

        // Render referenced field element and add to table row.
        $row[$weight] = drupal_render($element['items'][$row_key]);

        // Key track of the fields that have been rendered for removal.
        $remove_keys[$key] = $key;
      }
    }

    // Sort rows based on weights assigned to referenced fields in
    // administration interface.
    ksort($row);
    $rows[] = $row;
  }

  // Remove rendered fields.
  foreach ($remove_keys as $key) {
    unset($node->content[$key]);
  }

  // If one or more rows were generated then render table inside of fieldset.
  if ($rows) {
    // Sort header based on weights assigned to referenced fields in
    // administration interface.
    ksort($header);

    $fieldset = array(
      '#type' => 'fieldset',
      '#title' => variable_get('nodereference_field_' . $field['#field_name'] . '_title', ''),
      '#description' => variable_get('nodereference_field_' . $field['#field_name'] . '_description', ''),
      '#value' => theme('table', $header, $rows),
    );
    return drupal_render($fieldset);
  }
  return '';
}
