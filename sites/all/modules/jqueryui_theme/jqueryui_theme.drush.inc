<?php
// $Id$

/**
 * @file
 *   jQuery UI theme module drush integration.
 */

/**
 * Implementation of hook_command().
 */
function jqueryui_theme_drush_command() {
  
  $items['jqueryui-install'] = array(
    'description' => dt('Downloads default jQuery UI themes. If no version specified, download last version by default.'),
    'options' => array(
      '--version' => dt('Define between 1.7 or 1.8 version of jQuery UI theme to download.'),
    ),
    'examples' => array(
      'jqueryui-download --version 1.7' => 'download all default jQuery themes version 1.7',
    ),
    'aliases' => array('jinstall'),
  );
  
  $items['jqueryui-url'] = array(
    'description' => dt('Downloads jQuery UI themes by given an internal/external url or a local path'),
    'arguments' => array(
      'url' => dt('An internal or external url to the download archiver containing jQuery themes. You can also give a local path.'),
    ),
    'aliases' => array('jurl'),
  );
  
  $items['jqueryui-show'] = array(
    'description' => dt('Display jQuery UI themes stored in default public.'),
    'aliases' => array('jshow'),
  );
  
  $items['jqueryui-rename'] = array(
    'description' => dt('Change human name of jQuery UI themes'),
    'arguments' => array(
      'machine_name' => dt('machine-name of a jQuery UI theme'),
      'new_name' => dt('new human name to assign'),
    ),
    'examples' => array(
      'jqueryui-renamme <machine_name> <new_human_name> - <machine_name> <new_human_name> - etc' => dt('change human name'),
    ),
    'aliases' => array('jname'),
  );
  
  $items['jqueryui-delete'] = array(
    'description' => dt('Delete some jQuery UI theme passed as arguments'),
    'arguments' => array(
      'machine_name' => dt('machine name of jQuery UI themes'),
    ),
    'aliases' => array('jdel'),
  );
  
  $items['jqueryui-theme-available'] = array(
    'description' => dt("Show Drupal theme available, so active and don't use by jQuery UI theme"),
    'aliases' => array('jtav'),
  );
  
  $items['jqueryui-assoc'] = array(
    'description' => dt('Attach a jQuery UI theme to a Drupal theme'),
    'arguments' => array(
      'machine_name' => dt('machine-name of a jQuery UI theme, following by drupal theme and separate by - '),
      'drupal_theme' => dt('list of drupal theme name to attch'),
    ),
    'examples' => array(
      'jqueryui-assoc <machine_name> <drupal_theme> <drupal_theme> - <machine_name> <drupal_theme> - etc' => dt('attach jQuery UI theme to drupal theme'),
    ),
    'aliases' => array('jassoc'),
  );
  
  $items['jqueryui-dissoc'] = array(
    'description' => dt('Detach a jQuery UI theme to a Drupal theme'),
    'arguments' => array(
      'machine_name' => dt('machine-name of a jQuery UI theme, following by drupal theme and separate by - '),
      'drupal_theme' => dt('list of drupal theme name to detach'),
    ),
    'examples' => array(
      'jqueryui-dissoc <machine_name> <drupal_theme> <drupal_theme> - <machine_name> <drupal_theme> - etc' => dt('attach jQuery UI theme to drupal theme'),
    ),
    'aliases' => array('jdis'),
  );
  
  return $items;
}


/**
 * A command callback.
 * Imports jQuery UI default themes.
 */
function drush_jqueryui_theme_jqueryui_install() {
  $args = func_get_args();
  $version = '1.8';
  if (isset($args[0]) && $args[0] == '1.7') {
    $version = $args[0];
  }
  $url = $version == '1.7' ? 'http://jquery-ui.googlecode.com/files/jquery-ui-themes-1.7.zip' : 'http://jquery-ui.googlecode.com/files/jquery-ui-themes-1.8.zip';
  if (@preg_match('/200/', reset(@get_headers($url))) > 0) {
    _jqueryui_theme_import($url);
  }
  else {
    drush_log(dt('Unable to retrieve jQuery UI default themes from "jquery-ui.googlecode.com"'), 'error');
  }
}

/**
 * A command callback.
 * Imports jQuery UI theme by an url given.
 */
function drush_jqueryui_theme_jqueryui_url($url) {
  _jqueryui_theme_import(check_url($url));
}

/**
 * A command callback validate.
 * 
 * @see drush_jqueryui_theme_jqueryui_url().
 */
function drush_jqueryui_theme_jqueryui_url_validate() {
  if (!($url = @func_get_arg(0))) {
    drush_set_error('JQUERYUI_THEME_INVALID_ARGUMENTS', dt('No external url given.'));
  }
  elseif (!valid_url($url)) {
    drush_set_error('JQUERYUI_THEME_INVALID_URL', dt('Incorrect url.'));
  }
}

/**
 * Function callback to command invoke.
 * Imports jQuery UI themes.
 * 
 * @see drush_jqueryui_theme_jqueryui_install()
 * @see drush_jqueryui_theme_jqueryui_url()
 */
function _jqueryui_theme_import($url) {
  $temp_dir = file_directory_temp() . '/jqueryui_theme/' . uniqid();
  
  if (FALSE === (file_check_directory($temp_dir, FILE_CREATE_DIRECTORY | FILE_MODIFY_PERMISSIONS))) {
    drush_log(dt('Unable to create directory in : !directory.', array('!directory' => $temp_dir)), 'error');
    return;
  }
  
  $local_cache = _jqueryui_theme_get_file_url($url, $temp_dir);
  if (!$local_cache) {
    drush_set_error('JQUERYUI_THEME_INVALID_URL', dt('Unable to retrieve jQuery UI themes from @url.', array('@url' => $url)));
    return;
  }
  
  try {
    $archiver = _jqueryui_theme_get_archiver($local_cache);
    if (!$archiver) {
      drush_set_error('JQUERYUI_THEME_ERROR_ARCHIVE', dt('No archiver found.'));
      return;
    }
    $files = $archiver->listContents();
  }
  catch (Exception $e) {
    drush_set_error('JQUERYUI_THEME_ERROR_ARCHIVE', dt('!error', array('!error' => $e)));
    return;
  }
         
  if (!($themes = _jqueryui_theme_detect_themes($archiver->listContents(), $temp_dir))) {
    drush_set_error('JQUERYUI_THEME_ERROR_ARCHIVE', dt('No "all.css" files detected'));
    return;
  }
  else {
    $archiver->extract($temp_dir);
    
    //Ensure to get unique names because the user cannot
    //change himself here in the UI. It will incremente '_change'
    //until the machine name is unique.
    
    //@todo let's user provide human and machine in case
    //of conflict (machine name isn't unique)
    $name = array();
    foreach ($themes as $key => $theme) {
      $themes[$key]['name'] = preg_replace(array('/[^a-z0-9_]+/'), '_', $theme['name']);
      $name[$themes[$key]['name']] = $themes[$key]['name'];
      
      while (_jqueryui_theme_check_machine_name($themes[$key]['name']) || !in_array($themes[$key]['name'], $name)) {
        
        if ($name[$themes[$key]['name']]) {
          unset($name[$themes[$key]['name']]);
        }
        $themes[$key]['name'] = $themes[$key]['name'] . '_change';
        if (!in_array($themes[$key]['name'], $name)) {
          $name[$themes[$key]['name']] = $themes[$key]['name'];
        }
      }
      $themes[$key]['machine_name']   = $themes[$key]['name'];
      $themes[$key]['human_name']     = $themes[$key]['name'];
      //get version by the way
      $themes[$key]['version'] = _jqueryui_theme_get_version($themes[$key]['directory'], $themes[$key]['files'], $themes[$key]['version']);
    }
    
    $headers = array(dt('MACHINE-NAME'), dt('TEMP PATH'), dt('VERSION'));
    $rows = array();
    $rows[] = $headers;
    foreach ($themes as $theme) {
      $row = array();
      $row[] = $theme['name'];
      $row[] = $theme['directory'];
      $row[] = $theme['version'];
      $rows[] = $row;
    }
    
    drush_log(dt('!num jQuery UI theme detected', array('!num' => sizeof($themes))), 'ok');
    drush_print_table($rows, TRUE);
    
    if ($import = drush_prompt(dt('Select theme to import ? (all | none | <machine_name> <machine_name> etc)'))) {
      $theme_select = $messages = array();
      if ($import == 'all') {
        $theme_select = $name;
      }
      elseif ($import != 'none') {
        $theme_select = array_unique(explode(' ', $import));
          foreach ($theme_select as $key => $theme) {
            $theme_select[$theme] = $theme;
            unset($theme_select[$key]);
          }
        }
 
      foreach ($themes as $theme) {
        if (in_array($theme['name'], $theme_select)) {
          $params = _jqueryui_theme_get_params($theme['directory'], $theme['files']);
          if (_jqueryui_theme_move_tmp($theme)) {
            _jqueryui_theme_insert_theme($theme['machine_name'], $theme['human_name'], $theme['version'], $params);
            $messages[$theme['name']]['data'] = dt('@name have been imported', array('@name' => $theme['name']));
            $messages[$theme['name']]['type'] = 'ok';
          }
          else {
            $messages[$theme['name']]['data'] = dt('@name cannot be imported to default public location because machine_name is already use', array('@name' => $theme['name']));
            $messages[$theme['name']]['type'] = 'warning';
          }
          unset($theme_select[$theme['name']]);
        }
      }
    }
    _jqueryui_theme_delete_recursive($temp_dir);
    if (empty($messages)) {
      drush_log(dt('No jQuery UI theme imported'), 'ok');
    }
    else {
      foreach ($messages as $mess) {
        drush_log($mess['data'], $mess['type']);
      }
    }
  }
}

/**
 * A command callback.
 * Displays jQuery UI themes states.
 */
function drush_jqueryui_theme_jqueryui_show() {
  $themes = _jqueryui_theme_get_themes_infos();
  
  $headers = array(dt('MACHINE-NAME'), dt('HUMAN-NAME'), dt('ASSOCIATED'), dt('PATH'), dt('VERSION'));
  $rows = array();
  $rows[] = $headers;
  $rows[] = array('', '', '', '', '');
  if (empty($themes)) {
    $rows[] = array(dt('There is no jQuery UI theme imported'));
  }
  else {
    foreach ($themes as $theme => $data) {
      $row = array();
      $row[] = $data->machine_name;
      $row[] = $data->human_name;
      $row[] = implode(' -- ', $data->drupal_theme_attach) ? implode(' -- ', $data->drupal_theme_attach) : dt('none');
      $row[] = $data->path;
      $row[] = $data->version;
      $rows[] = $row;
    }
  }
  drush_print_table($rows, TRUE);
}

/**
 * A command callback.
 * Changes jQuery UI themes human name
 */
function drush_jqueryui_theme_jqueryui_rename() {
  $args = @func_get_args();
  if (!empty($args) && sizeof($args) > 1) {
    $data = _jqueryui_theme_parse_multiple_theme($args);
    $messages = array();
    foreach ($data as $machine_name => $new_name) {
      if (_jqueryui_theme_rename($machine_name, $new_name[1])) {
        $messages[$machine_name]['data'] = dt('!theme have been rename in !new_name', array('!theme' => $machine_name, '!new_name' => $new_name[1]));
        $messages[$machine_name]['type'] = 'ok';
      }
      else {
        $messages[$machine_name]['data'] = dt('!theme cannot be rename in !new_name', array('!theme' => $machine_name, '!new_name' => $new_name[1]));
        $messages[$machine_name]['type'] = 'warning';
      }
    }
    foreach ($messages as $mess) {
      drush_log($mess['data'], $mess['type']);  
    }
  }
  else {
    drush_set_error('JQUERYUI_THEME_INVALID_ARGUMENTS', dt('Arguments supplied are not correct, see detail with -h'));
  }
}

/**
 * A command callback.
 * Deletes jQuery UI themes.
 */
function drush_jqueryui_theme_jqueryui_delete() {
  if ($themes = @func_get_args()) {
    if (!drush_confirm(dt('Are you sure?'))) {
      return drush_user_abort();
    }
    
    $messages = array();
    $rebuild = FALSE;
    
    if ($themes[0] == 'all') {
      $themes = _jqueryui_theme_get_themes_infos();
    }
    
    foreach ($themes as $theme) {
      $theme = isset($theme->machine_name) ? $theme->machine_name : $theme;
      $public = file_directory_path() . '/jqueryui_theme/' . $theme;
      
      if (_jqueryui_theme_delete($theme, $public)) {
        $messages[$theme]['data'] = dt('!theme was deleted', array('!theme' => $theme));
        $messages[$theme]['type'] = 'ok';
        $rebuild = TRUE;
      }
      else {
        $messages[$theme]['data'] = dt('!theme cannot be delete', array('!theme' => $theme));
        $messages[$theme]['type'] = 'warning';
      }
    }
    if ($rebuild) {
      menu_rebuild();
    }
    foreach ($messages as $mess) {
      drush_log($mess['data'], $mess['type']);  
    }
  }
  else {
    drush_set_error('JQUERYUI_THEME_INVALID_ARGUMENTS', dt('No machine name supplied'));
  }
}

/**
 * A command callback.
 * Dissociates jQuery UI themes to Drupal theme.
 */
function drush_jqueryui_theme_jqueryui_dissoc() {
  $args = @func_get_args();
  if (!empty($args) && sizeof($args) > 1) {
    $data = _jqueryui_theme_parse_multiple_theme($args);
    $messages = array();
    $rebuild = FALSE;
    foreach ($data as $machine_name => $drupal_themes) {
      foreach ($drupal_themes as $drupal_theme) {
        if (_jqueryui_theme_dissoc($machine_name, $drupal_theme)) {
          $messages[$machine_name . $drupal_theme]['data'] = dt('!theme jQuery UI was detach to !machine_name', array('!theme' => $drupal_theme, '!machine_name' => $machine_name));
          $messages[$machine_name . $drupal_theme]['type'] = 'ok';
          $rebuild = TRUE;
        }
        else {
          $messages[$machine_name . $drupal_theme]['data'] = dt('!theme jQuery UI cannot be detach to !machine_name', array('!theme' => $drupal_theme, '!machine_name' => $machine_name));
          $messages[$machine_name . $drupal_theme]['type'] = 'warning';
        }
      }
    }
    if ($rebuild) {
      menu_rebuild();
    }
    foreach ($messages as $mess) {
      drush_log($mess['data'], $mess['type']);
    }
  }
  else {
    drush_set_error('JQUERYUI_THEME_INVALID_ARGUMENTS', dt('Arguments supplied are not correct, see detail with -h'));
  }
}

/**
 * A command callback.
 * Displays Drupal theme available.
 */
function drush_jqueryui_theme_available() {
  $themes = array();
  $themes[] = array(dt('NAME'), dt('STATUS'), dt('ATTACH'));
  $drupal_theme = _jqueryui_theme_drupal_themes_availables(TRUE);
  foreach ($drupal_theme as $theme) {
    $row = array();
    $row[] = $theme['name'];
    $row[] = $theme['status'] == 1 ? dt('Enable') : dt('Disable');
    $row[] = $theme['attach'] == 1 ? dt('Yes') : dt('No');
    $themes[] = $row;
  }
  drush_print_table($themes, TRUE);
}

/**
 * A command callback.
 * Associates jQuery UI themes with Drupal theme.
 */
function drush_jqueryui_theme_jqueryui_assoc() {
  $args = @func_get_args();
  if (!empty($args) && sizeof($args) > 1) {
    $data = _jqueryui_theme_parse_multiple_theme($args);
    $messages = array();
    $rebuild = FALSE;
    
    foreach ($data as $machine_name => $drupal_themes) {
      foreach ($drupal_themes as $drupal_theme) {
        if (array_key_exists($drupal_theme, _jqueryui_theme_drupal_themes_availables())) {
          if (_jqueryui_theme_assoc($machine_name, $drupal_theme)) {
            $messages[$machine_name . $drupal_theme]['data'] = dt('!theme was attach to jQuery UI "!machine_name"', array('!theme' => $drupal_theme, '!machine_name' => $machine_name));
            $messages[$machine_name . $drupal_theme]['type'] = 'ok';
            $rebuild = TRUE;
          }
        }
        if (!$messages[$machine_name . $drupal_theme]) {
          $messages[$machine_name . $drupal_theme]['data'] = dt('!theme cannot be attach to jQuery UI "!machine_name"', array('!theme' => $drupal_theme, '!machine_name' => $machine_name));
          $messages[$machine_name . $drupal_theme]['type'] = 'warning';
        }
      }
    }
    if ($rebuild) {
      menu_rebuild();
    }
    foreach ($messages as $mess) {
      drush_log($mess['data'], $mess['type']);
    }
  }
  else {
    drush_set_error('JQUERYUI_THEME_INVALID_ARGUMENTS', dt('Arguments supplied are not correct, see detail with -h'));
  }
}

/**
 * Function callback to invoke.
 *  
 * @param $args
 *   An array containing jQuery UI theme metadata.
 * @return
 *   Returns an associative array with
 *   machine name key and their values.
 */
function _jqueryui_theme_parse_multiple_theme($args) {
  $data = array();
  $args = explode('/-/', implode('/', $args));
  
  foreach ($args as $element) {
    $tmp = explode('/', $element);
    $key = $tmp[0];
    unset($tmp[0]);
    $data[$key] = $tmp;
  }
  return $data;
}