<?php
// $Id: contact_importer.admin.inc,v 1.1.2.9 2010/09/14 14:25:12 dalin Exp $

/**
 * @file
 * Admin functions for Contact Importer.
 */

/**
 * Admin info page.
 */
function contact_importer_info() {
  $output = '';

  // Check for requirements.
  $has_errors = _contact_importer_are_requirements_missing();
  $has_info = _contact_importer_are_requirements_missing(FALSE, TRUE);
  $output .= '<h3>'. t('Status') .'</h3>';
  if ($has_errors) {
    $output .= '<p>'. t('Contact Importer has not been setup correctly.  Please visit the '.
      '<a href="@URL">status report</a> to find out more.',
      array('@URL' => url('admin/reports/status'))) .'</p>';
  }
  elseif ($has_info) {
    $output .= '<p>'. t('Contact Importer has been setup correctly but there are additional ways '.
      'to improve it.  Please visit the <a href="@URL">status report</a> to find out more.',
      array('@URL' => url('admin/reports/status'))) .'</p>';
  }
  else {
    $output .= '<p>'. t('Contact Importer has been setup correctly.') .'</p>';
  }

  // Show engines.
  $output .= '<h3>'. t('Enabled Retrieval Engines') .'</h3>';
  $output .= '<p>'. t('Note that some services are fee based.') .'</p>';
  $engines = _contact_importer_engines();
  $providers_enabled = _contact_importer_providers_enabled();
  $providers_status = _contact_importer_engines_providers_status();
  foreach ($engines as $engine_name => $engine) {
    $output .= '<h4>'. $engine['human_readable'] .'</h4>';
    $output .= '<p>'. l(t('website'), $engine['url']) .' | ' .
      l(t('documentation'), drupal_get_path('module', $engine_name) .'/'. $engine['readme']) .
      '</p>';

    // Show the providers the engine is managing.
    $header = array(t('Provider'), t('Authentication status'), t('Contact retrieval status'), t('Operations'));
    $rows = array();
    foreach ($providers_enabled as $provider_name => $provider) {
      if ($provider['engine_enabled'] !== $engine_name) {
        continue;
      }
      foreach ($providers_status[$engine_name][$provider_name] as $test_type => $test_status) {
        if ($test_status == 'unknown') {
          ${$test_type .'_status'} = '<span title="Unknown">?</a>';
        }
        else {
          ${$test_type .'_status'} = 
            theme('image', 'misc/watchdog-'. $test_status .'.png', 
              ucfirst($test_status), ucfirst($test_status));
        }
      }
      $rows[] = array(
        $provider['favicon'] .' '. $provider['human_readable'],
        $authentication_success_status,
        $contacts_retrieved_status,
        l(t('Clear'), 'admin/reports/contact_importer/clear/'. $engine_name .'/'. $provider_name, array('query' => drupal_get_destination())),
      );
    }
    if (empty($rows)) {
      $output .= '<p>'. t('There are no providers being managed by this engine.') .'</p>';
    }
    else {
      $output .= '<p>'. t('Is managing the providers:') .'</p>';
      $caption = t('Providers are constantly changing their websites and so the retrieval engine '.
        'sometimes becomes unable to access a provider.  The status shown below is determined from '.
        'the <a href="!log_url">last two log entries</a> and is only valid at that point in time. '.
        'If the provider has not been used the status is unknown.  If the provider is not working '.
        'you may need to update the retreival engine\'s library files.',
        array('!log_url' => url('admin/reports/contact_importer')));
      $output .= theme('table', $header, $rows, array(), $caption);
    }
  }
  return $output;
}

/**
 * Admin settings form.
 *
 * @return array
 *  FAPI array.
 */
function contact_importer_settings_form() {
  $form['contact_importer_separator'] = array(
    '#type' => 'textfield',
    '#title' => t('Separator'),
    '#description' => t('The character that will be used to separate the list of email addresses: <br />'.
          '%n for new line<br />'.
          '%c for comma separated<br />'.
          'or choose your own.',
          array('%n' => '\n', '%c' => ',')
    ),
    '#default_value' => variable_get('contact_importer_separator', '\n'),
  );
  
  // Something to use for modal popups.
  $options = array(
    'none' => t('None'),
    'lightbox2' => t('<a href="@url">Lightbox2</a>', 
      array('@url' => 'http://drupal.org/project/lightbox2')),
    'automodal' => t('<a href="@url">Automodal</a>',
        array('@url' => 'http://drupal.org/project/automodal')),
  );
  $form['contact_importer_modal'] = array(
    '#type' => 'radios',
    '#title' => t('Modal pop-ups'),
    '#options' => $options,
    '#description' => t('Contact Importer can fetch contacts in a modal pop-up using one of '.
          'the following modules.  This allows the user to import from her address book without '.
          'leaving the current page.'),
  );
  foreach ($options as $option => $text) {
    $form['contact_importer_modal'][$option] = array(
      '#type' => 'radio',
      '#default_value' => (variable_get('contact_importer_modal', 'none') == $option ? $option : ''),
      '#return_value' => $option,
      '#disabled' => $option != 'none' && !module_exists($option),
      '#title' => $text,
      '#parents' => array('contact_importer_modal'),
    );
  }

  // Cache clear.
  $form['cache_clear'] = array(
    '#type' => 'fieldset',
    '#title' => 'Clear Contact Importer cached data',
    '#weight' => 2,
  );
  $form['cache_clear']['cache_clear'] = array(
    '#type' => 'submit',
    '#value' => 'Clear Contact Importer cached data',
    '#submit' => array('contact_importer_cache_clear_all', '_contact_importer_providers_enabled'),
  );

  return system_settings_form($form);
}

/**
 * Admin form to choose what engine to manage each provider.
 *
 * @return array
 *  FAPI array.
 */
function contact_importer_providers_form(&$form_state) {
  $form = array();
  $providers = _contact_importer_providers();
  $providers_enabled = _contact_importer_providers_enabled();
  $form['providers'] = array();
  foreach ($providers as $provider_name => $provider) {
    $provider['engines'] = array('disabled' => 'Disabled') + $provider['engines'];
    $default_value = !empty($provider['engine_enabled']) ? $provider['engine_enabled'] : 'disabled';
    $form['providers'][$provider['type']][$provider_name][$provider_name] = array(
      '#type' => 'select',
      '#title' => $provider['human_readable'],
      '#options' => $provider['engines'],
      '#default_value' => $default_value,
    );

    // Add the domains.
    $domain_list = array();
    foreach ($provider['domains'] as $domain) {
      $domain_list[] = $domain['human_readable'];
    }

    // Split into two columns.
    $domain_count = count($domain_list);
    $half_1 = ceil($domain_count / 2);
    $domain_list_1 = array_slice($domain_list, 0, $half_1);
    $domain_list_2 = array();
    if ($domain_count > 1) {
      $half_2 = $domain_count - $half_1;
      $domain_list_2 = array_slice($domain_list, -$half_2);
    }
    $form['providers'][$provider['type']][$provider_name]['domains'] = array(
      '#value' => theme('item_list', $domain_list_1, '', 'ul', array('style' => 'float:left; font-size:50%')) .
        theme('item_list', $domain_list_2, '', 'ul', array('style' => 'float:left; font-size:50%')),
    );
  }

  if (empty($form['providers'])) {
    drupal_set_message(t('There are no available providers.  Perhaps you have not yet installed '.
      'the appropriate libraries.'), 'error');
  }

  // Make the provider types into fieldsets.
  foreach ($form['providers'] as $provider_type_name => &$provider_type) {
    $provider_type['#type'] = 'fieldset';
    $provider_type['#collapsible'] = TRUE;
    $provider_type['#collapsed'] = TRUE;
    $provider_type['#title'] = t(ucfirst($provider_type_name) . ' providers');
  }
  $form['help'] = array(
    '#value' => '<p>'. t('Choose an engine for the providers that you wish to use on your site.') .'</p>',
    '#weight' => -1,
  );
  $form['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Save configuration'),
    '#suffix' => '<p>'. t('Upon saving the form the icons will be fetched for the '.
      'enabled providers.  This may take a long time depending on the number of enabled providers.'
      ) .'</p>',
  );
  return $form;
}

/**
 * Theme contact_importer_providers_form().
 */
function theme_contact_importer_providers_form($form) {
  $output = drupal_render($form['help']);
  
  // Put each fieldset into a table for easier readability.
  $header = array('Provider', 'Engine', 'Domains');
  foreach (element_children($form['providers']) as $provider_type) {
    $rows = array();
    foreach (element_children($form['providers'][$provider_type]) as $provider_name) {
      $provider =& $form['providers'][$provider_type][$provider_name];
      $title = $provider[$provider_name]['#title'];
      unset($provider[$provider_name]['#title']);
      $row = array(
        $title,
        drupal_render($provider[$provider_name]),
        drupal_render($provider['domains']),
      );
      $rows[] = $row;
    }

    // Put the table into the fieldset.
    $form['providers'][$provider_type]['#value'] = theme('table', $header, $rows);
    $output .= drupal_render($form['providers'][$provider_type]);
  }
  $output .= drupal_render($form);
  return $output;
}

/**
 * Validation handler for contact_importer_providers_form().
 */
function contact_importer_providers_form_validate($form, &$form_state) {

  // Ensure that at least one provider has been enabled.
  $enabled_count = 0;
  $disregarded_keys = array('op', 'submit', 'form_build_id', 'form_token', 'form_id');
  foreach ($form_state['values'] as $provider_name => $engine_name) {
    if (in_array($provider_name, $disregarded_keys)) {
      continue;
    }
    $enabled_count += ($engine_name != 'disabled');
  }
  if (!$enabled_count) {
    form_set_error('help', 'You must enable at least one provider');
  }
  
}

/**
 * Submit handler for contact_importer_providers_form().
 */
function contact_importer_providers_form_submit($form, &$form_state) {
  $disregarded_keys = array('op', 'submit', 'form_build_id', 'form_token', 'form_id');
  $providers_saved = array();
  foreach ($form_state['values'] as $provider_name => $engine_name) {
    if (in_array($provider_name, $disregarded_keys) || $engine_name == 'disabled') {
      continue;
    }
    $providers_saved[$provider_name] = $engine_name;
  }
  variable_set('contact_importer_providers_saved', $providers_saved);
  contact_importer_cache_clear_all();
  drupal_set_message(t('The configuration options have been saved.'));
}

/**
 * Menu callback to display Contact Importer log.
 */
function contact_importer_log() {
  $rows = array();
  $header = array(
    array(
      'data' => t('Log ID'),
      'field' => 'log_id',
      'sort' => 'DESC',
    ),
    array(
      'data' => t('Engine'),
      'field' => 'engine_name',
    ),
    array(
      'data' => t('Provider'),
      'field' => 'provider_name',
    ),
    t('Email'),
    t('Authentication success'),
    t('Contacts Retrieved'),
    t('Contacts Imported'),
    array(
      'data' => t('Completed'),
      'field' => 'completed',
    ),
  );

  $providers = _contact_importer_providers();
  $engines = _contact_importer_engines();

  // Build data.
  $res = pager_query('SELECT * FROM {contact_importer_log} '. tablesort_sql($header), 50);
  while ($log = db_fetch_object($res)) {
    $row = array(
      $log->log_id,
      (isset($engines[$log->engine_name]) ? $engines[$log->engine_name]['human_readable'] : $log->engine_name),
      (isset($providers[$log->provider_name]) ? $providers[$log->provider_name]['human_readable'] : $log->provider_name),
      check_plain($log->email),
      ($log->authentication_success ? 'True' : 'False'),
      $log->contacts_retrieved,
      $log->contacts_imported,
      ($log->completed ? 'True' : 'False'),
    );
    $rows[] = $row;
  }

  // Create output;
  $output = theme('table', $header, $rows);
  $output .= theme('pager', array(), 50);
  return $output;

}

/**
 * Confirm form to clear the log.
 */
function contact_importer_log_clear_confirm_form() {
  $engine_name = arg(4);
  $provider_name = arg(5);
  $form['engine_name'] = array(
    '#type' => 'value',
    '#value' => $engine_name,
  );
  $form['provider_name'] = array(
    '#type' => 'value',
    '#value' => $provider_name,
  );
  $filter = '';
  if ($engine_name || $provider_name) {
    $engines = _contact_importer_engines();
    $filter = $engines[$engine_name]['human_readable'];
    $filter .= $engine_name && $provider_name ? ' - ' : '';
    $filter .= !empty($provider_name) ? $engines[$engine_name]['providers'][$provider_name]['human_readable'] : '';
    $description = t('Are you sure you want to clear the log of records for %filter?', array('%filter' => $filter));
  }
  else {
    $description = t('Are you sure you want to clear the log of all records?');
  }
  $form['filter'] = array(
    '#type' => 'value',
    '#value' => $filter,
  );
  return confirm_form($form,
    t('Clear logs'),
    'admin/settings/cd_sunlight',
    $description,
    t('Clear log'),
    t('Cancel')
  );
}

/**
 * Handler for clearing the log.
 */
function contact_importer_log_clear_confirm_form_submit($form, &$form_state) {
  $engine_name = $form_state['values']['engine_name'];
  $provider_name = $form_state['values']['provider_name'];
  $filter = $form_state['values']['filter'];
  $where = $args = array();
  if ($engine_name) {
    $where[] = "engine_name = '%s'";
    $args[] = $engine_name;
  }
  if ($provider_name) {
    $where[] = "provider_name = '%s'";
    $args[] = $provider_name;
  }
  $where_string = '';
  if (!empty($where)) {
    $where_string = ' WHERE '. implode(' AND ', $where);
  }
  db_query('DELETE FROM {contact_importer_log} '. $where_string, $args);
  if ($filter) {
    drupal_set_message(t('The log has been cleared of records for %filter.', array('%filter' => $filter)));
  }
  else {
    drupal_set_message(t('The log has been cleared of all records.', array('%filter' => $filter)));
  }
}

/**
 * Reviews the log for each enabled provider and determines the status of authentication and contact
 * retrieval.
 *
 * @return array
 *  ex.
 *    array(
 *      'foo_engine' => array(
 *        'bar_mail' => array(
 *          'authentication_success' => 'ok',
 *          'contacts_retrueved' => 'warning',
 *        ),
 *        'baz_mail' => array(
 *          'authentication_success' => 'error',
 *          'contacts_retrueved' => 'error',
 *        ),
 *        'bif_mail' => array(
 *          'authentication_success' => 'unknown',
 *          'contacts_retrueved' => 'unknown',
 *        ),
 *      ),
 *    );
 */
function _contact_importer_engines_providers_status() {
  $providers = _contact_importer_providers_enabled();
  $status = array();
  foreach ($providers as $provider_name => $provider) {
    $results = array('authentication_success' => array(), 'contacts_retrieved' => array());
    $res = db_query_range("SELECT authentication_success, contacts_retrieved
      FROM {contact_importer_log}
      WHERE engine_name = '%s'
        AND provider_name = '%s'
        AND completed = TRUE
      ORDER BY log_id DESC", $provider['engine_enabled'], $provider_name, 0, 2);
    while ($log = db_fetch_array($res)) {
      $results['authentication_success'][] = $log['authentication_success'];
      $results['contacts_retrieved'][] = (bool)$log['contacts_retrieved'];
    }
    foreach ($results as $test_type => $entries) {
      if ($test_count = count($results[$test_type])) {
        $test_sum = array_sum($results[$test_type]);
        $test_difference = $test_count - $test_sum;
        $status[$provider['engine_enabled']][$provider_name][$test_type] =
          ($test_difference == 0 ? 'ok' : ($test_sum == 1 ? 'warning' : 'error'));
      }
      else {
        $status[$provider['engine_enabled']][$provider_name][$test_type] = 'unknown';
      }
    }
  }
  return $status;
}