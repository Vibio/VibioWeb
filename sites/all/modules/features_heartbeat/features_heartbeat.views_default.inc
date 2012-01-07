<?php

/**
 * Implementation of hook_views_default_views().
 */
function features_heartbeat_views_default_views() {
  $views = array();

  // Exported view: user_hb_incoming_activity
  $view = new view;
  $view->name = 'user_hb_incoming_activity';
  $view->description = 'heartbeat streams';
  $view->tag = 'heartbeat';
  $view->base_table = 'heartbeat_activity';
  $view->human_name = '';
  $view->core = 0;
  $view->api_version = '3.0';
  $view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

  /* Display: activity streams */
  $handler = $view->new_display('default', 'activity streams', 'default');
  $handler->display->display_options['title'] = 'Recent activities';
  $handler->display->display_options['use_ajax'] = TRUE;
  $handler->display->display_options['use_more'] = TRUE;
  $handler->display->display_options['use_more_always'] = TRUE;
  $handler->display->display_options['use_more_text'] = 'See More';
  $handler->display->display_options['access']['type'] = 'none';
  $handler->display->display_options['cache']['type'] = 'none';
  $handler->display->display_options['query']['type'] = 'views_query';
  $handler->display->display_options['exposed_form']['type'] = 'basic';
  $handler->display->display_options['pager']['type'] = 'some';
  $handler->display->display_options['pager']['options']['items_per_page'] = '5';
  $handler->display->display_options['pager']['options']['offset'] = '';
  $handler->display->display_options['style_plugin'] = 'table';
  $handler->display->display_options['style_options']['columns'] = array(
    'picture' => 'picture',
    'message' => 'message',
    'timestamp' => 'timestamp',
  );
  $handler->display->display_options['style_options']['default'] = 'timestamp';
  $handler->display->display_options['style_options']['info'] = array(
    'picture' => array(
      'sortable' => 0,
      'separator' => '',
    ),
    'message' => array(
      'separator' => '',
    ),
    'timestamp' => array(
      'sortable' => 0,
      'separator' => '',
    ),
  );
  $handler->display->display_options['style_options']['override'] = 1;
  $handler->display->display_options['style_options']['sticky'] = 0;
  $handler->display->display_options['style_options']['order'] = 'desc';
  /* Header: Global: Text area */
  $handler->display->display_options['header']['area']['id'] = 'area';
  $handler->display->display_options['header']['area']['table'] = 'views';
  $handler->display->display_options['header']['area']['field'] = 'area';
  $handler->display->display_options['header']['area']['empty'] = FALSE;
  $handler->display->display_options['header']['area']['content'] = '<?php 
$interval = 5000;
print theme(\'views_autofakerefresh\', $interval);
?>';
  $handler->display->display_options['header']['area']['format'] = '3';
  $handler->display->display_options['header']['area']['tokenize'] = 0;
  /* Field: User: Picture */
  $handler->display->display_options['fields']['picture']['id'] = 'picture';
  $handler->display->display_options['fields']['picture']['table'] = 'users';
  $handler->display->display_options['fields']['picture']['field'] = 'picture';
  $handler->display->display_options['fields']['picture']['label'] = '';
  $handler->display->display_options['fields']['picture']['alter']['alter_text'] = 0;
  $handler->display->display_options['fields']['picture']['alter']['make_link'] = 0;
  $handler->display->display_options['fields']['picture']['alter']['word_boundary'] = 1;
  $handler->display->display_options['fields']['picture']['alter']['ellipsis'] = 1;
  $handler->display->display_options['fields']['picture']['alter']['strip_tags'] = 0;
  $handler->display->display_options['fields']['picture']['alter']['trim'] = 0;
  $handler->display->display_options['fields']['picture']['alter']['html'] = 0;
  $handler->display->display_options['fields']['picture']['hide_empty'] = 0;
  $handler->display->display_options['fields']['picture']['empty_zero'] = 0;
  /* Field: Heartbeat activity: Message */
  $handler->display->display_options['fields']['message']['id'] = 'message';
  $handler->display->display_options['fields']['message']['table'] = 'heartbeat_activity';
  $handler->display->display_options['fields']['message']['field'] = 'message';
  $handler->display->display_options['fields']['message']['label'] = '';
  $handler->display->display_options['fields']['message']['alter']['alter_text'] = 0;
  $handler->display->display_options['fields']['message']['alter']['make_link'] = 0;
  $handler->display->display_options['fields']['message']['alter']['word_boundary'] = 1;
  $handler->display->display_options['fields']['message']['alter']['ellipsis'] = 1;
  $handler->display->display_options['fields']['message']['alter']['strip_tags'] = 0;
  $handler->display->display_options['fields']['message']['alter']['trim'] = 0;
  $handler->display->display_options['fields']['message']['alter']['html'] = 0;
  $handler->display->display_options['fields']['message']['hide_empty'] = 0;
  $handler->display->display_options['fields']['message']['empty_zero'] = 0;
  /* Sort criterion: Heartbeat activity: Action's timestamp */
  $handler->display->display_options['sorts']['timestamp']['id'] = 'timestamp';
  $handler->display->display_options['sorts']['timestamp']['table'] = 'heartbeat_activity';
  $handler->display->display_options['sorts']['timestamp']['field'] = 'timestamp';
  $handler->display->display_options['sorts']['timestamp']['order'] = 'DESC';
  /* Argument: Heartbeat activity: Currently requested user (url argument) carried out the activity */
  $handler->display->display_options['arguments']['uid_requested']['id'] = 'uid_requested';
  $handler->display->display_options['arguments']['uid_requested']['table'] = 'heartbeat_activity';
  $handler->display->display_options['arguments']['uid_requested']['field'] = 'uid_requested';
  $handler->display->display_options['arguments']['uid_requested']['style_plugin'] = 'default_summary';
  $handler->display->display_options['arguments']['uid_requested']['default_argument_type'] = 'fixed';
  $handler->display->display_options['arguments']['uid_requested']['default_argument_skip_url'] = 0;
  $handler->display->display_options['arguments']['uid_requested']['break_phrase'] = 0;
  $handler->display->display_options['arguments']['uid_requested']['not'] = 0;
  /* Argument: Privacy Settings: Currently requested setting (url arg) >= setting */
  $handler->display->display_options['arguments']['setting_requested']['id'] = 'setting_requested';
  $handler->display->display_options['arguments']['setting_requested']['table'] = 'privacy_node';
  $handler->display->display_options['arguments']['setting_requested']['field'] = 'setting_requested';
  $handler->display->display_options['arguments']['setting_requested']['style_plugin'] = 'default_summary';
  $handler->display->display_options['arguments']['setting_requested']['default_argument_type'] = 'fixed';

  /* Display: Block */
  $handler = $view->new_display('block', 'Block', 'block_1');
  $handler->display->display_options['block_caching'] = '2';
  $translatables['user_hb_incoming_activity'] = array(
    t('activity streams'),
    t('Recent activities'),
    t('See More'),
    t('Apply'),
    t('Reset'),
    t('Sort by'),
    t('Asc'),
    t('Desc'),
    t('Text area'),
    t('<?php 
$interval = 5000;
print theme(\'views_autofakerefresh\', $interval);
?>'),
    t('All'),
    t('Block'),
    t('more'),
  );

  $views[$view->name] = $view;

  // Exported view: user_heartbeat_activity
  $view = new view;
  $view->name = 'user_heartbeat_activity';
  $view->description = 'heartbeat streams';
  $view->tag = 'heartbeat';
  $view->base_table = 'heartbeat_activity';
  $view->human_name = '';
  $view->core = 0;
  $view->api_version = '3.0';
  $view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

  /* Display: activity streams */
  $handler = $view->new_display('default', 'activity streams', 'default');
  $handler->display->display_options['title'] = 'Recent activities';
  $handler->display->display_options['use_more_always'] = TRUE;
  $handler->display->display_options['use_more_text'] = 'See More';
  $handler->display->display_options['access']['type'] = 'none';
  $handler->display->display_options['cache']['type'] = 'argument';
  $handler->display->display_options['query']['type'] = 'views_query';
  $handler->display->display_options['exposed_form']['type'] = 'basic';
  $handler->display->display_options['pager']['type'] = 'full';
  $handler->display->display_options['pager']['options']['items_per_page'] = '10';
  $handler->display->display_options['pager']['options']['offset'] = '0';
  $handler->display->display_options['pager']['options']['id'] = '0';
  $handler->display->display_options['pager']['options']['expose']['items_per_page_options_all'] = 0;
  $handler->display->display_options['style_plugin'] = 'table';
  $handler->display->display_options['style_options']['columns'] = array(
    'message' => 'message',
    'timestamp' => 'timestamp',
  );
  $handler->display->display_options['style_options']['default'] = 'timestamp';
  $handler->display->display_options['style_options']['info'] = array(
    'message' => array(
      'separator' => '',
    ),
    'timestamp' => array(
      'sortable' => 0,
      'separator' => '',
    ),
  );
  $handler->display->display_options['style_options']['override'] = 1;
  $handler->display->display_options['style_options']['sticky'] = 0;
  $handler->display->display_options['style_options']['order'] = 'desc';
  /* Header: Global: Text area */
  $handler->display->display_options['header']['area']['id'] = 'area';
  $handler->display->display_options['header']['area']['table'] = 'views';
  $handler->display->display_options['header']['area']['field'] = 'area';
  $handler->display->display_options['header']['area']['empty'] = FALSE;
  $handler->display->display_options['header']['area']['content'] = 'Recent Activity';
  $handler->display->display_options['header']['area']['format'] = '1';
  $handler->display->display_options['header']['area']['tokenize'] = 0;
  /* Empty text: Global: Text area */
  $handler->display->display_options['empty']['area']['id'] = 'area';
  $handler->display->display_options['empty']['area']['table'] = 'views';
  $handler->display->display_options['empty']['area']['field'] = 'area';
  $handler->display->display_options['empty']['area']['label'] = 'Text area - user no heartbeat yet';
  $handler->display->display_options['empty']['area']['empty'] = FALSE;
  $handler->display->display_options['empty']['area']['content'] = '<?php
global $user;
if (arg(0) == \'user\' && is_numeric(arg(1)) && arg(1) == $user->uid && arg(2) == \'activity\') {
    return "<div id=\'no-activity\'><p>Still waiting for some activity here: get started putting your style online and this page will get interesting!</p><img src=\'/themes/vibio/images/no_activity.png\' alt=\'You have no recent activity\' /></div>";
} else {
    return "<div id=\'no-activity\'><p>Still waiting for some activity here: return again later to see if this user is active.</p><img src=\'/themes/vibio/images/no_activity_other.png\' alt=\'This user has no recent activity\' /></div>";
}
?>';
  $handler->display->display_options['empty']['area']['format'] = '3';
  $handler->display->display_options['empty']['area']['tokenize'] = 0;
  /* Field: User: Picture */
  $handler->display->display_options['fields']['picture']['id'] = 'picture';
  $handler->display->display_options['fields']['picture']['table'] = 'users';
  $handler->display->display_options['fields']['picture']['field'] = 'picture';
  $handler->display->display_options['fields']['picture']['label'] = '';
  $handler->display->display_options['fields']['picture']['alter']['alter_text'] = 0;
  $handler->display->display_options['fields']['picture']['alter']['make_link'] = 0;
  $handler->display->display_options['fields']['picture']['alter']['word_boundary'] = 1;
  $handler->display->display_options['fields']['picture']['alter']['ellipsis'] = 1;
  $handler->display->display_options['fields']['picture']['alter']['strip_tags'] = 0;
  $handler->display->display_options['fields']['picture']['alter']['trim'] = 0;
  $handler->display->display_options['fields']['picture']['alter']['html'] = 0;
  $handler->display->display_options['fields']['picture']['hide_empty'] = 0;
  $handler->display->display_options['fields']['picture']['empty_zero'] = 0;
  /* Field: Heartbeat activity: Message */
  $handler->display->display_options['fields']['message']['id'] = 'message';
  $handler->display->display_options['fields']['message']['table'] = 'heartbeat_activity';
  $handler->display->display_options['fields']['message']['field'] = 'message';
  $handler->display->display_options['fields']['message']['label'] = '';
  $handler->display->display_options['fields']['message']['alter']['alter_text'] = 0;
  $handler->display->display_options['fields']['message']['alter']['make_link'] = 0;
  $handler->display->display_options['fields']['message']['alter']['word_boundary'] = 1;
  $handler->display->display_options['fields']['message']['alter']['ellipsis'] = 1;
  $handler->display->display_options['fields']['message']['alter']['strip_tags'] = 0;
  $handler->display->display_options['fields']['message']['alter']['trim'] = 0;
  $handler->display->display_options['fields']['message']['alter']['html'] = 0;
  $handler->display->display_options['fields']['message']['hide_empty'] = 0;
  $handler->display->display_options['fields']['message']['empty_zero'] = 0;
  /* Field: Heartbeat activity: Action's timestamp */
  $handler->display->display_options['fields']['timestamp']['id'] = 'timestamp';
  $handler->display->display_options['fields']['timestamp']['table'] = 'heartbeat_activity';
  $handler->display->display_options['fields']['timestamp']['field'] = 'timestamp';
  $handler->display->display_options['fields']['timestamp']['label'] = '';
  $handler->display->display_options['fields']['timestamp']['exclude'] = TRUE;
  $handler->display->display_options['fields']['timestamp']['alter']['alter_text'] = 0;
  $handler->display->display_options['fields']['timestamp']['alter']['make_link'] = 0;
  $handler->display->display_options['fields']['timestamp']['alter']['word_boundary'] = 1;
  $handler->display->display_options['fields']['timestamp']['alter']['ellipsis'] = 1;
  $handler->display->display_options['fields']['timestamp']['alter']['strip_tags'] = 0;
  $handler->display->display_options['fields']['timestamp']['alter']['trim'] = 0;
  $handler->display->display_options['fields']['timestamp']['alter']['html'] = 0;
  $handler->display->display_options['fields']['timestamp']['hide_empty'] = 0;
  $handler->display->display_options['fields']['timestamp']['empty_zero'] = 0;
  /* Sort criterion: Heartbeat activity: Action's timestamp */
  $handler->display->display_options['sorts']['timestamp']['id'] = 'timestamp';
  $handler->display->display_options['sorts']['timestamp']['table'] = 'heartbeat_activity';
  $handler->display->display_options['sorts']['timestamp']['field'] = 'timestamp';
  $handler->display->display_options['sorts']['timestamp']['order'] = 'DESC';
  /* Argument: Heartbeat activity: Currently requested user (url argument) carried out the activity */
  $handler->display->display_options['arguments']['uid_requested']['id'] = 'uid_requested';
  $handler->display->display_options['arguments']['uid_requested']['table'] = 'heartbeat_activity';
  $handler->display->display_options['arguments']['uid_requested']['field'] = 'uid_requested';
  $handler->display->display_options['arguments']['uid_requested']['default_action'] = 'not found';
  $handler->display->display_options['arguments']['uid_requested']['style_plugin'] = 'default_summary';
  $handler->display->display_options['arguments']['uid_requested']['default_argument_type'] = 'fixed';
  $handler->display->display_options['arguments']['uid_requested']['break_phrase'] = 0;
  $handler->display->display_options['arguments']['uid_requested']['not'] = 0;
  /* Argument: Privacy Settings: Currently requested setting (url arg) >= setting */
  $handler->display->display_options['arguments']['setting_requested']['id'] = 'setting_requested';
  $handler->display->display_options['arguments']['setting_requested']['table'] = 'privacy_node';
  $handler->display->display_options['arguments']['setting_requested']['field'] = 'setting_requested';
  $handler->display->display_options['arguments']['setting_requested']['style_plugin'] = 'default_summary';
  $handler->display->display_options['arguments']['setting_requested']['default_argument_type'] = 'fixed';
  $handler->display->display_options['arguments']['setting_requested']['default_argument_skip_url'] = 0;
  $handler->display->display_options['arguments']['setting_requested']['break_phrase'] = 0;
  $handler->display->display_options['arguments']['setting_requested']['not'] = 0;
  /* Filter: Broken/missing handler */
  $handler->display->display_options['filters']['type']['id'] = 'type';
  $handler->display->display_options['filters']['type']['table'] = 'privacy_settings';
  $handler->display->display_options['filters']['type']['field'] = 'type';
  $handler->display->display_options['filters']['type']['value'] = 'heartbeat_messages';
  $handler->display->display_options['filters']['type']['expose']['operator'] = FALSE;

  /* Display: Block */
  $handler = $view->new_display('block', 'Block', 'block_1');
  $handler->display->display_options['defaults']['pager'] = FALSE;
  $handler->display->display_options['pager']['type'] = 'full';
  $handler->display->display_options['pager']['options']['items_per_page'] = '10';
  $handler->display->display_options['pager']['options']['offset'] = '0';
  $handler->display->display_options['pager']['options']['id'] = '0';
  $handler->display->display_options['pager']['options']['total_pages'] = '5';
  $handler->display->display_options['pager']['options']['expose']['items_per_page_options_all'] = 0;
  $handler->display->display_options['defaults']['header'] = FALSE;
  $handler->display->display_options['block_caching'] = '2';
  $translatables['user_heartbeat_activity'] = array(
    t('activity streams'),
    t('Recent activities'),
    t('See More'),
    t('Apply'),
    t('Reset'),
    t('Sort by'),
    t('Asc'),
    t('Desc'),
    t('Items per page'),
    t('- All -'),
    t('Offset'),
    t('Text area'),
    t('Recent Activity'),
    t('Text area - user no heartbeat yet'),
    t('<?php
global $user;
if (arg(0) == \'user\' && is_numeric(arg(1)) && arg(1) == $user->uid && arg(2) == \'activity\') {
    return "<div id=\'no-activity\'><p>Still waiting for some activity here: get started putting your style online and this page will get interesting!</p><img src=\'/themes/vibio/images/no_activity.png\' alt=\'You have no recent activity\' /></div>";
} else {
    return "<div id=\'no-activity\'><p>Still waiting for some activity here: return again later to see if this user is active.</p><img src=\'/themes/vibio/images/no_activity_other.png\' alt=\'This user has no recent activity\' /></div>";
}
?>'),
    t('All'),
    t('Block'),
    t('more'),
  );

  $views[$view->name] = $view;

  // Exported view: user_relational_activity
  $view = new view;
  $view->name = 'user_relational_activity';
  $view->description = 'heartbeat activity for a user\'s relations';
  $view->tag = 'heartbeat user_relationships';
  $view->base_table = 'heartbeat_activity';
  $view->human_name = '';
  $view->core = 0;
  $view->api_version = '3.0';
  $view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

  /* Display: Defaults */
  $handler = $view->new_display('default', 'Defaults', 'default');
  $handler->display->display_options['use_ajax'] = TRUE;
  $handler->display->display_options['access']['type'] = 'none';
  $handler->display->display_options['cache']['type'] = 'none';
  $handler->display->display_options['query']['type'] = 'views_query';
  $handler->display->display_options['exposed_form']['type'] = 'basic';
  $handler->display->display_options['pager']['type'] = 'full';
  $handler->display->display_options['pager']['options']['items_per_page'] = '3';
  $handler->display->display_options['pager']['options']['offset'] = '0';
  $handler->display->display_options['pager']['options']['id'] = '0';
  $handler->display->display_options['pager']['options']['expose']['items_per_page_options_all'] = 0;
  $handler->display->display_options['style_plugin'] = 'table';
  /* Field: User: Picture */
  $handler->display->display_options['fields']['picture']['id'] = 'picture';
  $handler->display->display_options['fields']['picture']['table'] = 'users';
  $handler->display->display_options['fields']['picture']['field'] = 'picture';
  $handler->display->display_options['fields']['picture']['label'] = '';
  $handler->display->display_options['fields']['picture']['alter']['alter_text'] = 0;
  $handler->display->display_options['fields']['picture']['alter']['make_link'] = 0;
  $handler->display->display_options['fields']['picture']['alter']['word_boundary'] = 1;
  $handler->display->display_options['fields']['picture']['alter']['ellipsis'] = 1;
  $handler->display->display_options['fields']['picture']['alter']['strip_tags'] = 0;
  $handler->display->display_options['fields']['picture']['alter']['trim'] = 0;
  $handler->display->display_options['fields']['picture']['alter']['html'] = 0;
  $handler->display->display_options['fields']['picture']['hide_empty'] = 0;
  $handler->display->display_options['fields']['picture']['empty_zero'] = 0;
  /* Field: Heartbeat activity: Message */
  $handler->display->display_options['fields']['message']['id'] = 'message';
  $handler->display->display_options['fields']['message']['table'] = 'heartbeat_activity';
  $handler->display->display_options['fields']['message']['field'] = 'message';
  $handler->display->display_options['fields']['message']['label'] = '';
  $handler->display->display_options['fields']['message']['alter']['alter_text'] = 0;
  $handler->display->display_options['fields']['message']['alter']['make_link'] = 0;
  $handler->display->display_options['fields']['message']['alter']['word_boundary'] = 1;
  $handler->display->display_options['fields']['message']['alter']['ellipsis'] = 1;
  $handler->display->display_options['fields']['message']['alter']['strip_tags'] = 0;
  $handler->display->display_options['fields']['message']['alter']['trim'] = 0;
  $handler->display->display_options['fields']['message']['alter']['html'] = 0;
  $handler->display->display_options['fields']['message']['hide_empty'] = 0;
  $handler->display->display_options['fields']['message']['empty_zero'] = 0;
  /* Field: Heartbeat activity: Action's timestamp */
  $handler->display->display_options['fields']['timestamp']['id'] = 'timestamp';
  $handler->display->display_options['fields']['timestamp']['table'] = 'heartbeat_activity';
  $handler->display->display_options['fields']['timestamp']['field'] = 'timestamp';
  $handler->display->display_options['fields']['timestamp']['label'] = '';
  $handler->display->display_options['fields']['timestamp']['alter']['alter_text'] = 0;
  $handler->display->display_options['fields']['timestamp']['alter']['make_link'] = 0;
  $handler->display->display_options['fields']['timestamp']['alter']['word_boundary'] = 1;
  $handler->display->display_options['fields']['timestamp']['alter']['ellipsis'] = 1;
  $handler->display->display_options['fields']['timestamp']['alter']['strip_tags'] = 0;
  $handler->display->display_options['fields']['timestamp']['alter']['trim'] = 0;
  $handler->display->display_options['fields']['timestamp']['alter']['html'] = 0;
  $handler->display->display_options['fields']['timestamp']['hide_empty'] = 0;
  $handler->display->display_options['fields']['timestamp']['empty_zero'] = 0;
  $handler->display->display_options['fields']['timestamp']['date_format'] = 'time ago';
  /* Sort criterion: Heartbeat activity: Action's timestamp */
  $handler->display->display_options['sorts']['timestamp']['id'] = 'timestamp';
  $handler->display->display_options['sorts']['timestamp']['table'] = 'heartbeat_activity';
  $handler->display->display_options['sorts']['timestamp']['field'] = 'timestamp';
  $handler->display->display_options['sorts']['timestamp']['order'] = 'DESC';
  /* Argument: User relationships: Requester user */
  $handler->display->display_options['arguments']['requester_id']['id'] = 'requester_id';
  $handler->display->display_options['arguments']['requester_id']['table'] = 'user_relationships';
  $handler->display->display_options['arguments']['requester_id']['field'] = 'requester_id';
  $handler->display->display_options['arguments']['requester_id']['default_action'] = 'not found';
  $handler->display->display_options['arguments']['requester_id']['style_plugin'] = 'default_summary';
  $handler->display->display_options['arguments']['requester_id']['default_argument_type'] = 'fixed';
  $handler->display->display_options['arguments']['requester_id']['break_phrase'] = 0;
  $handler->display->display_options['arguments']['requester_id']['not'] = 0;
  /* Argument: Privacy Settings: Currently requested setting (url arg) >= setting */
  $handler->display->display_options['arguments']['setting_requested']['id'] = 'setting_requested';
  $handler->display->display_options['arguments']['setting_requested']['table'] = 'privacy_node';
  $handler->display->display_options['arguments']['setting_requested']['field'] = 'setting_requested';
  $handler->display->display_options['arguments']['setting_requested']['style_plugin'] = 'default_summary';
  $handler->display->display_options['arguments']['setting_requested']['default_argument_type'] = 'fixed';
  $handler->display->display_options['arguments']['setting_requested']['default_argument_skip_url'] = 0;
  $handler->display->display_options['arguments']['setting_requested']['break_phrase'] = 0;
  $handler->display->display_options['arguments']['setting_requested']['not'] = 0;
  /* Filter: User relationships: Relationship status */
  $handler->display->display_options['filters']['approved']['id'] = 'approved';
  $handler->display->display_options['filters']['approved']['table'] = 'user_relationships';
  $handler->display->display_options['filters']['approved']['field'] = 'approved';
  $handler->display->display_options['filters']['approved']['value'] = '1';
  $handler->display->display_options['filters']['approved']['expose']['operator'] = FALSE;
  $translatables['user_relational_activity'] = array(
    t('Defaults'),
    t('more'),
    t('Apply'),
    t('Reset'),
    t('Sort by'),
    t('Asc'),
    t('Desc'),
    t('Items per page'),
    t('- All -'),
    t('Offset'),
    t('All'),
  );

  $views[$view->name] = $view;

  return $views;
}
