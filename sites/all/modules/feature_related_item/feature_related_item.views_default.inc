<?php

/**
 * Implementation of hook_views_default_views().
 */
function feature_related_item_views_default_views() {
  $views = array();

  // Exported view: related_item
  $view = new view;
  $view->name = 'related_item';
  $view->description = 'Similar By Terms';
  $view->tag = 'similarterms';
  $view->base_table = 'node';
  $view->human_name = '';
  $view->core = 0;
  $view->api_version = '3.0';
  $view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

  /* Display: Defaults */
  $handler = $view->new_display('default', 'Defaults', 'default');
  $handler->display->display_options['title'] = 'Similar By Terms';
  $handler->display->display_options['access']['type'] = 'none';
  $handler->display->display_options['cache']['type'] = 'none';
  $handler->display->display_options['query']['type'] = 'views_query';
  $handler->display->display_options['exposed_form']['type'] = 'basic';
  $handler->display->display_options['pager']['type'] = 'some';
  $handler->display->display_options['pager']['options']['items_per_page'] = '1';
  $handler->display->display_options['pager']['options']['offset'] = '0';
  $handler->display->display_options['style_plugin'] = 'default';
  $handler->display->display_options['row_plugin'] = 'fields';
  /* Field: Node: Title */
  $handler->display->display_options['fields']['title']['id'] = 'title';
  $handler->display->display_options['fields']['title']['table'] = 'node';
  $handler->display->display_options['fields']['title']['field'] = 'title';
  $handler->display->display_options['fields']['title']['label'] = '';
  $handler->display->display_options['fields']['title']['alter']['alter_text'] = 0;
  $handler->display->display_options['fields']['title']['alter']['make_link'] = 0;
  $handler->display->display_options['fields']['title']['alter']['word_boundary'] = 1;
  $handler->display->display_options['fields']['title']['alter']['ellipsis'] = 1;
  $handler->display->display_options['fields']['title']['alter']['strip_tags'] = 0;
  $handler->display->display_options['fields']['title']['alter']['trim'] = 0;
  $handler->display->display_options['fields']['title']['alter']['html'] = 0;
  $handler->display->display_options['fields']['title']['hide_empty'] = 0;
  $handler->display->display_options['fields']['title']['empty_zero'] = 0;
  $handler->display->display_options['fields']['title']['link_to_node'] = 1;
  /* Field: User: Name */
  $handler->display->display_options['fields']['name']['id'] = 'name';
  $handler->display->display_options['fields']['name']['table'] = 'users';
  $handler->display->display_options['fields']['name']['field'] = 'name';
  $handler->display->display_options['fields']['name']['label'] = '';
  $handler->display->display_options['fields']['name']['alter']['alter_text'] = 0;
  $handler->display->display_options['fields']['name']['alter']['make_link'] = 0;
  $handler->display->display_options['fields']['name']['alter']['absolute'] = 0;
  $handler->display->display_options['fields']['name']['alter']['external'] = 0;
  $handler->display->display_options['fields']['name']['alter']['replace_spaces'] = 0;
  $handler->display->display_options['fields']['name']['alter']['trim_whitespace'] = 0;
  $handler->display->display_options['fields']['name']['alter']['nl2br'] = 0;
  $handler->display->display_options['fields']['name']['alter']['word_boundary'] = 1;
  $handler->display->display_options['fields']['name']['alter']['ellipsis'] = 1;
  $handler->display->display_options['fields']['name']['alter']['strip_tags'] = 0;
  $handler->display->display_options['fields']['name']['alter']['trim'] = 0;
  $handler->display->display_options['fields']['name']['alter']['html'] = 0;
  $handler->display->display_options['fields']['name']['element_label_colon'] = 1;
  $handler->display->display_options['fields']['name']['element_default_classes'] = 1;
  $handler->display->display_options['fields']['name']['hide_empty'] = 0;
  $handler->display->display_options['fields']['name']['empty_zero'] = 0;
  $handler->display->display_options['fields']['name']['hide_alter_empty'] = 0;
  $handler->display->display_options['fields']['name']['link_to_user'] = 1;
  $handler->display->display_options['fields']['name']['overwrite_anonymous'] = 0;
  /* Field: Content: Main Image (field_main_image) */
  $handler->display->display_options['fields']['field_main_image_fid']['id'] = 'field_main_image_fid';
  $handler->display->display_options['fields']['field_main_image_fid']['table'] = 'node_data_field_main_image';
  $handler->display->display_options['fields']['field_main_image_fid']['field'] = 'field_main_image_fid';
  $handler->display->display_options['fields']['field_main_image_fid']['alter']['alter_text'] = 0;
  $handler->display->display_options['fields']['field_main_image_fid']['alter']['make_link'] = 0;
  $handler->display->display_options['fields']['field_main_image_fid']['alter']['absolute'] = 0;
  $handler->display->display_options['fields']['field_main_image_fid']['alter']['external'] = 0;
  $handler->display->display_options['fields']['field_main_image_fid']['alter']['replace_spaces'] = 0;
  $handler->display->display_options['fields']['field_main_image_fid']['alter']['trim_whitespace'] = 0;
  $handler->display->display_options['fields']['field_main_image_fid']['alter']['nl2br'] = 0;
  $handler->display->display_options['fields']['field_main_image_fid']['alter']['word_boundary'] = 1;
  $handler->display->display_options['fields']['field_main_image_fid']['alter']['ellipsis'] = 1;
  $handler->display->display_options['fields']['field_main_image_fid']['alter']['strip_tags'] = 0;
  $handler->display->display_options['fields']['field_main_image_fid']['alter']['trim'] = 0;
  $handler->display->display_options['fields']['field_main_image_fid']['alter']['html'] = 0;
  $handler->display->display_options['fields']['field_main_image_fid']['element_label_type'] = '0';
  $handler->display->display_options['fields']['field_main_image_fid']['element_label_colon'] = 0;
  $handler->display->display_options['fields']['field_main_image_fid']['element_default_classes'] = 1;
  $handler->display->display_options['fields']['field_main_image_fid']['empty'] = '<img src="themes/vibio/images/icons/default_item_large.png"/>';
  $handler->display->display_options['fields']['field_main_image_fid']['hide_empty'] = 0;
  $handler->display->display_options['fields']['field_main_image_fid']['empty_zero'] = 0;
  $handler->display->display_options['fields']['field_main_image_fid']['hide_alter_empty'] = 0;
  $handler->display->display_options['fields']['field_main_image_fid']['link_to_node'] = 1;
  $handler->display->display_options['fields']['field_main_image_fid']['label_type'] = 'none';
  $handler->display->display_options['fields']['field_main_image_fid']['format'] = 'collection_fixed_fluid_grid_115_path';
  /* Sort criterion: Similar By Terms: Similarity */
  $handler->display->display_options['sorts']['similarterms']['id'] = 'similarterms';
  $handler->display->display_options['sorts']['similarterms']['table'] = 'similarterms';
  $handler->display->display_options['sorts']['similarterms']['field'] = 'similarterms';
  /* Argument: Similar By Terms: Nid */
  $handler->display->display_options['arguments']['nid']['id'] = 'nid';
  $handler->display->display_options['arguments']['nid']['table'] = 'similarterms';
  $handler->display->display_options['arguments']['nid']['field'] = 'nid';
  $handler->display->display_options['arguments']['nid']['default_action'] = 'default';
  $handler->display->display_options['arguments']['nid']['style_plugin'] = 'default_summary';
  $handler->display->display_options['arguments']['nid']['default_argument_type'] = 'node';
  $handler->display->display_options['arguments']['nid']['validate_type'] = 'node';
  $handler->display->display_options['arguments']['nid']['validate_options']['types'] = array(
    'blog' => 0,
    'product' => 0,
    'presenter' => 0,
    'playlist_video' => 0,
    'standalone_video' => 0,
    'page' => 0,
  );
  $handler->display->display_options['arguments']['nid']['break_phrase'] = 0;
  $handler->display->display_options['arguments']['nid']['not'] = 0;
  /* Argument: Node: Author UID */
  $handler->display->display_options['arguments']['uid']['id'] = 'uid';
  $handler->display->display_options['arguments']['uid']['table'] = 'node';
  $handler->display->display_options['arguments']['uid']['field'] = 'uid';
  $handler->display->display_options['arguments']['uid']['style_plugin'] = 'default_summary';
  $handler->display->display_options['arguments']['uid']['default_argument_type'] = 'fixed';
  $handler->display->display_options['arguments']['uid']['default_argument_skip_url'] = 0;
  $handler->display->display_options['arguments']['uid']['break_phrase'] = 0;
  $handler->display->display_options['arguments']['uid']['not'] = 0;
  /* Argument: Privacy Settings: Currently requested setting (url arg) >= setting */
  $handler->display->display_options['arguments']['setting_requested']['id'] = 'setting_requested';
  $handler->display->display_options['arguments']['setting_requested']['table'] = 'privacy_node';
  $handler->display->display_options['arguments']['setting_requested']['field'] = 'setting_requested';
  $handler->display->display_options['arguments']['setting_requested']['style_plugin'] = 'default_summary';
  $handler->display->display_options['arguments']['setting_requested']['default_argument_type'] = 'fixed';
  $handler->display->display_options['arguments']['setting_requested']['default_argument_skip_url'] = 0;
  $handler->display->display_options['arguments']['setting_requested']['break_phrase'] = 0;
  $handler->display->display_options['arguments']['setting_requested']['not'] = 0;
  /* Filter: Node: In moderation */
  $handler->display->display_options['filters']['moderate']['id'] = 'moderate';
  $handler->display->display_options['filters']['moderate']['table'] = 'node';
  $handler->display->display_options['filters']['moderate']['field'] = 'moderate';
  $handler->display->display_options['filters']['moderate']['value'] = '0';
  $handler->display->display_options['filters']['moderate']['expose']['operator'] = FALSE;
  /* Filter: Node: Published */
  $handler->display->display_options['filters']['status']['id'] = 'status';
  $handler->display->display_options['filters']['status']['table'] = 'node';
  $handler->display->display_options['filters']['status']['field'] = 'status';
  $handler->display->display_options['filters']['status']['value'] = '1';
  $handler->display->display_options['filters']['status']['expose']['operator'] = FALSE;
  /* Filter: Node: Type */
  $handler->display->display_options['filters']['type']['id'] = 'type';
  $handler->display->display_options['filters']['type']['table'] = 'node';
  $handler->display->display_options['filters']['type']['field'] = 'type';
  $handler->display->display_options['filters']['type']['value'] = array(
    'vibio_item' => 'vibio_item',
  );
  /* Filter: User: Name */
  $handler->display->display_options['filters']['uid']['id'] = 'uid';
  $handler->display->display_options['filters']['uid']['table'] = 'users';
  $handler->display->display_options['filters']['uid']['field'] = 'uid';
  $handler->display->display_options['filters']['uid']['operator'] = 'not in';
  $handler->display->display_options['filters']['uid']['value'] = array(
    0 => 0,
    1 => '1',
  );
  /* Filter: Content: Main Image (field_main_image) - list */
  $handler->display->display_options['filters']['field_main_image_list']['id'] = 'field_main_image_list';
  $handler->display->display_options['filters']['field_main_image_list']['table'] = 'node_data_field_main_image';
  $handler->display->display_options['filters']['field_main_image_list']['field'] = 'field_main_image_list';
  $handler->display->display_options['filters']['field_main_image_list']['value'] = '1';

  /* Display: Block */
  $handler = $view->new_display('block', 'Block', 'block_1');
  $handler->display->display_options['block_caching'] = '4';

  /* Display: Random Item */
  $handler = $view->new_display('block', 'Random Item', 'random_item');
  $handler->display->display_options['defaults']['sorts'] = FALSE;
  /* Sort criterion: Global: Random */
  $handler->display->display_options['sorts']['random']['id'] = 'random';
  $handler->display->display_options['sorts']['random']['table'] = 'views';
  $handler->display->display_options['sorts']['random']['field'] = 'random';
  $handler->display->display_options['defaults']['arguments'] = FALSE;
  /* Argument: Node: Author UID */
  $handler->display->display_options['arguments']['uid']['id'] = 'uid';
  $handler->display->display_options['arguments']['uid']['table'] = 'node';
  $handler->display->display_options['arguments']['uid']['field'] = 'uid';
  $handler->display->display_options['arguments']['uid']['style_plugin'] = 'default_summary';
  $handler->display->display_options['arguments']['uid']['default_argument_type'] = 'fixed';
  /* Argument: Privacy Settings: Currently requested setting (url arg) >= setting */
  $handler->display->display_options['arguments']['setting_requested']['id'] = 'setting_requested';
  $handler->display->display_options['arguments']['setting_requested']['table'] = 'privacy_node';
  $handler->display->display_options['arguments']['setting_requested']['field'] = 'setting_requested';
  $handler->display->display_options['arguments']['setting_requested']['style_plugin'] = 'default_summary';
  $handler->display->display_options['arguments']['setting_requested']['default_argument_type'] = 'fixed';
  $translatables['related_item'] = array(
    t('Defaults'),
    t('Similar By Terms'),
    t('more'),
    t('Apply'),
    t('Reset'),
    t('Sort by'),
    t('Asc'),
    t('Desc'),
    t('<img src="themes/vibio/images/icons/default_item_large.png"/>'),
    t('All'),
    t('Block'),
    t('Random Item'),
  );

  $views[$view->name] = $view;

  return $views;
}
